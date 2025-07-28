@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Importer un fichier Excel</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                    @endif

                    <form action="{{ route('import.preview') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Sélectionnez un fichier Excel</label>
                            <input type="file" class="form-control-file" id="file" name="file" required>
                            <small class="form-text text-muted">
                                Formats acceptés: .xlsx, .xls (max 20MB)
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-eye"></i> Prévisualiser
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection