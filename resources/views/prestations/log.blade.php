<h2>Saisir une prestation du jour</h2>

<form method="POST" action="{{ route('prestations.log.store') }}">
    @csrf
    <label>Date</label>
    <input type="date" name="date" required>

    <label>Prestation</label>
    <select name="prestation_id">
        @foreach($prestations as $prestation)
            <option value="{{ $prestation->id }}">{{ $prestation->code }} - {{ $prestation->label }}</option>
        @endforeach
    </select>

    <label>Quantité</label>
    <input type="number" name="quantite" value="1" min="1">

    <button type="submit">Valider</button>
</form>
