@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Prévisualisation avant importation</div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Fichier chargé:</strong> {{ basename($filePath) }}<br>
                        <strong>Nombre d'enregistrements:</strong> {{ count($data) }}
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    @foreach($headers as $header)
                                        <th>{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $row)
                                    <tr>
                                        @foreach($row as $value)
                                            <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <form action="{{ route('import.execute') }}" method="POST">
                            @csrf
                            <input type="hidden" name="filePath" value="{{ $filePath }}">
                            <input type="hidden" name="confirmed" value="1">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Confirmer l'importation
                            </button>
                            <a href="{{ route('import.form') }}" class="btn btn-danger">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection