@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Quality</h1>
        <form action="{{ route('qualities.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="quality_type_id" class="form-label">Quality Type:</label>
                <select class="form-control @error('quality_type_id') is-invalid @enderror" id="quality_type_id" name="quality_type_id" required>
                    <option value="">Select Quality Type</option>
                    @foreach ($qualityTypes as $qualityType)
                        <option value="{{ $qualityType->id }}" {{ old('quality_type_id') == $qualityType->id ? 'selected' : '' }}>
                            {{ $qualityType->libel }}
                        </option>
                    @endforeach
                </select>
                @error('quality_type_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="position" class="form-label">Position:</label>
                <input type="number" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position') }}">
                @error('position')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="libel" class="form-label">Libel:</label>
                <input type="text" class="form-control @error('libel') is-invalid @enderror" id="libel" name="libel" value="{{ old('libel') }}" required>
                @error('libel')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-success">Create</button>
            <a href="{{ route('qualities.index') }}" class="btn btn-secondary">Back to List</a>
        </form>
    </div>
@endsection