<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\ProjectAndCamera;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = ProjectAndCamera::currentUser()->where('type', 'project')->get();
        $cameras = ProjectAndCamera::currentUser()->where('type', 'camera')->get();
        return view('frontend.user.project.index', ['projects' => $projects, 'cameras' => $cameras]);
    }

    public function create(Request $request)
    {
        $projectOrCamera = new ProjectAndCamera();
        $type = $request->type;
        if($request->type == 'project'){
            $title = $request->project_name;
            $exist = ProjectAndCamera::where('title', $title)->where('type', $type)->count();
            if($exist){
                $message = lang('The project name is existed already', 'projects');
                toastr()->error($message);
                return back();
            }
            $projectOrCamera->title = $request->project_name;
            $message = lang('New project name is created successfully', 'projects');
        }
        if($request->type == 'camera'){
            $title = $request->camera_name;
            $exist = ProjectAndCamera::where('title', $title)->where('type', $type)->count();
            if($exist){
                $message = lang('The camera name is existed already', 'projects');
                toastr()->error($message);
                return back();
            }
            $projectOrCamera->title = $title;
            $message = lang('New camera name is created successfully', 'projects');
        }
        $projectOrCamera->type = $type;
        $projectOrCamera->user_id = userAuthInfo()->id;
        $projectOrCamera->save();
        toastr()->success($message);
        return  redirect()->route('user.project.index');
    }

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
        return  redirect()->route('user.project.index');
    }

    public function destroy($id)
    {
        ProjectAndCamera::where('id', $id)->delete();
        toastr()->success(lang('Deleted successfully', 'videos'));
        return back();
    }

    public function destroyAll(Request $request)
    {
        // if (empty($request->ids)) {
        //     toastr()->error(lang('You have not selected any video', 'videos'));
        //     return back();
        // }
        // $ids = explode(',', $request->ids);
        // foreach ($ids as $shared_id) {
        //     $fileEntry = FileEntry::where('shared_id', $shared_id)->currentUser()->notExpired()->first();
        //     if (!is_null($fileEntry)) {
        //         try {
        //             $handler = $fileEntry->storageProvider->handler;
        //             $delete = $handler::delete($fileEntry->path);
        //             if ($delete) {
        //                 $fileEntry->delete();
        //             }
        //         } catch (\Exception$e) {
        //             toastr()->error(lang('Video not found, missing or expired please refresh the page and try again', 'videos'));
        //             return back();
        //         }
        //     }
        // }
        // toastr()->success(lang('Deleted successfully', 'videos'));
        // return back();
    }
}
