@extends('layouts.app')
@section('title',"Add ".ucfirst(config('settings.document_label_singular')))
@section('content_header')
    <h1>
        {{ucfirst(config('settings.document_label_singular'))}}
    </h1>
@stop

@section('content')

    <div class="content">
        <div class="card card-primary">

            <div class="card-body">
                <div class="row">
                    {!! Form::open(['route' => 'documents.store']) !!}
                        @include('documents.fields',['document'=>null])
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
