@php $form_group  = isset($form_group)  ? $form_group     : true
@php $label       = isset($label)       ? $label          : 'Valor'
@php $name        = isset($name)        ? $name           : 'valor'
@php $class_group = isset($class_group) ? $class_group    : ''
@php $class       = isset($class)       ? $class          : ''
@php $value       = isset($value)       ? $value          : ''
@php $id          = isset($id)          ? 'id=' . $id     : ''
@php $required    = isset($required)    ? 'required '     : ''
@php $readonly    = isset($readonly)    ? 'readonly'      : ''
@php $style       = isset($style)       ? 'style='.$style : ''

@php $group_html    = ($form_group == true) ? '<div class="form-group"><label for="valor-frete">' . $label . ':</label>' : ''
@php $endgroup_html = ($form_group == true) ? '</div>' : ''


{!! $group_html !!}
    <div class="input-group left-icon {{ $class_group }} {{ $required }} {{ $readonly }}" {{ $style }}>
        <div class="input-group-addon">
            <span class="fa fa-usd"></span>
        </div>
        <input type="text" name="{{ $name }}" class="form-control input-text-right {{ $class }}" min="0" value="{{ $value }}" {{ $id }} {{ $required }} {{ $readonly }}/>
    </div>
{!! $endgroup_html !!}