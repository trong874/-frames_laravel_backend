{{-- Aside --}}

@php
    $kt_logo_image = 'logo-default.png';
@endphp

@if (config('layout.brand.self.theme') === 'light')
    @php $kt_logo_image = 'logo-dark.png' @endphp
@elseif (config('layout.brand.self.theme') === 'dark')
    @php $kt_logo_image = 'logo-default.png' @endphp
@endif

<div class="aside aside-left {{ Metronic::printClasses('aside', false) }} d-flex flex-column flex-row-auto"
     id="kt_aside">

    {{-- Brand --}}
    <div class="brand flex-column-auto {{ Metronic::printClasses('brand', false) }}" id="kt_brand">
        <div class="brand-logo">
            <a href="{{ url('/') }}">
{{--                <img alt="{{ config('app.name') }}" src="{{ asset('media/logos/'.$kt_logo_image) }}"/>--}}
                <h3><i class="flaticon2-gear"></i> span</h3>
            </a>
        </div>

        @if (config('layout.aside.self.minimize.toggle'))
            <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
                {{ Metronic::getSVG("media/svg/icons/Navigation/Angle-double-left.svg", "svg-icon-xl") }}
            </button>
        @endif

    </div>
    <div class="container mt-4">
        <div class="input-group input-group-sm mb-3">
            <input type="search" class="form-control" aria-label="Sizing example input"
                   aria-describedby="inputGroup-sizing-sm" placeholder="Tìm kiếm" id="keyword">
        </div>
    </div>
    {{-- Aside menu --}}
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">

        @if (config('layout.aside.self.display') === false)
            <div class="header-logo">
                <a href="{{ url('/') }}">
                    <img alt="{{ config('app.name') }}" src="{{ asset('media/logos/'.$kt_logo_image) }}"/>
                </a>
            </div>
        @endif

        <div id="kt_aside_menu" class="aside-menu my-4"
             data-menu-vertical="1"
            {{ Metronic::printAttrs('aside_menu') }} >
            <ul class="menu-nav {{ Metronic::printClasses('aside_menu_nav', false) }}">
                {{ Menu::renderVerMenu(config('menu_aside.items')) }}
            </ul>
        </div>
        <div id="kt_aside_menu_result_search" class="aside-menu my-4 scroll ps ps--active-y" data-menu-vertical="1"
        {{ Metronic::printAttrs('aside_menu') }}">
        <ul class="menu-nav {{ Metronic::printClasses('aside_menu_nav', false) }}">
            <li class="container" style="display: none" id="empty-result-search">
                <h6 class="text-danger">Không tìm thấy kết quả !</h6>
            </li>
            {{ Menu::renderVerMenu(config('menu_aside.items')) }}
        </ul>
    </div>
</div>
</div>
