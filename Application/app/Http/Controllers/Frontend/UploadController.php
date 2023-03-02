<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Methods\UploadSettingsManager;
use App\Models\FileEntry;
use App\Models\StorageProvider;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Str;
use Validator;
use FFMpeg;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $uploadedFile = $request->file('file');
        $uploadedFileName = $uploadedFile->getClientOriginalName();
        $validator = Validator::make($request->all(), [
            'password' => ['nullable', 'max:255'],
            'upload_auto_delete' => ['required', 'integer', 'min:0', 'max:365'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'project_name' => ['required'],
            'camera_name' => ['required'],
        ]);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $project_id = $request->project_name;
        $camera_id = $request->camera_name;
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                return static::errorResponseHandler($error . ' (' . $uploadedFileName . ')');
            }
        }
        $allowedTypes = explode(',', allowedTypes());
        if (!in_array($request->type, $allowedTypes)) {
            return static::errorResponseHandler(lang('You cannot upload files of this type.', 'upload zone'));
        }
        $uploadSettings = UploadSettingsManager::handler();
        if (!$uploadSettings->active) {
            return static::errorResponseHandler(lang('Login or create account to start uploading videos', 'upload zone'));
        }
        if (!array_key_exists($request->upload_auto_delete, autoDeletePeriods())) {
            return static::errorResponseHandler(lang('Invalid file auto delete time', 'upload zone'));
        } else {
            if (autoDeletePeriods()[$request->upload_auto_delete]['days'] != 0) {
                $expiryAt = autoDeletePeriods()[$request->upload_auto_delete]['datetime'];
            } else {
                $expiryAt = null;
            }
        }
        if ($request->has('password') && !is_null($request->password) && $request->password != "undefined") {
            if ($uploadSettings->upload->password_protection) {
                $request->password = Hash::make($request->password);
            } else {
                $request->password = null;
            }
        }
        if (!is_null($uploadSettings->upload->file_size)) {
            if ($request->size > $uploadSettings->upload->file_size) {
                return static::errorResponseHandler(str_replace('{maxFileSize}', $uploadSettings->formates->file_size, lang('File is too big, Max file size {maxFileSize}', 'upload zone')));
            }
        }
        if (!is_null($uploadSettings->storage->remining->number)) {
            if ($request->size > $uploadSettings->storage->remining->number) {
                return static::errorResponseHandler(lang('insufficient storage space please ensure sufficient space', 'upload zone'));
            }
        }
        $storageProvider = StorageProvider::where([['symbol', env('FILESYSTEM_DRIVER')], ['status', 1]])->first();
        if (is_null($storageProvider)) {
            return static::errorResponseHandler(lang('Unavailable storage provider', 'upload zone'));
        }
        $receiver = new FileReceiver('file', $request, HandlerFactory::classFromRequest($request));
        try {
            if ($receiver->isUploaded() === false || hasUploaded() == false) {
                return static::errorResponseHandler(str_replace('{filename}', $uploadedFileName, lang('Failed to upload ({filename})', 'upload zone')));
            }
            $save = $receiver->receive();
            if ($save->isFinished() && hasUploaded() == true) {
                $file = $save->getFile();
                $fileName = $file->getClientOriginalName();
                $fileMimeType = $file->getMimeType();
                $fileExtension = $file->getClientOriginalExtension();
                $fileSize = $file->getSize();
                if (!in_array($fileMimeType, $allowedTypes)) {
                    return static::errorResponseHandler(lang('You cannot upload files of this type.', 'upload zone'));
                }
                if ($fileSize == 0) {
                    return static::errorResponseHandler(lang('Empty files cannot be uploaded', 'upload zone'));
                }
                if (!is_null($uploadSettings->upload->file_size)) {
                    if ($fileSize > $uploadSettings->upload->file_size) {
                        return static::errorResponseHandler(str_replace('{maxFileSize}', $uploadSettings->formates->file_size, lang('File is too big, Max file size {maxFileSize}', 'upload zone')));
                    }
                }
                if (!is_null($uploadSettings->storage->remining->number)) {
                    if ($fileSize > $uploadSettings->storage->remining->number) {
                        return static::errorResponseHandler(lang('insufficient storage space please ensure sufficient space', 'upload zone'));
                    }
                }
                $ip = vIpInfo()->ip;
                $sharedId = Str::random(15);
                $userId = (Auth::user()) ? Auth::user()->id : null;
                $location = (Auth::user()) ? "users/" . hashid(userAuthInfo()->id) . "/" . hashid($project_id) . "/" . hashid($camera_id) . "/" : "guests/";
                $handler = $storageProvider->handler;
                $uploadResponse = "";
                if($fileExtension !== 'mp4'){
                    $uploadResponse = \App\Http\Controllers\Frontend\Storage\LocalController::upload($file, $location);
                    $c_filename = str_replace($fileExtension, "mp4", $uploadResponse->filename);
                    $c_path = str_replace($fileExtension, "mp4", $uploadResponse->path);
                    $link = base_path($uploadResponse->path);
                    $link = str_replace( 'Application\\', "" , $link);
                    $link = str_replace("\\", "/", $link);
                    $convert_link = str_replace($fileExtension, "mp4", $link);
                    $ffmpeg = FFMpeg\FFMpeg::create([
                        'ffmpeg.binaries'  => env('FFMPEG_BINARIES'),
                        'ffprobe.binaries' => env('FFPROBE_BINARIES'),
                        'timeout'          => 3600,
                        'ffmpeg.threads'   => 12,
                    ]);
                    $video = $ffmpeg->open($link);
                    $format = new FFMpeg\Format\Video\X264();
                    $format->setAudioCodec("libmp3lame");
                    $video->save($format, $convert_link);
                    if(env('FILESYSTEM_DRIVER') == 'local'){
                        $uploadResponse = responseHandler([
                            "type" => "success",
                            "filename" => $c_filename,
                            "path" => $c_path,
                            "link" => url($c_path),
                        ]);
                    }
                    if(env('FILESYSTEM_DRIVER') == 's3'){
                        $disk = Storage::disk('s3');
                        $uploadToStorage = $disk->put($c_path, file_get_contents($convert_link));
                        if ($uploadToStorage) {
                            $uploadResponse = responseHandler([
                                "type" => "success",
                                "filename" => $c_filename,
                                "path" => $c_path,
                                "link" => $disk->url($c_path),
                            ]);
                        }
                        unlink($convert_link);
                    }
                    if(env('FILESYSTEM_DRIVER') == 'wasabi'){
                        $disk = Storage::disk('wasabi');
                        $uploadToStorage = $disk->put($c_path, file_get_contents($convert_link));
                        if ($uploadToStorage) {
                            $uploadResponse = responseHandler([
                                "type" => "success",
                                "filename" => $c_filename,
                                "path" => $c_path,
                                "link" => $disk->url($c_path),
                            ]);
                        }
                        unlink($convert_link);
                    }
                    unlink($link);
                }else{
                    $uploadResponse = $handler::upload($file, $location);
                }
                if ($uploadResponse->type == "error") {
                    return $uploadResponse;
                }
                $createFileEntry = FileEntry::create([
                    'ip' => $ip,
                    'shared_id' => $sharedId,
                    'user_id' => $userId,
                    'storage_provider_id' => $storageProvider->id,
                    'name' => $fileName,
                    'mime' => $fileMimeType,
                    'size' => $fileSize,
                    'extension' => $fileExtension,
                    'filename' => $uploadResponse->filename,
                    'path' => $uploadResponse->path,
                    'link' => $uploadResponse->link,
                    'access_status' => 0,
                    'password' => $request->password,
                    'expiry_at' => $expiryAt,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'project_id' => $project_id,
                    'camera_id' => $camera_id,
                ]);
                return response()->json([
                    'type' => 'success',
                    'file_id' => $createFileEntry->shared_id,
                    'file_link' => route('file.view', $createFileEntry->shared_id),
                ]);
            }
        } catch (Exception $e) {
            return static::errorResponseHandler(str_replace('{filename}', $uploadedFileName, lang('Failed to upload ({filename})', 'upload zone')));
        }
    }

    private static function errorResponseHandler($response)
    {
        return response()->json(['type' => 'error', 'msg' => $response]);
    }
}
