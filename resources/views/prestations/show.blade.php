@extends('layouts.app')
@section('title','Show '.ucfirst(config('settings.prestations_label_singular')))

@section('content_header')
    <h1 class="pull-left">
        {{ucfirst(config('settings.prestations_label_singular'))}}
    </h1>
    <h1 class="pull-right">
        @can('create prestations')
            <a class="btn btn-flat"
               href="{!! route('prestations.create') !!}">
                <i class="fas fa-lg fa-plus"></i>
                Nouveau
            </a>
        @endcan
    </h1>
    <div class="clearfix"></div>
@stop

@section('content')
    <div class="content">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tag" data-toggle="tab"
                                      aria-expanded="true">{{ucfirst(config('settings.prestations_label_singular'))}}</a>
                </li>
                @can('user manage permission')
                    <li class=""><a href="#tab_permissions" data-toggle="tab"
                                    aria-expanded="false">Permission</a>
                    </li>
                @endcan
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tag">
                    @include('prestations.show_fields')
                </div>
                @can('user manage permission')
                    <div class="tab-pane" id="tab_permissions">
                        <div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th colspan="3" style="font-size: 1.8rem;">{{ucfirst(config('settings.document_label_plural'))}} permissions in this {{config('settings.tags_label_singular')}}</th>
                                </tr>
                                <tr>
                                    <th>User</th>
                                    <th>Permissions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($tagWisePermList)==0)
                                    <tr>
                                        <td colspan="3">No record found</td>
                                    </tr>
                                @endif
                                @foreach ($tagWisePermList as $perm)
                                    <tr>
                                        <td>{{$perm['user']->name}}</td>
                                        <td>
                                            @foreach ($perm['permissions'] as $p)
                                                <label class="label label-default">{{$p}}</label>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th colspan="3" style="font-size: 1.8rem;">Permission inherited from global {{config('settings.document_label_plural')}}</th>
                                </tr>
                                <tr>
                                    <th>User</th>
                                    <th>Permissions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($globalPermissionUsers)==0)
                                    <tr>
                                        <td colspan="2">No record found</td>
                                    </tr>
                                @endif
                                @foreach ($globalPermissionUsers as $user)
                                    <tr>
                                        <td>{{$user->name}}</td>
                                        <td>
                                            @foreach(config('constants.GLOBAL_PERMISSIONS.DOCUMENTS') as $perm_key=>$value)
                                                @if ($user->can($perm_key))
                                                    <label class="label label-default">{{$value}}</label>
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
@endsection
