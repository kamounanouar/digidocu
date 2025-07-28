@extends('layouts.app')

@section('content')
    <h2>Saisie des prestations journalières</h2>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
    <form method="POST" action="{{ route('prestations.daily.store') }}">
        @csrf

        <label for="date">Date :</label>
        <input type="date" name="date" required value="{{ date('Y-m-d') }}">
        <br><br>
<div class="form-group col-sm-6" >

        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Libellé</th>
                    <th>Quantité</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prestations as $prestation)
                    <tr>
                        <td>{{ $prestation->code }}</td>
                        <td>{{ $prestation->label }}</td>
                        <td>
                            <input type="number" name="quantite[{{ $prestation->id }}]" min="0" value="0">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>
        <button type="submit">Enregistrer les prestations</button>
        </div>
    </form>
    </div></div></div>
@endsection
