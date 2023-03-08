@extends('frontend.user.layouts.dash')
@section('title', lang('My Videos', 'videos'))
@section('upload', true)
@section('content')
    @if ($dCameras->count() > 0)
        <div class="section-body">

            <div class="card-v v3">
                <div class="card-v-body">
                    <div class="col-md-12">
                        <div class="col-md-12">
                        <form class="multiple-select-delete-form d-none" action="{{ route('user.project.destroy.all') }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="ids" class="multiple-select-delete-ids">
                            <input type="hidden" name="project_id" class="" value="{{ $breadCrumbProject->id }}">
                            <button class="vironeer-able-to-delete btn btn-danger confirm-action-form"><i
                                    class="far fa-trash-alt me-2"></i>{{ lang('Delete Selected', 'videos') }}</button>
                        </form>
                        </div>
                        <div class="table-responsive mt-2">
                            <table class="vironeer-normal-table table w-100">
                                <thead>
                                    <tr>
                                        <th class="tb-w-3x">
                                            <input class="multiple-select-check-all form-check-input" type="checkbox">
                                        </th>
                                        <th class="tb-w-20x">{{ lang('camera Name', 'videos') }}</th>
                                        <th class="tb-w-7x text-center">{{ lang('Created date', 'videos') }}</th>
                                        <th class="text-end"><i class="fas fa-sliders-h me-1"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dCameras as $camera)
                                        <tr>
                                            <td>
                                                <input class="form-check-input multiple-select-checkbox"
                                                    data-id="{{ $camera->id }}" type="checkbox">
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a class="vironeer-content-image text-center me-3"
                                                        href="{{ route('user.videos.videos.index', ['project_id' => $breadCrumbProject->id, 'camera_id' => $camera->id]) }}">
                                                        <img src="{{ asset('/images/icons/folder.png')}}"
                                                            alt="{{ $camera->title }}">
                                                    </a>
                                                    <div class="my-auto">
                                                        <a class="text-reset"
                                                            href="{{ route('user.videos.videos.index', ['project_id' => $breadCrumbProject->id, 'camera_id' => $camera->id]) }}">{{ $camera->title }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ vDate($camera->created_at) }}</td>
                                            <td>
                                                <div class="text-end">
                                                    <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                                        aria-expanded="true">
                                                        <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-md-end dropdown-menu-lg"
                                                        data-popper-placement="bottom-end">
                                                        <li>
                                                            <a href="{{ route('user.project.edit', $camera->id) }}"
                                                                class="dropdown-item">
                                                                <i class="fa fa-edit"></i>
                                                                {{ lang('Edit', 'videos') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider" />
                                                        </li>
                                                        <li>
                                                            <a href="#" class="dropdown-item text-danger delete-file"
                                                                data-id="{{ $camera->id }}">
                                                                <i class="fa-regular fa-trash-can"></i>
                                                                {{ lang('Delete', 'videos') }}
                                                            </a>
                                                            <form id="deleteFile{{ $camera->id }}"
                                                                action="{{ route('user.project.destroy', $camera->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $dCameras->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    @else
        @section('empty', true)
        @if (request()->has('search'))
            @include('frontend.global.includes.noResults')
        @else
            @include('frontend.user.includes.empty')
        @endif
    @endif
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection
