@extends('frontend.user.layouts.dash')
@section('title', lang('My Projects & Cameras', 'Project & Cameras'))
@section('content')
    <div class="row row-cols-1 row-cols-md-2 justify-content-center g-3">
        <div class="col">
            <div class="card-v v3">
                <div class="card-v-body">
                    <div class="stats">
                        <div class="stats-info">
                            <div class="stats-meta">
                                <h3 class="stats-title">{{ lang('New Project', 'projects') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('user.project.create') }}" method="POST">
                            @csrf
                            <div class="form-search w-100">
                                <input type="text" name="project_name"
                                    class="form-control form-control-md"
                                    placeholder="{{ lang('Project Name', 'projects') }}"
                                    value="" required>
                                <input type="hidden" name="type" value="project">
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <button class="btn btn-danger w-100 btn-md">
                                        {{ lang('Create', 'projects') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card-v v3">
                <div class="card-v-body">
                    <div class="stats">
                        <div class="stats-info">
                            <div class="stats-meta">
                                <h3 class="stats-title">{{ lang('Projects', 'projects') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $project)
                                <tr>
                                    <td>{{ $project->title }}</td>
                                    <td class="text-center">
                                        <a type="button" href="{{ route('user.project.edit', $project->id) }}" class="btn btn-primary btn-md me-2 d-inline">
                                            {{ lang('Edit', 'projects') }}
                                        </a>
                                        <form class="d-inline" action="{{ route('user.project.destroy', $project->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-md confirm-action-form">{{ lang('Delete', 'projects') }}</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card-v v3">
                <div class="card-v-body">
                    <div class="stats">
                        <div class="stats-info">
                            <div class="stats-meta">
                                <h3 class="stats-title">{{ lang('New Camera', 'projects') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('user.project.create') }}" method="POST">
                            @csrf
                            <div class="form-search w-100">
                                <input type="text" name="camera_name"
                                    class="form-control form-control-md"
                                    placeholder="{{ lang('Camera Name', 'projects') }}"
                                    value="" required>
                                <input type="hidden" name="type" value="camera">
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <button class="btn btn-danger w-100 btn-md">
                                        {{ lang('Create', 'projects') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card-v v3">
                <div class="card-v-body">
                    <div class="stats">
                        <div class="stats-info">
                            <div class="stats-meta">
                                <h3 class="stats-title">{{ lang('Cameras', 'project') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Camera Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cameras as $camera)
                                <tr>
                                    <td>{{ $camera->title }}</td>
                                    <td class="text-center">
                                        <a type="button" href="{{ route('user.project.edit', $camera->id) }}" class="btn btn-primary btn-md d-inline me-2">
                                            {{ lang('Edit', 'projects') }}
                                        </a>
                                        <form class="d-inline" action="{{ route('user.project.destroy', $camera->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-md confirm-action-form">{{ lang('Delete', 'projects') }}</button>
                                        </form>
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
