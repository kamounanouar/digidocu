@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => old($name),
])

<div class="form-group col-sm-6 {{ $errors->has($name) ? 'has-error' : '' }}">
    {!! Form::label($name, $label ?? ucfirst($name) . ':') !!}

    @if ($type === 'textarea')
        {!! Form::textarea($name, $value, array_merge(['class' => 'form-control'], $attributes->toArray())) !!}
    @elseif ($type === 'select')
        {!! Form::select($name, $options ?? [], $value, array_merge(['class' => 'form-control'], $attributes->toArray())) !!}
    @else
        {!! Form::$type($name, $value, array_merge(['class' => 'form-control'], $attributes->toArray())) !!}
    @endif

    {!! $errors->first($name, '<span class="help-block">:message</span>') !!}
</div>
