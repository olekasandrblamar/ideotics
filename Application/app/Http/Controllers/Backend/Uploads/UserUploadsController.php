<?php

namespace App\Http\Controllers\Backend\Uploads;

use App\Http\Controllers\Controller;
use App\Models\FileEntry;
use Illuminate\Http\Request;

class UserUploadsController extends Controller
{
    public function index(Request $request)
    {
        $unviewedFiles = FileEntry::where('admin_has_viewed', 0)->userEntry()->notExpired()->get();
        if (count($unviewedFiles) > 0) {
            foreach ($unviewedFiles as $unviewedFile) {
                $unviewedFile->admin_has_viewed = 1;
                $unviewedFile->save();
            }
        }
        $totalUploads = FileEntry::userEntry()->notExpired()->count();
        $usedSpace = FileEntry::userEntry()->notExpired()->sum('size');
        if ($request->has('search')) {
            $q = $request->search;
            $fileEntries = FileEntry::where(function ($query) {
                $query->userEntry();
            })->where(function ($query) use ($q) {
                $query->where('shared_id', 'like', '%' . $q . '%')
                    ->OrWhere('name', 'like', '%' . $q . '%')
                    ->OrWhere('filename', 'like', '%' . $q . '%')
                    ->OrWhere('mime', 'like', '%' . $q . '%')
                    ->OrWhere('size', 'like', '%' . $q . '%')
                    ->OrWhere('extension', 'like', '%' . $q . '%');
            })->notExpired()->with('storageProvider')->orderbyDesc('id')->paginate(30);
            $fileEntries->appends(['search' => $q]);
        } elseif ($request->has('user')) {
            $fileEntries = FileEntry::where('user_id', unhashid($request->user))
                ->notExpired()
                ->with(['storageProvider'])
                ->orderbyDesc('id')
                ->paginate(30);
            $fileEntries->appends(['user' => $request->user]);
            $totalUploads = FileEntry::where('user_id', unhashid($request->user))->userEntry()->notExpired()->count();
            $usedSpace = FileEntry::where('user_id', unhashid($request->user))->userEntry()->notExpired()->sum('size');
        } else {
            $fileEntries = FileEntry::userEntry()->notExpired()->with('storageProvider')->orderbyDesc('id')->paginate(30);
        }
        return view('backend.uploads.users.index', [
            'fileEntries' => $fileEntries,
            'totalUploads' => formatNumber($totalUploads),
            'usedSpace' => formatBytes($usedSpace),
        ]);
    }

    public function view($shared_id)
    {
        $fileEntry = FileEntry::where('shared_id', $shared_id)->userEntry()->notExpired()->with(['user', 'storageProvider'])->firstOrFail();
        if($fileEntry->scan_status == 1){
            $prefix = substr($fileEntry->filename, 0 , strlen($fileEntry->filename) - 4);

            $fileEntry->fetch_uploaded_s3_path = $fileEntry->filename;
            $fileEntry->csv_file_path = $prefix."_id_sorted.csv";
            $fileEntry->json_file1_path = $prefix."_id_sorted.json";
            $fileEntry->json_file2_path = $prefix."_time_sorted.json";
            $fileEntry->processed_video_s3_path = $prefix."_tracked.mp4";
            $fileEntry->processed_video_s3_file_path = str_replace($fileEntry->filename, $prefix."_tracked.mp4", $fileEntry->link);
        }
        return view('backend.uploads.users.view', ['fileEntry' => $fileEntry]);
    }

    public function downloadFile($shared_id)
    {
        $fileEntry = FileEntry::where('shared_id', $shared_id)->userEntry()->notExpired()->with('storageProvider')->firstOrFail();
        try {
            $handler = $fileEntry->storageProvider->handler;
            $download = $handler::download($fileEntry);
            if ($fileEntry->storageProvider->symbol == "local") {
                return $download;
            } else {
                return redirect($download);
            }
        } catch (\Exception$e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }

    public function destroy($shared_id)
    {
        $fileEntry = FileEntry::where('shared_id', $shared_id)->userEntry()->notExpired()->with('storageProvider')->firstOrFail();
        try {
            $handler = $fileEntry->storageProvider->handler;
            $delete = $handler::delete($fileEntry->path);
            if ($delete) {
                $fileEntry->forceDelete();
                toastr()->success(__('Deleted successfully'));
                return redirect()->route('admin.uploads.users.index');
            }
        } catch (\Exception$e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }

    public function destroySelected(Request $request)
    {
        if (empty($request->delete_ids)) {
            toastr()->error(__('You have not selected any file'));
            return back();
        }
        try {
            $fileEntriesIds = explode(',', $request->delete_ids);
            $totalDelete = 0;
            foreach ($fileEntriesIds as $fileEntryId) {
                $fileEntry = FileEntry::where('id', $fileEntryId)->userEntry()->notExpired()->with('storageProvider')->first();
                if (!is_null($fileEntry)) {
                    $handler = $fileEntry->storageProvider->handler;
                    $handler::delete($fileEntry->path);
                    $fileEntry->forceDelete();
                    $totalDelete += 1;
                }
            }
            if ($totalDelete != 0) {
                $countFiles = ($totalDelete > 1) ? $totalDelete . ' ' . __('Files') : $totalDelete . ' ' . __('File');
                toastr()->success($countFiles . ' ' . __('deleted successfully'));
                return back();
            } else {
                toastr()->info(__('No files have been deleted'));
                return back();
            }
        } catch (\Exception$e) {
            toastr()->error($e->getMessage());
            return back();
        }
    }

    public function scanVideo($id){
        $result = [];
        $fileEntry = FileEntry::where('id', $id)->userEntry()->notExpired()->with(['user', 'storageProvider'])->firstOrFail();
        try {
          if($fileEntry->storageProvider->symbol == 's3'){
              $result['status'] = $this->scanCurl('s3://ideoticsv2/' . $fileEntry->path);
              if($result['status'] == 'success'){
                $fileEntry = FileEntry::find($id);
                $fileEntry->scan_status = 1;
                $fileEntry->save();
              }
          }else{
              $result['status'] = null;
          }
        } catch (\Exception$e) {
            toastr()->error($e->getMessage());
            return back();
        }
        return $result;
    }

    public function scanCurl($s3_url){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => env('Scan_Video_Url'),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"data":["' . $s3_url . '",10,"aW191OwmDG97JZizLgY0MQbHBJ4TJN7R"]}',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        if($response->data[0]->status == 'success'){
          return $response->data[0]->status;
        }else{
          return null;
        }

    }
}
