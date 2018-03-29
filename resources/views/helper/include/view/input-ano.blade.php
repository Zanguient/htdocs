@php $label    = isset($label) ? $label : 'Ano'
@php $name     = isset($name) ? $name : 'ano'
@php $class    = isset($class)    ? $class : ''
@php $id       = isset($id)       ? 'id=' . $id : ''
@php $required = isset($required) ? 'required ' : ''
@php $disabled = isset($disabled) ? 'disabled' : ''
@php $selected = isset($selected) ? (($selected == 'now') ? date('Y') : $selected) : ''
@php $year_min = isset($year_min) ? $year_min : 2000
@php $year_max = isset($year_max) ? $year_min : date('Y')+10

<div class="form-group">
    <label>{{ $label }}:</label>
    <select name="{{ $name }}" class="form-control {{ $class }}" {{ $id }} {{ $required }} {{ $disabled }} >
        <option disabled>Ano</option>
        @for ($i = $year_min; $i < $year_max; $i++)
        <option value="{{ $i }}" {{ $selected == $i ? 'selected' : ''}}>{{ $i }}</option>
        @endfor
    </select>
</div>