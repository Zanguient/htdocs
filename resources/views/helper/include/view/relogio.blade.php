<div id="relogio" class="a_resizable">
	<span id="data">{{ date("d/m/Y") }}</span>
	<span id="hora">{{ date("H:i:s") }}</span>
</div>
<input type="hidden" id="_hora-servidor" value="{{ date("Y-m-d H:i:s") }}" />