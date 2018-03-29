@php $meses = array(1 => ['01','Janeiro'],['02','Fevereiro'],['03','Março'],['04','Abril'],['05','Maio'],['06','Junho'],['07','Julho'],['08','Agosto'],['09','Setembro'],['10','Outubro'],['11','Novembro'],['12','Dezembro'])

@php $label    = isset($label) ? $label : 'Mês'
@php $name     = isset($name) ? $name : 'mes'
@php $class    = isset($class)    ? $class : ''
@php $id       = isset($id)       ? 'id=' . $id : ''
@php $required = isset($required) ? 'required ' : ''
@php $disabled = isset($disabled) ? 'disabled' : ''
@php $selected = isset($selected) ? (($selected == 'now') ? date('n') : $selected) : ''

<div class="form-group">
    <label>{{ $label }}:</label>
    <select name="{{ $name }}" class="form-control {{ $class }}" {{ $id }} {{ $required }} {{ $disabled }} >
        <option disabled>Mês</option>
        @for ($i = 1; $i < 13; $i++)
        <option value="{{ $i }}" {{ $selected == $i ? 'selected' : ''}}>{{ $meses[$i][1] }}</option>
        @endfor
    </select>
</div>