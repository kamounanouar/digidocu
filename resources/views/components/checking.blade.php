
    <label for="rating">Donnez une note (1-5):</label>
    <div> {{-- Wrap radio buttons in a div for better layout --}}
        @for ($i = 1; $i <= 5; $i++)
            <label class="radio-inline">
                <input type="checkbox" name="rating" id="rating_{{ $i }}" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                {{ $i }}
            </label>
        @endfor
    </div>

    {!! $errors->first('rating','<span class="help-block">:message</span>') !!} {{-- Adjust error message name --}}
