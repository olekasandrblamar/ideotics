<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\FileEntry;
use App\Models\ProjectAndCamera;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class VideoController extends Controller
{
    public function videos_index($project_id, $camera_id)
    {
        $breadCrumbProject = ProjectAndCamera::currentUser()->where('type', 'project')->find($project_id);
        $breadCrumbCamera = ProjectAndCamera::currentUser()->where('type', 'camera')->find($camera_id);
        $projects = ProjectAndCamera::currentUser()->where('type', 'project')->orderBy('title', 'asc')->get();
        $cameras = ProjectAndCamera::currentUser()->where('type', 'camera')->orderBy('title', 'asc')->get();

        if (request()->has('search')) {
            $q = request()->input('search');
            $fileEntries = FileEntry::where(function ($query) use ($project_id, $camera_id) {
                $query->currentUser()->where('project_id', $project_id)->where('camera_id', $camera_id);
            })->where(function ($query) use ($q) {
                $query->where('shared_id', 'like', '%' . $q . '%')
                    ->OrWhere('name', 'like', '%' . $q . '%')
                    ->OrWhere('filename', 'like', '%' . $q . '%')
                    ->OrWhere('mime', 'like', '%' . $q . '%')
                    ->OrWhere('size', 'like', '%' . $q . '%')
                    ->OrWhere('extension', 'like', '%' . $q . '%');
            })->notExpired()->orderByDesc('id')->paginate(20);
            $fileEntries->appends(['search' => $q]);
        } else {
            $fileEntries = FileEntry::currentUser()->notExpired()->where('project_id', $project_id)->where('camera_id', $camera_id)->orderbyDesc('id')->paginate(20);
        }

        return view('frontend.user.videos.index', [
            'breadCrumbType' => 'camera',
            'breadCrumbProject' => $breadCrumbProject,
            'breadCrumbCamera' =>$breadCrumbCamera,
            'fileEntries' => $fileEntries,
            'projects' => $projects,
            'cameras' => $cameras,
        ]);
    }

    function projects_index(){
        $projects = ProjectAndCamera::currentUser()->where('type', 'project')->orderBy('title', 'asc')->get();
        $cameras = ProjectAndCamera::currentUser()->where('type', 'camera')->orderBy('title', 'asc')->get();

        if (request()->has('search')) {
            $q = request()->input('search');
            $dProjects = ProjectAndCamera::where(function ($query) {
                $query->currentUser()->where('type', 'project');
            })->where(function ($query) use ($q) {
                $query->where('title', 'like', '%' . $q . '%');
            })->notExpired()->orderBy('title', 'asc')->paginate(20);
            $dProjects->appends(['search' => $q]);
        } else {
            $dProjects = ProjectAndCamera::currentUser()->where('type', 'project')->orderBy('title', 'asc')->paginate(20);
        }
        return view('frontend.user.videos.projects', [
            'dProjects' => $dProjects,
            'projects' => $projects,
            'cameras' => $cameras,
        ]);
    }

    function cameras_index($project_id){
        $breadCrumbProject = ProjectAndCamera::currentUser()->where('type', 'project')->find($project_id);
        $cameras_id = FileEntry::currentUser()->notExpired()->where('project_id', $project_id)->groupBy('camera_id')->pluck('camera_id')->toArray();
        $projects = ProjectAndCamera::currentUser()->where('type', 'project')->orderBy('title', 'asc')->get();
        $cameras = ProjectAndCamera::currentUser()->where('type', 'camera')->orderBy('title', 'asc')->get();

        if (request()->has('search')) {
            $q = request()->input('search');
            $dCameras = ProjectAndCamera::where(function ($query) use ($cameras_id) {
                $query->currentUser()->where('type', 'camera')->whereIn('id', $cameras_id);
            })->where(function ($query) use ($q) {
                $query->where('title', 'like', '%' . $q . '%');
            })->notExpired()->orderBy('title', 'asc')->paginate(20);
            $dCameras->appends(['search' => $q]);
        } else {
            $dCameras = ProjectAndCamera::currentUser()->where('type', 'camera')->whereIn('id', $cameras_id)->orderBy('title', 'asc')->paginate(20);
        }
        return view('frontend.user.videos.cameras', [
            'breadCrumbType' => 'project',
            'breadCrumbProject' => $breadCrumbProject,
            'dCameras' => $dCameras,
            'projects' => $projects,
            'cameras' => $cameras,
        ]);
    }

    public function edit($shared_id)
    {
        $fileEntry = FileEntry::where('shared_id', $shared_id)->currentUser()->notExpired()->firstOrFail();
        return view('frontend.user.videos.edit', ['fileEntry' => $fileEntry]);
    }

    public function update(Request $request, $shared_id)
    {
        $fileEntry = FileEntry::where('shared_id', $shared_id)->currentUser()->notExpired()->first();
        if (is_null($fileEntry)) {
            toastr()->error(lang('Video not found, missing or expired please refresh the page and try again', 'alerts'));
            return back();
        }
        $validator = Validator::make($request->all(), [
            'filename' => ['required', 'string', 'max:255'],
            'access_status' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                toastr()->error($error);
            }
            return back();
        }
        if ($request->has('password') && !is_null($request->password)) {
            if (uploadSettings()->upload->password_protection) {
                $request->password = Hash::make($request->password);
            } else {
                $request->password = null;
            }
        }
        $update = $fileEntry->update([
            'name' => $request->filename,
            'access_status' => $request->access_status,
            'password' => $request->password,
        ]);
        if ($update) {
            toastr()->success(lang('Updated successfully', 'videos'));
            return back();
        }
    }

    public function download($shared_id)
    {
        if (!uploadSettings()->upload->allow_downloading) {
            toastr()->error(lang('There was a problem while trying to download the video', 'alerts'));
            return back();
        }
        $fileEntry = FileEntry::where('shared_id', $shared_id)->currentUser()->notExpired()->firstOrFail();
        try {
            $handler = $fileEntry->storageProvider->handler;
            $download = $handler::download($fileEntry);
            if ($fileEntry->storageProvider->symbol == "local") {
                return $download;
            } else {
                return redirect($download);
            }
        } catch (Exception $e) {
            toastr()->error(lang('There was a problem while trying to download the video', 'alerts'));
            return redirect()->route('user.videos.index');
        }
    }

    public function destroy($shared_id)
    {
        $fileEntry = FileEntry::where('shared_id', $shared_id)->currentUser()->notExpired()->firstOrFail();
        try {
            $handler = $fileEntry->storageProvider->handler;
            $delete = $handler::delete($fileEntry->path);
            if ($delete) {
                $fileEntry->delete();
                toastr()->success(lang('Deleted successfully', 'videos'));
                return redirect()->back();
            }
        } catch (\Exception$e) {
            toastr()->error(lang('There was a problem while trying to delete the video', 'videos'));
            return back();
        }
    }

    public function destroyAll(Request $request)
    {
        if (empty($request->ids)) {
            toastr()->error(lang('You have not selected any video', 'videos'));
            return back();
        }
        $ids = explode(',', $request->ids);
        foreach ($ids as $shared_id) {
            $fileEntry = FileEntry::where('shared_id', $shared_id)->currentUser()->notExpired()->first();
            if (!is_null($fileEntry)) {
                try {
                    $handler = $fileEntry->storageProvider->handler;
                    $delete = $handler::delete($fileEntry->path);
                    if ($delete) {
                        $fileEntry->delete();
                    }
                } catch (\Exception$e) {
                    toastr()->error(lang('Video not found, missing or expired please refresh the page and try again', 'videos'));
                    return back();
                }
            }
        }
        toastr()->success(lang('Deleted successfully', 'videos'));
        return back();
    }
}
