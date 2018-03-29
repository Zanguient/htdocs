@extends('helper.include.view.modal', ['id' => 'modal-balanca'])

@section('modal-header-left')

	<h4 class="modal-title">
		Capturando peso...
	</h4>

@overwrite

@section('modal-header-right')

	<div id="botoes-baixar">
		<button type="button" class="btn btn-warning" id="baixar-parcial" data-hotkey="alt+p">
			<span class="glyphicon glyphicon-download"></span>
			Baixar Parcial
		</button>
		<button type="button" class="btn btn-success" id="baixar-total" data-hotkey="alt+t">
			<span class="glyphicon glyphicon-circle-arrow-down"></span>
			Baixar Total
		</button>
		<button type="button" class="btn btn-danger btn-cancelar" data-dismiss="modal" data-hotkey="f11">
			<span class="glyphicon glyphicon-ban-circle"></span>
			{{ Lang::get('master.cancelar') }}
		</button>
	</div>

@overwrite

@section('modal-body')
<form class="form-inline">
	<div class="row">
		<div class="form-group">
			<label for="balanca-produto">Produto:</label>
			<input type="text" class="form-control" id="balanca-produto" readonly/>
		</div>
	</div>
	<div class="row">
		<div class="form-group">
			<label for="balanca-saldo-inicial">Peso Líquido Inicial:</label>
			<div class="input-group">
				<input type="text" class="form-control" id="balanca-saldo-inicial" readonly />
				<div class="input-group-addon um"></div>
				<input type="hidden" name="_balanca_saldo_inicial" id="_balanca-saldo-inicial" />
			</div>
		</div>
		<div class="form-group">
			<label for="balanca-tara">Tara:</label>
			<div class="input-group">
				<input type="text" class="form-control" id="balanca-tara" readonly />
				<div class="input-group-addon um"></div>
				<input type="hidden" name="_balanca_tara" id="_balanca-tara" />
			</div>
		</div>
		<div class="form-group">
			<label for="balanca-rendimento">Rendimento:</label>
			<div class="input-group">
				<input type="text" class="form-control" id="balanca-rendimento" readonly />
				<div class="input-group-addon um-altern"></div>
				<input type="hidden" name="_balanca_rendimento" id="_balanca-rendimento" />
			</div>
		</div>
	</div>
	<div class="row">
		<div class="form-group">
			<label for="balanca-peso-bruto">Peso Bruto Atual:</label>
			<div class="input-group">
				<input type="text" class="form-control peso-bruto" id="balanca-peso-bruto" readonly />
				<div class="input-group-addon um"></div>
				<input type="hidden" name="_balanca_peso_bruto" class="gc-print-recebe-peso" id="_balanca-peso-bruto" />
			</div>
		</div>
		<div class="form-group">
			<label for="balanca-saldo-final">Peso Líquido Final:</label>
			<div class="input-group">
				<input type="text" class="form-control" id="balanca-saldo-final" readonly />
				<div class="input-group-addon um"></div>
				<input type="hidden" name="_balanca_saldo_final" id="_balanca-saldo-final" />
			</div>
		</div>
		<div class="form-group">
			<label for="balanca-metragem-calculada">Metragem Calculada:</label>
			<div class="input-group">
				<input type="text" class="form-control" id="balanca-metragem-calculada" readonly />
				<div class="input-group-addon um-altern"></div>
				<input type="hidden" name="_balanca_metragem_calculada" id="_balanca-metragem-calculada" />
			</div>
		</div>
	</div>
	<div class="row">		
		<div class="form-group">
            <label for="balanca-metragem-projetada">Peso à Produzir <span>Lado 1</span>:</label>
			<div class="input-group">
				<input type="text" class="form-control" id="balanca-metragem-projetada" readonly />
				<div class="input-group-addon um"></div>
				<input type="hidden" name="_balanca_metragem_projetada" id="_balanca-metragem-projetada" />
			</div>
		</div>
		<div class="form-group conjunto-2">
			<label for="balanca-metragem-projetada-2">Peso à Produzir Lado 2:</label>
			<div class="input-group">
				<input type="text" class="form-control" id="balanca-metragem-projetada-2" readonly />
				<div class="input-group-addon um"></div>
				<input type="hidden" name="_balanca_metragem_projetada_2" id="_balanca-metragem-projetada-2" />
			</div>
		</div>
		<div class="form-group">
			<label for="balanca-metragem-projetada-altern">Metragem à Produzir <span>Lado 1</span>:</label>
			<div class="input-group">
				<input type="text" class="form-control" id="balanca-metragem-projetada-altern" readonly />
				<div class="input-group-addon um-altern"></div>
				<input type="hidden" name="_balanca_metragem_projetada_altern" id="_balanca-metragem-projetada-altern" />
			</div>
		</div>
		<div class="form-group conjunto-2">
			<label for="balanca-metragem-projetada-altern-2">Metragem à Produzir Lado 2:</label>
			<div class="input-group">
				<input type="text" class="form-control" id="balanca-metragem-projetada-altern-2" readonly />
				<div class="input-group-addon um-altern-2"></div>
				<input type="hidden" name="_balanca_metragem_projetada_altern_2" id="_balanca-metragem-projetada-altern-2" />
			</div>
		</div>
	</div>
	<div class="row">
		<div class="form-group peso-baixar">
			<label for="balanca-peso-baixar">Peso à Baixar:</label>
			<div class="input-group">
				<input type="text" class="form-control" id="balanca-peso-baixar" readonly />
				<div class="input-group-addon um"></div>
				<input type="hidden" name="_balanca_peso_baixar" id="_balanca-peso-baixar" />
			</div>
		</div>
		<div class="form-group">
			<label for="balanca-metragem-baixar">Metragem à Baixar <span>Lado 1</span>:</label>
			<div class="input-group">
				<input type="number" class="form-control" id="balanca-metragem-baixar" min="0" autofocus />
				<div class="input-group-addon um-altern"></div>
				<input type="hidden" name="_balanca_metragem_baixar" id="_balanca-metragem-baixar" />
			</div>
		</div>
		<div class="form-group conjunto-2">
			<label for="balanca-metragem-baixar-2">Metragem à Baixar Lado 2:</label>
			<div class="input-group">
				<input type="number" class="form-control" id="balanca-metragem-baixar-2" min="0" autofocus />
				<div class="input-group-addon um-altern-2"></div>
				<input type="hidden" name="_balanca_metragem_baixar_2" id="_balanca-metragem-baixar-2" />
				<input type="hidden" name="_remessa_talao_detalhe_id_2" id="_remessa-talao-detalhe-id-2" />
			</div>
		</div>
	</div>
</form>
@overwrite