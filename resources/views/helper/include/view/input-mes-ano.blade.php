@php $meses = array(1 => ['01','Janeiro'],['02','Fevereiro'],['03','Março'],['04','Abril'],['05','Maio'],['06','Junho'],['07','Julho'],['08','Agosto'],['09','Setembro'],['10','Outubro'],['11','Novembro'],['12','Dezembro'])

@php $form_group    = isset($form_group)    ? $form_group : true
@php $label         = isset($label)         ? $label : 'Período'
@php $required      = isset($required)      ? 'required ' : ''
@php $disabled      = isset($disabled)      ? 'disabled' : ''

@php $mes_name      = isset($mes_name)      ? $mes_name   : 'mes'
@php $mes_class     = isset($mes_class)     ? $mes_class  : ''
@php $mes_id        = isset($id)            ? 'id=' . $id : ''
@php $mes_selected  = isset($mes_selected)  ? (($mes_selected == 'now') ? date('n') : $mes_selected) : ''
@php $mes_style     = isset($mes_style)     ? 'style=' . $mes_style : ''

@php $ano_name      = isset($ano_name)      ? $ano_name       : 'ano'
@php $ano_class     = isset($ano_class)     ? $ano_class      : ''
@php $ano_id        = isset($ano_id)        ? 'id=' . $ano_id : ''
@php $ano_selected  = isset($ano_selected)  ? (($ano_selected == 'now') ? date('Y') : $ano_selected) : ''
@php $ano_min       = isset($ano_min)       ? $ano_min        : 2000
@php $ano_max       = isset($ano_max)       ? $ano_max        : date('Y')+10
@php $ano_style     = isset($ano_style)     ? 'style=' . $ano_style : ''

@php $group_html    = ($form_group == true) ? '<div class="form-group"><label for="data-utilizacao">'. $label .':</label>' : ''
@php $endgroup_html = ($form_group == true) ? '</div>' : ''

{!! $group_html !!}
    <select name="{{ $mes_name }}" class="form-control {{ $mes_class }}" {{ $mes_style }}  {{ $mes_id }} {{ $required }} {{ $disabled }}>
        <option disabled>Mês</option>
        @for ($i = 1; $i < 13; $i++)
         <option value="{{ $i }}" {{ $mes_selected == $i ? 'selected' : ''}}>{{ $meses[$i][1] }}</option>
        @endfor
    </select>
    <select name="{{ $ano_name }}" class="form-control {{ $ano_class }}" {{ $ano_style }} {{ $ano_id }} {{ $required }} {{ $disabled }}>
        <option disabled>Ano</option>
        @for ($i = $ano_min; $i < $ano_max; $i++)
        <option value="{{ $i }}" {{ $ano_selected == $i ? 'selected' : ''}}>{{ $i }}</option>
        @endfor
    </select>
{!! $endgroup_html !!}