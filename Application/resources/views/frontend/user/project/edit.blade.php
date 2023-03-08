@extends('frontend.user.layouts.dash')
@section('title', lang('New Projects', 'projects'))
@section('content')
    <div class="col">
        <div class="card-v v3">
            <div class="card-v-body">
                <form action="{{ route('user.projects.create') }}" method="POST">
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
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/clipboard/clipboard.min.js') }}"></script>
    @endpush
@endsection
