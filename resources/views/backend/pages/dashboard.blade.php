{{-- Extends layout --}}
@extends('backend.layout.default')

{{-- Content --}}
@section('content')

    {{-- Dashboard 1 --}}
    <h2 class="alert-light-info">Dashboard Updating...</h2>

{{--    <div class="row">--}}
{{--        <div class="col-lg-6 col-xxl-4">--}}
{{--            @include('backend.pages.widgets._widget-1', ['class' => 'card-stretch gutter-b'])--}}
{{--        </div>--}}

{{--        <div class="col-lg-6 col-xxl-4">--}}
{{--            @include('backend.pages.widgets._widget-2', ['class' => 'card-stretch gutter-b'])--}}
{{--        </div>--}}

{{--        <div class="col-lg-6 col-xxl-4">--}}
{{--            @include('backend.pages.widgets._widget-3', ['class' => 'card-stretch card-stretch-half gutter-b'])--}}
{{--            @include('backend.pages.widgets._widget-4', ['class' => 'card-stretch card-stretch-half gutter-b'])--}}
{{--        </div>--}}

{{--        <div class="col-lg-6 col-xxl-4 order-1 order-xxl-1">--}}
{{--            @include('backend.pages.widgets._widget-5', ['class' => 'card-stretch gutter-b'])--}}
{{--        </div>--}}

{{--        <div class="col-xxl-8 order-2 order-xxl-1">--}}
{{--            @include('backend.pages.widgets._widget-6', ['class' => 'card-stretch gutter-b'])--}}
{{--        </div>--}}

{{--        <div class="col-lg-6 col-xxl-4 order-1 order-xxl-2">--}}
{{--            @include('backend.pages.widgets._widget-7', ['class' => 'card-stretch gutter-b'])--}}
{{--        </div>--}}

{{--        <div class="col-lg-6 col-xxl-4 order-1 order-xxl-2">--}}
{{--            @include('backend.pages.widgets._widget-8', ['class' => 'card-stretch gutter-b'])--}}
{{--        </div>--}}

{{--        <div class="col-lg-12 col-xxl-4 order-1 order-xxl-2">--}}
{{--            @include('backend.pages.widgets._widget-9', ['class' => 'card-stretch gutter-b'])--}}
{{--        </div>--}}
{{--    </div>--}}

@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script src="{{ asset('js/pages/widgets.js') }}" type="text/javascript"></script>
    @if(Session::has('token_jwt'))
        <script>
            $(document).ready(function () {
                localStorage.setItem('token_jwt',"{{Session::pull('token_jwt')}}")
            })
        </script>
    @endif
@endsection
