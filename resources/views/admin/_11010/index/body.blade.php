
@foreach ( $usuarios as $usuario )
<tr tabindex="0" data-id="{{ $usuario->ID }}" data-usuario="{{ $usuario->USUARIO }}" data-status="{{ trim($usuario->STATUS) }}">
	<td class="t-status status{{ trim($usuario->STATUS) }}"></td>
    <td>{{ lpad($usuario->ID,3,'0') }}</td>
    <td>{{ $usuario->USUARIO }}</td>
    <td>{{ $usuario->NOME }}</td>
    <td>{{ $usuario->EMAIL }}</td>>
</tr>
@endforeach
