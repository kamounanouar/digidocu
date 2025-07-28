@extends('layouts.app') {{-- Assurez-vous d'avoir un fichier de layout, idéalement avec Bootstrap et Alpine.js --}}

@section('content')
    <div class="container" x-data="{ globalDate: '{{ old('global_date', date('Y-m-d')) }}' }">
        <h1 class="mb-4">Saisie des Logs Qualité</h1>

        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- Affichage global des erreurs de validation (important si la validation de date échoue) --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <form action="{{ route('qualities.store') }}" method="POST">
        {{-- Champ de date global --}}
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h2 class="h5 mb-0">Date des Logs</h2>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="global_date" class="form-label">Sélectionnez la date pour tous les logs :</label>
                    <input type="date"
                    name="date"
                           class="form-control form-control-lg @error('date') is-invalid @enderror"
                           id="date"
                           value="{{ date('Y-m-d') }}"
                           required>
                    @error('date') {{-- L'erreur de validation pour 'date' sera affichée ici car tous les logs partagent la même date --}}
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <p class="text-muted">Cette date sera appliquée à tous les logs qualité enregistrés ci-dessous.</p>
                <div class="col-12 text-end"> {{-- Alignez le bouton à droite --}}
                                        <button type="submit" class="btn btn-success btn-sm">Enregistrer ce Log</button>
                                    </div>
            </div>
        </div>
        
        @forelse($qualityT1s as $t1)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0">{{ $t1->libel }}</h2>
                </div>
                <div class="card-body">
                @forelse($t1->qualityT2s as $t2)
                        <div class="border p-3 mb-3 rounded shadow-sm">
                                @csrf
                                <input type="hidden" name="logs[{{ $t2->id }}][quality_t2_id]" value="{{ $t2->id }}">
                                <div class="row g-3">
                                    {{-- Le champ de date n'est plus visible ici --}}
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input  class="form-check-input" type="checkbox" 
                                                    role="switch" id="status_{{ $t2->id }}" 
                                                    name="logs[{{ $t2->id }}][status]"
                                                    value="1" {{ old("logs.{$t2->id}.status") ? 'checked' : '' }}
                                                    >
                                            <label class="form-check-label" for="status_{{ $t2->id }}"><h1 class="h4 mb-3">{{ $t2->libel }}</h1></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="comment[{{ $t2->id }}]" class="form-label">Commentaire:</label>
                                        <textarea name="logs[{{ $t2->id }}][comment]" 
                              id="comment_{{ $t2->id }}" class="form-control b-wysihtml5-editor" rows="2">{{ old("logs.{$t2->id}.comment") }}</textarea>
                                    </div>
                                    
                                </div>
                          
                        </div>
                    @empty
                        <p class="text-muted">Aucune qualité disponible pour ce type de qualité.</p>
                    @endforelse
                    
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                Aucun type de qualité trouvé. Veuillez en créer un d'abord.
            </div>
        @endforelse
        </form>
        <div class="mt-4">
            <a href="{{ route('qualities.index') }}" class="btn btn-secondary">Retour à la Liste des Logs</a>
        </div>
    </div>
@endsection