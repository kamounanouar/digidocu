@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Quality Log</h1>

    <form method="POST" action="{{ route('qualities.update', $quality->id) }}">
        @csrf
        @method('PUT')

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0">General Information</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" id="date" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   value="{{ old('date', $quality->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="comment" class="form-label">General Comment</label>
                    <textarea name="comment" id="comment" 
                             class="form-control @error('comment') is-invalid @enderror" 
                             rows="3">{{ old('comment', $quality->comment) }}</textarea>
                    @error('comment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <h3 class="mb-3">Quality Checks</h3>

        @foreach($qualityT1s as $t1)
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h4 class="h6 mb-0">{{ $t1->libel }}</h4>
            </div>
            <div class="card-body">
                @foreach($t1->qualityT2s as $t2)
                @php
                    $log = $existingLogs[$t2->id] ?? null;
                @endphp
                <div class="mb-3 border-bottom pb-3">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" 
                               name="logs[{{ $t2->id }}][status]" 
                               id="status_{{ $t2->id }}" value="1"
                               {{ (old("logs.$t2->id.status", $log?->status) ? 'checked' : '') }}>
                        <label class="form-check-label fw-bold" for="status_{{ $t2->id }}">
                            {{ $t2->libel }}
                        </label>
                    </div>
                    
                    <input type="hidden" name="logs[{{ $t2->id }}][quality_t2_id]" 
                           value="{{ $t2->id }}">

                    <div class="mb-2">
                        <label for="comment_{{ $t2->id }}" class="form-label">Comment</label>
                        <textarea name="logs[{{ $t2->id }}][comment]" 
                                  id="comment_{{ $t2->id }}" 
                                  class="form-control @error("logs.$t2->id.comment") is-invalid @enderror" 
                                  rows="2">{{ old("logs.$t2->id.comment", $log?->comment) }}</textarea>
                        @error("logs.$t2->id.comment")
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('qualities.show', $quality->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection