@extends('master')

@section('titulo')
    {{ Lang::get('ppcp/_22130.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/22130.css') }}" />
@endsection

@section('conteudo')

	<input type="hidden" name="_socket_token" class="_socket_token" value="0">

	<div class="conteiner-tela" ng-controller="Ctrl as vm" ng-cloak>
	
		@include('ppcp._22130.include.tela_descanso')

		<button type="button" class="btn btn-xs btn-default btn-toggle-filter" id="filtrar-toggle" data-toggle="collapse" data-target="#programacao-filtro" aria-expanded="true" aria-controls="programacao-filtro">
			Filtro<span class="caret"></span>
		</button>
		
		<div id="programacao-filtro" class="table-filter collapse in" aria-expanded="true">

			@include('ppcp._22130.index.filtro')

		</div>

	<table class="tg" style="table-layout: fixed; overflow: hidden;">
	<colgroup>
		<col style="width: 7.066vw">
		<col style="width: 6vw">
		<col style="width: 3px">
		<col style="width: 6.533vw">
		<col style="width: 6.533vw">
		<col style="width: 6.533vw">
		<col style="width: 3px">
		<col style="width: 6.533vw">
		<col style="width: 6.533vw">
		<col style="width: 6.533vw">
	</colgroup>
	  <tr>
	    <th class="descfab" colspan="2" rowspan="2">
				<span class="valor" ng-if="vm.OPERADOR.LOGADO == true">
					@{{vm.OPERADOR.OPERADOR_NOME}}
					<button ng-click="vm.Acoes.LogOff()" type="button" class="btn  btn-temp btn-warning" id="finalizar">
						<span class="glyphicon glyphicon-user"></span> logOff
					</button>
				</span>
				<span class="valor" ng-if="vm.OPERADOR.LOGADO == false">
					<button ng-click="vm.Acoes.modalLogin()" type="button" class="btn  btn-temp btn-warning" id="finalizar">
						<span class="glyphicon glyphicon-user"></span> Login
					</button>
				</span>
				<input type="hidden" name="_operador_id" id="_operador-id" autocomplete="off">

	    </th>
	    <th class="tg-fqys" rowspan="3"></th>

	    <th class="tg-1sci" colspan="7">Acompanhamento de desempenho Conformação
			
			<button type="submit" class="btn btn-primary btn-atualizat-tela" >
				<span class="glyphicon glyphicon-refresh"></span>
			</button>

			<button type="submit" class="btn btn-primary" id="abrir-resumo">
				<span class="glyphicon glyphicon-list-alt"></span>
			</button>

	    </th>
	  </tr>
	  <tr>
	    <td class="tg-1sci" colspan="3">
			<form name="myForm" style="margin-top:0px">
			  <label>
			    <input type="radio" ng-model="vm.FILTRO.TURNO" ng-value="1">
			    Diurno
			  </label>
			  <label>
			    <input type="radio" ng-model="vm.FILTRO.TURNO" ng-value="2">
			    Noturno
			  </label>
			 </form>

	    </td>
	    <td class="tg-fqys" rowspan="2"></td>
	    <td class="tg-1sci" colspan="3">
			<form name="myForm" style="margin-top:0px">
			  <label  title="Eficiência baseada no tempo previsto">
			    <input type="radio" ng-model="vm.TIPO_EFICIENCIA" ng-value="0">
			    Eficácia
			  </label>
			  <label  title="Eficiência baseada na quantidade prevista">
			    <input type="radio" ng-model="vm.TIPO_EFICIENCIA" ng-value="1">
			    Eficiência
			  </label>
			 </form>
		
	    </td>
	  </tr>
	  <tr >
	    <td class="descfab2" colspan="1">
			@{{vm.FILTRO.UP_DESCRICAO}}
		</td>
	    <td class="tg-1sci">META</td>
	    <td class="tg-1sci">PRODUÇÃO</td>
	    <td class="tg-1sci">@{{vm.DESC_EFIC}}</td>
	    <td class="tg-1sci">PERDAS</td>
	    <td class="tg-1sci">PRODUÇÃO</td>
	    <td class="tg-1sci">@{{vm.DESC_EFIC}}</td>
	    <td class="tg-1sci">PERDAS</td>
	  </tr>
	</table>

	@include('ppcp._22130.include.maquina', ['dados' => $dados])

	<div class="conteiner-estacao">
		<table class="tg" style="undefined;table-layout: fixed; width: 399px">
		<colgroup>
			<col style="width: 6.533vw">
			<col style="width: 7.066vw">
			<col style="width: 6vw">
			<col style="width: 3px">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
			<col style="width: 3px">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
			<col style="width: 3px">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
			<col style="width: 3px">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
			<col style="width: 6.533vw">
		</colgroup>
		  <tr>
		    <td class="tg-031g" colspan="2"></td>
		    <td class="tg-031g"></td>
		    <td class="tg-031g"></td>
		    <td class="tg-031g"></td>
		    <td class="tg-031g"></td>
		    <td class="tg-031g"></td>
		    <td class="tg-031g"></td>
		    <td class="tg-031g"></td>
		    <td class="tg-031g"></td>
		    <td class="tg-031g"></td>
		    <td class="tg-031g"></td>
		    <td class="tg-031g"></td>
		    <td class="tg-031g"></td>
		    <td class="tg-031g"></td>
		    <td class="tg-031g"></td>
		    <td class="tg-031g" colspan="3"></td>
		  </tr>
		</table>
	</div>

	@include('ppcp._22130.include.modal')
	
	@include('ppcp._22130.include.validar_matriz')
	@include('ppcp._22130.include.trocar_ferramenta')
	@include('ppcp._22130.include.trocar_estacao')
	@include('ppcp._22130.include.parar_estacao')
	@include('ppcp._22130.include.parar_talao')
	@include('ppcp._22130.include.just_inefic')
	@include('ppcp._22130.include.setup')

	@include('ppcp._22130.include.login')
	@include('ppcp._22130.include.login2')

	@include('ppcp._22130.include.producao')
	
	@include('ppcp._22130.include.talao_producao')

	@include('ppcp._22130.include.info-componentes')

	

</div>

<div class="legenda-container">
	
	<ul class="legenda talao">
		<li>
			<div class="texto-legenda" >Fonte : </div>
		</li>

		<li>
			<div class="cor-legenda2 azul"></div>
			<div class="texto-legenda">Em Produção</div>
		</li>

		<li>
			<div class="cor-legenda2 verde"></div>
			<div class="texto-legenda">Abastecido</div>
		</li>
		<li>
			<div class="cor-legenda2 amarelo"></div>
			<div class="texto-legenda">Cortado</div>
		</li>
		<li>
			<div class="cor-legenda2 preto"></div>
			<div class="texto-legenda">Não Cortado</div>
		</li>
	</ul>
</div>

<div class="legenda-container">
	
	<ul class="legenda talao">
		<li>
			<div class="texto-legenda" >Fundo : </div>
		</li>

		<li>
			<div class="cor-legenda2 branco"></div>
			<div class="texto-legenda">Rem. em Dia</div>
		</li>

		<li>
			<div class="cor-legenda2 cinza"></div>
			<div class="texto-legenda">Rem. Atrasada</div>
		</li>

		<li>
			<div class="cor-legenda2 creme"></div>
			<div class="texto-legenda">Rem. Atrasada</div>
		</li>

		<li>
			<div class="cor-legenda2 sobra"></div>
			<div class="texto-legenda">100% Aproveitamento</div>
		</li>

		<li>
			<div class="cor-legenda2 encerrado"></div>
			<div class="texto-legenda">100% Encerrado</div>
		</li>

		<li>
			<div class="cor-legenda2 vermelho"></div>
			<div class="texto-legenda">Conf. de Ferram.</div>
		</li>
	</ul>
</div>

<div class="legenda-container">
	
	<ul class="legenda talao">
		<li>
			<div class="texto-legenda" ></div>
		</li>

		<li><div class="texto-legenda"><strong>A</strong> - Rem. de Amostra</div></li>
		<li><div class="texto-legenda"><strong>R</strong> - Requisição</div></li>
		<li><div class="texto-legenda"><strong>P</strong> - Parado</div></li>
		<li><div class="texto-legenda"><strong>V</strong> - Rem. Vip</div></li>
		<li><div class="texto-legenda"><strong><span class="glyphicon glyphicon-plus" style="font-size: 0.5vw;"></strong> - Tal. Extra</div></li>

	</ul>
</div>

<div class="legenda-container">
	
	<ul class="legenda talao">
		<li>
			<div class="texto-legenda" >Legenda : </div>
		</li>

		<li><div class="texto-legenda"><strong class="f-azul" >M</strong> - Ferr. A Caminho</div></li>
		<li><div class="texto-legenda"><strong class="f-verde">M</strong> - Ferr. Separada</div></li>
		<li><div class="texto-legenda"><strong class="f-preto">M</strong> - Ferr. Não Separação</div></li>

	</ul>
</div>

@endsection

@section('script')
	
	<script src="{{ asset('assets/js/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ elixir('assets/js/_22130.js') }}"></script>

@append
