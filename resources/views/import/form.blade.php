@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Importer un fichier Excel / CSV</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Choisir un fichier</label>
            <input type="file" name="file" class="form-control" required>
        </div>
        <button class="btn btn-primary mt-3">Importer</button>
    </form>
</div>
@endsection
