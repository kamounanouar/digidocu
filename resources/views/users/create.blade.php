@extends('layouts.app')
@section('title','New User')


@section('content_header_title', 'User')
@section('content_header_subtitle', 'New User')



@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create User</h3>
                </div>
                <div class="card-body">
                    <Form class="form-horizontal" role="form" method="POST" action="{{ route('users.store') }}">
                        {{ csrf_field() }}
                        <div class="box box-primary">
                            <div class="box-body">
                                @include('flash::message')
                                @include('users.fields')
                            </div>
                            <div class="box-footer">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                                <a href="{{ route('users.index') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </div>
@endsection
