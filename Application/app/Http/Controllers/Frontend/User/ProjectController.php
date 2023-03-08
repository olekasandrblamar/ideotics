<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\FileEntry;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::currentUser()->orderBy('title', 'asc')->get();
        return view('frontend.user.project.index', ['projects' => $projects]);
    }

    public function create(){
        return view('frontend.user.project.create');
    }

    // public function create(Request $request)
    // {
    //     $projectOrCamera = new ProjectAndCamera();
    //     $type = $request->type;
    //     if($request->type == 'project'){
    //         $title = $request->project_name;
    //         $exist = ProjectAndCamera::where('title', $title)->where('type', $type)->count();
    //         if($exist){
    //             $message = lang('The project name is existed already', 'projects');
    //             toastr()->error($message);
    //             return back();
    //         }
    //         $projectOrCamera->title = $request->project_name;
    //         $message = lang('New project name is created successfully', 'projects');
    //     }
    //     if($request->type == 'camera'){
    //         $title = $request->camera_name;
    //         $exist = ProjectAndCamera::where('title', $title)->where('type', $type)->count();
    //         if($exist){
    //             $message = lang('The camera name is existed already', 'projects');
    //             toastr()->error($message);
    //             return back();
    //         }
    //         $projectOrCamera->title = $title;
    //         $message = lang('New camera name is created successfully', 'projects');
    //     }
    //     $projectOrCamera->type = $type;
    //     $projectOrCamera->user_id = userAuthInfo()->id;
    //     $projectOrCamera->save();
    //     toastr()->success($message);
    //     return  redirect()->route('user.project.index');
    // }

    public function edit($id)
    {
        $projectOrCamera = ProjectAndCamera::where('id', $id)->currentUser()->firstOrFail();
        return view('frontend.user.project.edit', ['projectOrCamera' => $projectOrCamera]);
    }

    public function update(Request $request, $id)
    {
        $projectOrCamera = ProjectAndCamera::find($id);
        $projectOrCamera->title = $request->title;
        $projectOrCamera->save();
        toastr()->success(lang('Updated successfully', 'videos'));
        return  redirect()->back();
    }

    public function destroy($id)
    {
        $projectOrCamera = ProjectAndCamera::find($id);
        if($projectOrCamera->type == 'project'){
            $shared_ids = FileEntry::where('project_id', $id)->select('shared_id')->get();
        }
        if($projectOrCamera->type == 'camera'){
            $shared_ids = FileEntry::where('camera_id', $id)->select('shared_id')->get();
        }
        foreach ($shared_ids as $shared_id) {
            $fileEntry = FileEntry::where('shared_id', $shared_id->shared_id)->currentUser()->notExpired()->first();
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
        $projectOrCamera->delete();
        toastr()->success(lang('Deleted successfully', 'videos'));
        return back();
    }

    public function destroyAll(Request $request)
    {
        if (empty($request->ids)) {
            toastr()->error(lang('You have not selected any project', 'videos'));
            return back();
        }
        $ids = explode(',', $request->ids);
        foreach ($ids as $id) {
            $projectOrCamera = ProjectAndCamera::find($id);
            if(!isset($request->project_id)){
                $shared_ids = FileEntry::where('project_id', $id)->select('shared_id')->get();
                foreach ($shared_ids as $shared_id) {
                    $fileEntry = FileEntry::where('shared_id', $shared_id->shared_id)->currentUser()->notExpired()->first();
                    if (!is_null($fileEntry)) {
                        try {
                            $handler = $fileEntry->storageProvider->handler;
                            $delete = $handler::delete($fileEntry->path);
                            if ($delete) {
                                $fileEntry->delete();
                            }
                        } catch (\Exception $e) {
                            toastr()->error(lang('Video not found, missing or expired please refresh the page and try again', 'videos'));
                            return back();
                        }
                    }
                }
            }else{
                $fileEntry = FileEntry::where('project_id', $request->project_id)->where('camera_id', $id)->currentUser()->notExpired()->first();
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

            $projectOrCamera->delete();
        }
        toastr()->success(lang('Deleted successfully', 'videos'));
        return back();
    }
}
