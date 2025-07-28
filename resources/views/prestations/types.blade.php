@extends('layouts.app')
@section('title','List '.ucfirst(config('settings.tags_label_plural')))





@section('content')
<h2>Ajouter une prestation</h2>

<form method="POST" action="{{ route('prestations.store') }}">
    @csrf
    <label>Code</label>
    <input type="text" name="code" required>

    <label>Libellé</label>
    <input type="text" name="label" required>

    <button type="submit">Enregistrer</button>
</form>

<hr>

<h2>Liste des prestations</h2>

<table border="1">
    <tr>
        <th>Code</th>
        <th>Libellé</th>
    </tr>
    @foreach($prestations as $prestation)
        <tr>
            <td>{{ $prestation->code }}</td>
            <td>{{ $prestation->label }}</td>
        </tr>
    @endforeach
</table>
@endsection