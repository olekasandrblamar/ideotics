<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        @if(isset($breadCrumbType))
            @if($breadCrumbType == 'project')
                <li class="breadcrumb-item">
                    <a href="{{ route('user') }}">{{ lang('User', 'breadcrumb') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('user.videos.index') }}">{{ lang('Videos', 'breadcrumb') }}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ $breadCrumbProject->title }}
                </li>
            @endif
            @if($breadCrumbType == 'camera')
                <li class="breadcrumb-item">
                    <a href="{{ route('user') }}">{{ lang('User', 'breadcrumb') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('user.videos.index') }}">{{ lang('Videos', 'breadcrumb') }}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('user.videos.cameras.index', ['project_id' => $breadCrumbProject->id]) }}">{{ $breadCrumbProject->title }}</a>
                </li>
                <li class="breadcrumb-item active">
                    {{ $breadCrumbCamera->title }}
                </li>
            @endif
        @else
            <?php $segments = ''; ?>
            @foreach (request()->segments() as $segment)
                <?php $segments .= '/' . $segment; ?>
                @if ($segment != getLang())
                    <li class="breadcrumb-item  @if (request()->segment(count(request()->segments())) == $segment) active @endif">
                        @if (request()->segment(count(request()->segments())) != $segment)
                            <a href="{{ url($segments) }}">{{ $segment }}</a>
                        @else
                            {{ $segment }}
                        @endif
                    </li>
                @endif
            @endforeach
        @endif
    </ol>
</nav>
