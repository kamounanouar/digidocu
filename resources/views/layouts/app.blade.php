@extends('adminlte::page')

{{-- Extend and customize the browser title --}}

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- Extend and customize the page content header --}}

    @section('content_header')
    
        @hasSection('content_header_title')
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    @yield('content_header_title')
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                @hasSection('content_header_subtitle')
                    <small class="text-dark">
                        <i class="fas fa-xs fa-angle-right text-muted"></i>
                        @yield('content_header_subtitle')
                    </small>
                @endif
                </ol>   
            </div>
        </div>
        @endif
    @stop
{{-- Rename section content to content_body --}}

@section('content')
<div class="content-fluid">  

                    @include('flash::message')
                    @yield('content_body')
   
</div>
@stop
{{-- Create a common footer --}}

@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', 'SIAB PLC') }}
        </a>
    </strong>
@stop

{{-- Add common Javascript/Jquery code --}}

@push('js')

<script src="{{asset('vendor/bootstrap-typeahead/js/bootstrap3-typeahead.min.js')}}"></script>
<script src="{{asset('vendor/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js')}}"></script>
<script src="{{asset('vendor/bootstrap-wysihtml5/js/bootstrap3-wysihtml5.all.min.js')}}"></script>
<script src="{{asset('js/handlebar-helpers.js')}}"></script>
<script src="{{asset('js/digidocu-custom.js')}}"></script>
@endpush

{{-- Add common CSS customizations --}}

@push('css')
<style type="text/css">

    {{-- You can add AdminLTE customizations here --}}
    /*
    .card-header {
        border-bottom: none;
    }
    .card-title {
        font-weight: 600;
    }
    */

</style>
@endpush