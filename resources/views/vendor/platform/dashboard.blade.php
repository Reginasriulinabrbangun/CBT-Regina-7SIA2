{{--
    Override shell Orchid untuk portal siswa (CERDAS CBT).
    Diff minimal dari vendor/orchid/platform/resources/views/dashboard.blade.php:
      - Sidebar: brand + kartu user di bawah brand, tanpa search sidebar, tanpa footer profil.
      - Workspace: topbar (search + notifikasi + avatar) khusus halaman dashboard.
    Jangan lupa: perubahan khusus dashboard WAJIB dijaga request()->routeIs('siswa.dashboard')
    karena file ini adalah shell untuk SEMUA screen Orchid.
--}}
@extends(config('platform.workspace', 'platform::workspace.compact'))

@section('aside')
    <div class="aside siswa-aside col-xs-12 col-lg-3 col-xl-2 bg-dark d-flex flex-column" data-controller="menu" data-bs-theme="dark">
        <header class="p-3 mt-lg-4 w-100 d-flex align-items-center">
            <a href="#" class="header-toggler d-lg-none me-auto order-first d-flex align-items-center lh-1 link-body-emphasis"
               data-action="click->menu#toggle">
                <x-orchid-icon path="bs.three-dots-vertical" class="icon-menu"/>

                <span class="ms-2">@yield('title')</span>
            </a>

            <a class="header-brand order-last link-body-emphasis" href="{{ route(config('platform.index')) }}">
                @includeFirst([config('platform.template.header'), 'platform::header'])
            </a>
        </header>

        @includeWhen(Auth::check(), 'siswa.partials.user-card')

        <nav class="aside-collapse w-100 d-lg-flex flex-column collapse-horizontal text-body-emphasis" id="headerMenuCollapse">

            <ul class="nav nav-pills flex-column mb-md-1 mb-auto ps-0 gap-1">
                {!! Dashboard::renderMenu() !!}
            </ul>

            <div class="h-100 w-100 position-relative to-top cursor d-none d-md-flex mt-md-5"
                 data-action="click->html-load#goToTop"
                 title="{{ __('Scroll to top') }}">
                <div class="bottom-left w-100 mb-2 ps-3 overflow-hidden">
                    <small data-controller="viewport-entrance-toggle"
                           class="scroll-to-top d-flex align-items-center gap-3"
                           data-viewport-entrance-toggle-class="show">
                        <x-orchid-icon path="bs.chevron-up"/>
                        {{ __('Scroll to top') }}
                    </small>
                </div>
            </div>
        </nav>
    </div>
@endsection

@section('workspace')
    @if(Breadcrumbs::has())
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb px-4 mb-2">
                <x-tabuna-breadcrumbs
                    class="breadcrumb-item"
                    active="active"
                />
            </ol>
        </nav>
    @endif

    @if (request()->routeIs('siswa.dashboard'))
        @include('siswa.partials.topbar')
    @else
        <div class="order-last order-md-0 command-bar-wrapper">
            <div class="@hasSection('navbar') @else d-none d-md-block @endif layout d-md-flex align-items-center">
                <header class="d-none d-md-block col-xs-12 col-md p-0 me-3">
                    <h1 class="m-0 fw-light h3 text-body-emphasis">@yield('title')</h1>
                    <small class="text-muted" title="@yield('description')">@yield('description')</small>
                </header>
                <nav class="col-xs-12 col-md-auto ms-md-auto p-0">
                    <ul class="nav command-bar justify-content-sm-end justify-content-start d-flex align-items-center gap-2 flex-wrap-reverse flex-sm-nowrap">
                        @yield('navbar')
                    </ul>
                </nav>
            </div>
        </div>
    @endif

    @include('platform::partials.alert')
    @yield('content')
@endsection
