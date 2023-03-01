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
                                <h3 class="stats-title">{{ $projectOrCamera->type == 'project' ? lang('Edit Project', 'projects') : lang('Edit Camera', 'projects') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('user.project.update', $projectOrCamera->id) }}" method="POST">
                            @csrf
                            <div class="form-search w-100">
                                <input type="text" name="title"
                                    class="form-control form-control-md"
                                    placeholder="{{ $projectOrCamera->type == 'project' ? lang('Project Name', 'projects') : lang('Camera Name', 'projects') }}"
                                    value="{{ $projectOrCamera->title }}" required>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <button class="btn btn-danger w-100 btn-md">
                                        {{ lang('Update', 'projects') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection
