@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">{{ucfirst(config('settings.Journal_prestations'))}}</h1>
        <h1 class="pull-right">
            @can('create prestations')
                <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
                   href="{!! route('prestations.journalier') !!}">
                    <i class="fa fa-plus"></i>
                    Add New
                </a>
            @endcan
            @if(auth()->user()->is_super_admin)
                <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
                   href="{!! route('prestations.journalier') !!}">
                    <i class="fa fa-plus"></i>
                    Add New
                </a>
            @endif
        </h1>
    </section>


    <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
    @forelse ($logs as $date => $entries)
        <h4 style="margin-top: 20px">{{ $date }}</h4>
        <table border="1" cellpadding="5" cellspacing="0" class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Libellé</th>
                    <th>Quantité</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($entries as $log)
                    <tr>
                        <td>{{ $log->prestation->code }}</td>
                        <td>{{ $log->prestation->label }}</td>
                        <td>{{ $log->quantite }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <p>Aucune prestation enregistrée.</p>
    @endforelse

</div>
</div>
</div>
@endsection
