@extends('layouts.app')
@section('title','List '.ucfirst(config('settings.quality_label_plural')))
@section('content')
    <section class="content-header">
        <h1 class="pull-left">Qualities</h1>
        <h1 class="pull-right">
            @can('create qualities')
                <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
                   href="{{ route('qualities.create') }}">
                    <i class="fa fa-plus"></i>
                    Add New
                </a>
            @endcan
        </h1>
    </section>
    
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
        <table class="table table-striped table-bordered table-mini dataTable no-footer">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($qualities as $quality)
                    <tr>
                        <td>{{ $quality->id }}</td>
                        <td>{{ $quality->date ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('qualities.show', $quality->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('qualities.edit', $quality->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('qualities.destroy', $quality->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        </div>
        </div>
    </div>
@endsection