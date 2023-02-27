@extends('backend.layouts.grid')
@section('section', __('Users uploads'))
@section('title', $fileEntry->name)
@section('back', route('admin.uploads.users.index'))
@section('content')
    <div class="card custom-card mb-3">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <a href="{{ route('admin.users.edit', $fileEntry->user->id) }}">
                        <img class="border rounded-circle border-2" src="{{ asset($fileEntry->user->avatar) }}" width="60"
                            height="60">
                    </a>
                </div>
                <div class="flex-grow-1 ms-3">
                    <a href="{{ route('admin.users.edit', $fileEntry->user->id) }}" class="text-dark">
                        <h5 class="mb-1">
                            {{ $fileEntry->user->firstname . ' ' . $fileEntry->user->lastname }}
                        </h5>
                        <p class="mb-0 text-muted">{{ $fileEntry->user->email }}</p>
                    </a>
                </div>
                <div class="flex-grow-3 ms-3">
                    <a href="{{ route('admin.users.edit', $fileEntry->user->id) }}" class="btn btn-blue" target="_blank"><i
                            class="fa fa-eye me-2"></i>{{ __('View details') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body text-center">
                    <div class="file d-flex h-100 justify-content-center align-items-center">
                        <iframe src="{{ route('admin.uploads.embed', $fileEntry->shared_id) }}" name="test" width="100%"
                            height="100%" id="video_iframe"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <ul class="custom-list-group list-group list-group-flush">
                    <li class="list-group-item">
                        @if (!$fileEntry->access_status)
                            <div class="alert alert-danger text-center">
                                <i
                                    class="fas fa-exclamation-circle me-2"></i>{{ __("Video has private access it can't be previewed") }}
                            </div>
                        @endif
                        <a href="{{ route('admin.uploads.users.download', $fileEntry->shared_id) }}"
                            class="btn btn-primary btn-lg w-100 mb-3"><i
                                class="fas fa-download me-2"></i>{{ __('Download') }}</a>
                        @if($fileEntry->scan_status !== 1)
                        <button
                            class="btn btn-primary btn-lg w-100 mb-3 view-scan-button" data-id="{{ $fileEntry->id }}"><i
                                class="fas fa-search me-2"></i>{{ __('Scan') }}</button>
                        @endif
                        <form action="{{ route('admin.uploads.users.destroy', $fileEntry->shared_id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="vironeer-able-to-delete btn btn-danger btn-lg w-100"><i
                                    class="far fa-trash-alt me-2"></i>{{ __('Delete') }}</button>
                        </form>
                    </li>
                    <li class="list-group-item d-flex justify-content-between original_path" style="cursor: pointer" data-url="{{ $fileEntry->link }}">
                        <strong>{{ __('Name') }}</strong>
                        <span>{{ shortertext($fileEntry->name, 30) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ __('Shared id') }}</strong>
                        <span>{{ $fileEntry->shared_id }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ __('Type') }}</strong>
                        <span>{{ shortertext($fileEntry->mime, 30) ?? __('Unknown') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ __('Size') }}</strong>
                        <span>{{ formatBytes($fileEntry->size) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ __('Storage') }}</strong>
                        <span>
                            @if ($fileEntry->storageProvider->symbol == 'local')
                                <span><i class="fas fa-server me-2"></i>{{ $fileEntry->storageProvider->symbol }}</span>
                            @else
                                <a class="text-dark capitalize"
                                    href="{{ route('admin.settings.storage.edit', $fileEntry->storageProvider->id) }}">
                                    <i class="fas fa-server me-2"></i>{{ $fileEntry->storageProvider->symbol }}
                                </a>
                            @endif
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ __('Upload date') }}</strong>
                        <span>{{ vDate($fileEntry->created_at) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ __('Expiry date') }}</strong>
                        <span>{{ $fileEntry->expiry_at ? vDate($fileEntry->expiry_at) : __('Unlimited time') }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ __('Downloads') }}</strong>
                        <span>{{ formatNumber($fileEntry->downloads) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ __('Views') }}</strong>
                        <span>{{ formatNumber($fileEntry->views) }}</span>
                    </li>
                    <div class="append">
                    @if($fileEntry->scan_status == 1)
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ __('Status') }}</strong>
                        <span class="badge bg-success">Success</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>{{ __('Duration') }}</strong>
                        <span >{{ $fileEntry->duration }}</span>
                    </li>
                    <li class="list-group-item justify-content-between">
                        <p class="m-0"><strong>{{ __('Fetch Uploaded S3 Path') }}</strong></p>
                        <p class="m-0"><span>{{ $fileEntry->fetch_uploaded_s3_path }}</span></p>
                    </li>
                    <li class="list-group-item justify-content-between">
                        <p class="m-0"><strong>{{ __('CSV File Path') }}</strong></p>
                        <p class="m-0"><span>{{ $fileEntry->csv_file_path }}</span></p>
                    </li>
                    <li class="list-group-item justify-content-between">
                        <p class="m-0"><strong>{{ __('JSON File 1 Path') }}</strong></p>
                        <p class="m-0"><span>{{ $fileEntry->json_file1_path }}</span></p>
                    </li>
                    <li class="list-group-item justify-content-between">
                        <p class="m-0"><strong>{{ __('JSON File 2 Path') }}</strong></p>
                        <p class="m-0"><span>{{ $fileEntry->json_file2_path }}</span></p>
                    </li>
                    <li class="list-group-item justify-content-between processed_video_s3_path" style="cursor: pointer" data-url="{{ $fileEntry->processed_video_s3_file_path }}">
                        <p class="m-0"><strong>{{ __('Processed Video S3 Path') }}</strong></p>
                        <p class="m-0"><span>{{ $fileEntry->processed_video_s3_path }}</span></p>
                    </li>
                    @endif
                    </div>
                </ul>
            </div>
        </div>
    </div>
@endsection
