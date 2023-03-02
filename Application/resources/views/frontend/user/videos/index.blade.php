@extends('frontend.user.layouts.dash')
@section('title', lang('My Videos', 'videos'))
@section('upload', true)
@section('content')
    @if ($fileEntries->count() > 0)
        <div class="section-body">

            <div class="card-v v3">
                <div class="card-v-body">
                    <div class="col-md-12">
                        <div class="col-md-12">
                        <form class="multiple-select-delete-form d-none" action="{{ route('user.videos.destroy.all') }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="ids" class="multiple-select-delete-ids">
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
                                        <th class="tb-w-20x">{{ lang('Details', 'videos') }}</th>
                                        <th class="tb-w-7x">{{ lang('Project', 'videos') }}</th>
                                        <th class="tb-w-7x">{{ lang('Camera', 'videos') }}</th>
                                        <th class="tb-w-6x">{{ lang('Size', 'videos') }}</th>
                                        <th class="tb-w-3x text-center">{{ lang('Downloads', 'videos') }}</th>
                                        <th class="tb-w-3x text-center">{{ lang('Views', 'videos') }}</th>
                                        <th class="tb-w-7x text-center">{{ lang('Created date', 'videos') }}</th>
                                        <th class="text-end"><i class="fas fa-sliders-h me-1"></i></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($fileEntries as $fileEntry)
                                        <tr>
                                            <td>
                                                <input class="form-check-input multiple-select-checkbox"
                                                    data-id="{{ $fileEntry->shared_id }}" type="checkbox">
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a class="vironeer-content-image text-center me-3"
                                                        href="{{ route('user.videos.edit', $fileEntry->shared_id) }}">
                                                        <img src="{{ fileIcon($fileEntry->extension) }}"
                                                            alt="{{ $fileEntry->name }}">
                                                    </a>
                                                    <div>
                                                        <a class="text-reset"
                                                            href="{{ route('user.videos.edit', $fileEntry->shared_id) }}">{{ shortertext($fileEntry->name, 50) }}</a>
                                                        <p class="text-muted mb-0">
                                                            {{ shortertext($fileEntry->mime, 50) ?? __('Unknown') }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $fileEntry->project->title}}
                                            </td>
                                            <td>
                                                {{ $fileEntry->camera->title}}
                                            </td>
                                            <td>{{ formatBytes($fileEntry->size) }}</td>
                                            <td class="text-center">{{ formatNumber($fileEntry->downloads) }}</td>
                                            <td class="text-center">{{ formatNumber($fileEntry->views) }}</td>
                                            <td class="text-center">{{ vDate($fileEntry->created_at) }}</td>
                                            <td>
                                                <div class="text-end">
                                                    <button type="button" class="btn btn-sm rounded-3" data-bs-toggle="dropdown"
                                                        aria-expanded="true">
                                                        <i class="fa fa-ellipsis-v fa-sm text-muted"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-md-end dropdown-menu-lg"
                                                        data-popper-placement="bottom-end">
                                                        <li>
                                                            @if ($fileEntry->access_status)
                                                                <a href="#" class="dropdown-item fileManager-share-file"
                                                                    data-share='{"filename":"{{ $fileEntry->name }}","share_link":"{{ route('file.view', $fileEntry->shared_id) }}", "embed_link":"{{ route('file.embed', $fileEntry->shared_id) }}"}'>
                                                                    <i class="fa-solid fa-share-from-square"></i>
                                                                    {{ lang('Share', 'videos') }}
                                                                </a>
                                                            @endif
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('file.view', $fileEntry->shared_id) }}" target="_blank"
                                                                class="dropdown-item">
                                                                <i class="fa fa-eye"></i>
                                                                {{ lang('Preview', 'videos') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                        @if (uploadSettings()->upload->allow_downloading)
                                                            <a href="{{ route('user.videos.download', $fileEntry->shared_id) }}"
                                                                class="dropdown-item">
                                                                <i class="fa-solid fa-download"></i>
                                                                {{ lang('Download', 'videos') }}
                                                            </a>
                                                        @endif
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('user.videos.edit', $fileEntry->shared_id) }}"
                                                                class="dropdown-item">
                                                                <i class="fa fa-edit"></i>
                                                                {{ lang('Edit details', 'videos') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider" />
                                                        </li>
                                                        <li>
                                                            <a href="#" class="dropdown-item text-danger delete-file"
                                                                data-id="{{ $fileEntry->shared_id }}">
                                                                <i class="fa-regular fa-trash-can"></i>
                                                                {{ lang('Delete', 'videos') }}
                                                            </a>
                                                            <form id="deleteFile{{ $fileEntry->shared_id }}"
                                                                action="{{ route('user.videos.destroy', $fileEntry->shared_id) }}" method="POST">
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
                            {{ $fileEntries->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div id="shareModal" class="modal fade share-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 pt-1 mb-1">
                        <h5 class="mb-4"><i class="fas fa-share-alt me-2"></i>{{ lang('Share this video', 'videos') }}
                        </h5>
                        <p class="mb-3 text-ellipsis filename"></p>
                        <div class="mb-3">
                            <div class="share v2"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>{{ lang('Share link', 'videos') }}</strong></label>
                            <div class="input-group">
                                <input id="copy-share-link" type="text" class="form-control" value="" readonly>
                                <button type="button" class="btn btn-primary btn-md btn-copy"
                                    data-clipboard-target="#copy-share-link"><i class="far fa-clone"></i></button>
                            </div>
                        </div>
                        <label class="form-label"><strong>{{ lang('Embed code', 'videos') }}</strong></label>
                        <div class="textarea-btn">
                            <textarea id="embedLink" class="form-control" rows="5" readonly></textarea>
                            <button type="button" class="btn btn-primary btn-copy"
                                data-clipboard-target="#embedLink">{{ lang('Copy', 'videos') }}</button>
                        </div>
                    </div>
                </form>
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
