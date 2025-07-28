@extends('layouts.app')
@section('title','Show '.ucfirst(config('settings.tags_label_singular')))
@section('content')
<section class="content-header">
        <h1 class="pull-left">
        {{ucfirst(config('settings.Journal_prestations'))}}
        </h1>
        <h1 class="pull-right">
            @can('create prestations')
                <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
                   href="{!! route('prestations.daily.form') !!}">
                    <i class="fa fa-plus"></i>
                    Add New
                </a>
            @endcan
        </h1>

    </section>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Prestations Journal</h3>
                        <h2>Graphique des prestations</h2> 
                    </div> 
                    <canvas id="pivotChart" height="100" class="my-4 w-100 chartjs-render-monitor"></canvas>
                </div>
            </div>
        </div>
    </div>

@php
    $codes = \DB::table('prestations')->pluck('code');
@endphp

<table id="prestationTable" class="table table-bordered">
    <thead>
        <tr>
            <th>Date</th>
            @foreach ($codes as $code)
                <th>{{ $code }}</th>
            @endforeach
        </tr>
    </thead>
</table>


@endsection
