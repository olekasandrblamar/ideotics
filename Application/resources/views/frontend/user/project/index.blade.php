@extends('frontend.user.layouts.dash')
@section('title', lang('Projects', 'projects'))
@section('content')
    <div class="section-body">
        <div class="card-v v3">
            <div class="card-v-body">
                <div class="col-md-12">
                    <div class="d-flex j-right">
                        <form action="{{ route('user.projects.index') }}" class="me-2" method="GET">
                            <div class="form-search w-100">
                                <input type="text" name="search"
                                    class="form-control form-control-md"
                                    placeholder="{{ lang('Search...', 'videos') }}"
                                    value="{{ request()->input('search') ?? '' }}">
                                <button class="icon">
                                    <i class="fa fa-search"></i>
                                    </button>
                            </div>
                        </form>
                        <button class="btn btn-primary btn-md">
                            <i class="fa fa-upload me-2"></i>{{ lang('Add', 'projects')}}
                        </button>
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="table-responsive mt-2">
                        <table class="vironeer-normal-table table w-100">
                            <thead>
                                <tr>
                                    <th class="tb-w-3x">
                                        <input class="multiple-select-check-all form-check-input" type="checkbox">
                                    </th>
                                    <th class="tb-w-20x">{{ lang('Project name', 'projects') }}</th>
                                    <th class="tb-w-7x text-center">{{ lang('Created date', 'projects') }}</th>
                                    <th class="text-end"><i class="fas fa-sliders-h me-1"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projects as $project)
                                    <tr>
                                        <td>
                                            <input class="form-check-input multiple-select-checkbox"
                                                data-id="{{ $fileEntry->id }}" type="checkbox">
                                        </td>
                                        <td>
                                            {{ $project->title}}
                                        </td>
                                        <td class="text-center">{{ vDate($project->created_at) }}</td>
                                        <td>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                                    aria-expanded="true">
                                                    <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-md-end dropdown-menu-lg"
                                                    data-popper-placement="bottom-end">
                                                    <li>
                                                        <a href=""
                                                            class="dropdown-item">
                                                            <i class="fa fa-edit"></i>
                                                            {{ lang('Edit', 'projects') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider" />
                                                    </li>
                                                    <li>
                                                        <a href="#" class="dropdown-item text-danger delete-file"
                                                            data-id="">
                                                            <i class="fa-regular fa-trash-can"></i>
                                                            {{ lang('Delete', 'projects') }}
                                                        </a>
                                                        <form id="deleteFile{{ $project->id }}"
                                                            action="" method="POST">
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

                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection
