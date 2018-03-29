@extends('master')

@section('titulo')
    {{ Lang::get('custo/_31010.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/31010.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak style="zoom: 0.85;">

	@php $id = isset($_GET['SIMULACAO_ID']) ? $_GET['SIMULACAO_ID'] : 0

	<input type="hidden" class="_id_simulacao" value="{{$id}}" autocomplete="off">

	<div class="corpo-geral-container">

		<div class="kpi-div2">

			<div class="consultar-simulacao"></div>

			<div class="form-group " style="margin-right: 5px;">
				<label  title="Quantidade Total do Pedido">Descrição:</label>
				<input type="text"  class="form-control imput-maior"  style="width: 600px;" ng-model="vm.Descricao">
			</div>
			
			<div class="form-group" style="margin-right: 5px;">
				<label style="opacity: 0;" title="">.</label>
				<div class="input-group">
				    <button ng-disabled="{{$permissaoMenu->INCLUIR}}  == 0" type="button" ng-click="vm.gravarSimulacao()" class="btn btn-success" data-hotkey="f1" >
				        <span class="glyphicon glyphicon-floppy-save"></span> Gravar
				    </button>
				</div>
			</div>

			<div class="form-group" style="margin-right: 5px;">
				<label style="opacity: 0;" title="">.</label>
				<div class="input-group">
				    <button ng-disabled="{{$permissaoMenu->EXCLUIR}}  == 0" type="button" ng-click="vm.excluirSimulacao()" class="btn btn-danger" data-hotkey="f1" >
				        <span class="glyphicon glyphicon-trash"></span> Excluir
				    </button>
				</div>
			</div>
		</div>

		<div class="kpi-div" style="display: -webkit-box; background-color: #f7f7f7;">
			<div class="kpi-div2 consulta-padrao consulta-big" style="width: 700px;"></div>

			<div class="kpi-div2" style="margin-right: 5px;" ng-repeat="item in vm.PadraoItem" >
				<div class="form-group">
					<label  title="">@{{item.DESCRICAO}}:</label>
					<div  ng-if="item.USAR_FATOR == 0" class="input-group left-icon" style="width: 170px;">
						<div class="input-group-addon">%</div>
						<input  ng-disabled="item.EDITAVEL == false || (vm.Frete.PERCENTUAL > 0 && item.FRETE == 1)" type="number" ng-keyup="vm.recalcularPadrao()" style="height: 36px;" step="0.01" ng-min="0.00" min="0.00" class="form-control" ng-model="item.VALOR">
					</div>

					<div  ng-if="item.USAR_FATOR == 1" class="input-group left-icon" style="width: 200px;">
						<div class="input-group-addon">%</div>
						<input  ng-disabled="true" type="number" style="width: 76px; height: 36px;" step="0.01" ng-min="0.00" min="0.00" class="form-control" ng-model="item.VALOR">
						<input  ng-if="item.USAR_FATOR == 1"  ng-keyup="vm.CalcularFator(item)" type="number" style="width: 76px; height: 36px;" ng-min="0" min="0" class="form-control" ng-model="item.FATOR">
					</div>

				</div>	
			</div>
		    
		</div>

		<br>

		<div  class="kpi-div" style="display: -webkit-box; background-color: #f7f7f7;">
			<div class="kpi-div2">
				<div class="consulta-frete-transportadora"></div>
			</div>

			<div class="kpi-div2">   
            	<div class="consulta-cliente"></div>  
			</div>

            <div class="kpi-div2">  
            	<div class="consulta-cidade"></div> 
			</div>

			<div class="kpi-div2">
				<div class="form-group ">
					<label  title="Quantidade Total do Pedido">Frete Calculado:</label>
						<div class="input-group left-icon" style="width: 170px;">
						<div class="input-group-addon">%</div>
						<input type="text" disabled style="height: 34px;" class="form-control"  ng-value="(vm.Frete.PERCENTUAL * 100) | number:2">
						<div title="Zerar Valor do frete calculado" class="input-group-addon" style="border-left: 0px;border-radius: 0px 4px 4px 0px; cursor: pointer;" ng-click="vm.LimparFrete()">X
						</div>
					</div>

				</div>
			</div>
			
			<div class="kpi-div2">
				<div class="form-group">
					<label style="opacity: 0;" title="Margem de Contribuição">.</label>
					<div class="input-group">
						<button type="button" ng-click="vm.CalcularFrete()" class="btn " data-hotkey="f1" ng-class="{'btn-success': vm.Frete.CALCULADO == true, 'btn-danger' : vm.Frete.CALCULADO == false }">
					        <span class="glyphicon glyphicon-refresh"></span> Calcular Frete
					    </button>
					</div>
				</div>
			</div>
			
			<div class="kpi-div2">
				<div class="form-group">
					<label style="opacity: 0;" title="Margem de Contribuição">.</label>
					<div class="input-group">
					    <button style="margin-left: 10px;" ng-disabled="vm.Frete.CALCULADO == false" type="button" ng-click="vm.DetalharFrete()" class="btn btn-success" data-hotkey="f1">
					        <span class="glyphicon glyphicon-th"></span> Detalhar Frete
					    </button>
					</div>
				</div>
			</div>
		</div>

		<br>

		<div class="kpi-div detalhes" style="display: -webkit-box;  background-color: #f7f7f7;">

			<div class="kpi-div detalhes" style="  background-color: #f7f7f7;">

				<div class="form-group">
					<label  title="">Mês:</label>
					<div class="input-group">
						<select name="repeatSelect" id="repeatSelect" ng-model="vm.DATA.MES" ng-class="{'data-invalida': vm.DataInvalida == true}">
					        <option ng-repeat="option in vm.LISTA_MES" value="@{{option}}">@{{option}}</option>
					    </select>
					</div>
				</div>

				<div class="form-group">
					<label  title="">Ano:</label>
					<div class="input-group">
						<select name="repeatSelect" id="repeatSelect" ng-model="vm.DATA.ANO" ng-class="{'data-invalida': vm.DataInvalida == true}">
					        <option ng-repeat="option in vm.LISTA_ANO" value="@{{option}}">@{{option}}</option>
					    </select>
					</div>
				</div>

				<div class="form-group">
					<label  style="opacity: 0;" title="">.</label>
					<div class="input-group">
						<pre> à </pre>
					</div>
				</div>

				<div class="form-group">
					<label  title="">Mês:</label>
					<div class="input-group">
						<select name="repeatSelect" id="repeatSelect" ng-model="vm.DATA.MES2" ng-class="{'data-invalida': vm.DataInvalida == true}">
					        <option ng-repeat="option in vm.LISTA_MES2" value="@{{option}}">@{{option}}</option>
					    </select>
					</div>
				</div>

				<div class="form-group">
					<label  title="">Ano:</label>
					<div class="input-group">
						<select name="repeatSelect" id="repeatSelect" ng-model="vm.DATA.ANO2" ng-class="{'data-invalida': vm.DataInvalida == true}">
					        <option ng-repeat="option in vm.LISTA_ANO2" value="@{{option}}">@{{option}}</option>
					    </select>
					</div>
				</div>			
				
				
				<div class="form-group">
					<label style="opacity: 0;" title="">.</label>
					<div class="input-group">
						<button type="button" ng-click="vm.AdicionarItensCusto()" class="btn btn-success" data-hotkey="f1">
					        <span class="glyphicon glyphicon-plus"></span> Adicionar
					    </button>
					</div>
				</div>

			</div>
		
			<div class="kpi-div2">
				<div class="form-group ">
					<label  title="Quantidade Total do Pedido">Quantidade:</label>
					<div class="input-group left-icon" style="width: 130px;">
						<div class="input-group-addon">@{{vm.Total.UnidadeMedida}}</div>
						<input type="number"  style="height: 34px;" ng-min="1" min="1" readonly="" class="form-control" ng-model="vm.Total.Quantidade">
					</div>
				</div>
			</div>
			<div class="kpi-div2">
				<div class="form-group">
					<label  title="Custo Total dos Produtos">Custo Total:</label>
					<div class="input-group left-icon" style="width: 170px;">
						<div class="input-group-addon" style="background-color: green;color: white; border-color: green;">R$</div>
						<input type="text"  style="height: 34px;" class="form-control" readonly="" ng-model="vm.Total.CustoT" ng-value="vm.Total.CustoT | number:2">
					</div>
				</div>
			</div>
			<div class="kpi-div2">
				<div class="form-group">
					<label  title="Preço Total de Venda">Preço Total:</label>
					<div class="input-group left-icon" style="width: 170px;">
						<div class="input-group-addon" style="background-color: green;color: white; border-color: green;">R$</div>
						<input type="text"  style="height: 34px;" class="form-control" readonly="" ng-model="vm.Total.Venda" ng-value="vm.Total.Venda | number:2">
					</div>
				</div>
			</div>
			<div class="kpi-div2">
				<div class="form-group">
					<label  title="Markup">Markup:</label>
					<div class="input-group left-icon" style="width: 140px;">
						<div class="input-group-addon">%</div>
						<input type="number"  style="height: 35px;"  step="0.01" ng-min="1.00" min="1.00" class="form-control" ng-keyup="vm.keyupMarckUp()" ng-model="vm.Fatores.MarckUp">
					</div>
				</div>
			</div>

			<div class="kpi-div2">
				<div class="form-group">
					<label  title="Markup">Margem:</label>
					<div class="input-group left-icon" style="width: 140px;">
						<div class="input-group-addon">%</div>
						<input type="number"  style="height: 35px;"  step="0.01" ng-min="1.00" min="1.00" class="form-control" ng-keyup="vm.keyupMargem()" ng-model="vm.MARGEM">
					</div>
				</div>
			</div>

			<div class="kpi-div2">
				<div class="form-group">
					<label  title="Adicionar % de perda no custo dos produtos">Adicionar Perdas:</label>
					<div class="input-group" style="width: 140px;">
						<input style="width: 25px !important;height: 25px !important;" type="checkbox" ng-model="vm.Fatores.ConsiderarPerdas">
					</div>
				</div>
			</div>

			<div class="kpi-div2">
				<div class="form-group" style="border: 1px solid #d0d0d0;border-radius: 5px;padding: 5px;">
					<label  title="Adicionar % de perda no custo dos produtos">Incentivo Fiscal:</label>
					<div class="input-group"  >
						<span  ng-repeat="Incentivo in vm.ListaIncentivo" style="margin: 2px;">
							<input ng-disabled="vm.ConsultaPadrao.selected.INCENTIVO == 0" type="radio" style="width: 25px !important;height: 25px !important;" ng-model="vm.Fatores.Incentivo" value="@{{Incentivo.PERCENTUAL}}"><label style="margin: 5px;">@{{Incentivo.DESCRICAO}}</label>
						</span>
					</div>
				</div>
			</div>

		</div>


		<div class="kpi-div2">
			<div class="form-group">
				<label style="opacity: 0;">.</label>
				<button style="display: -webkit-box;" ng-if="vm.FLAG_RECALCULAR == true && vm.ListaItens.length > 0" type="button" ng-click="vm.RecalcularCusto()" class="btn btn-danger">
			        <span class="glyphicon glyphicon-refresh"></span> Calcular
			    </button>
			</div>	
		</div>

		<div class="kpi-div2">
			<div class="form-group">
				<label style="opacity: 0;">.</label>
				<button style="display: -webkit-box;" ng-if="vm.FLAG_RECALCULAR == false && vm.ListaItens.length > 0" type="button" ng-click="vm.RecalcularCusto()" class="btn btn-success">
			        <span class="glyphicon glyphicon-refresh"></span> Calcular
			    </button>
			</div>	
		</div>

		<br>

		<div class="itens-simulacao">
            <div class="scroll-table">
                <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
                    <thead>
                        <tr>
                        	<th title=""></th>
                            <th title="">Modelo</th>
                            <th title="">Cor</th>
                            <th title="">Tamanho</th>
                            <th title="">Quantidade</th>
                            <th title="">Custo Unitário</th>
                            <th title="">Custo Total</th>
                            <th title="">Despesa Total</th>
                            <th title="">Preço Unitário</th>
                            <th title="">Preço Total</th>
                            <th title="">IR</th>
                            <th title="">Margem de Contribuição (% / Unitário / Total)</th>  
                            <th title=""></th>  
                            <th title=""></th>                            
                        </tr>
                    </thead>
                    <tbody style="font-size: 18px;">

                        <tr class="container-consulta-itens" ng-repeat="itemCusto in vm.ListaItens track by $index"
                            tabindex="-1"     
                            >
                            <td>
                            	<div style="margin-top:10px;" class="item-modificado" ng-if="itemCusto.Ficha.PRODUTO_TROCA.length >  0" title="Produto com ficha técnica modificada"></div>
                            </td>
                            <td style="min-width: 200px;">
                            	<div class="container-consulta-itens">
                            		<div class="consulta-modelo-@{{itemCusto.id}}"></div>
                            	</div>
                            </td>
                            <td style="min-width: 200px;max-width: 200px;">
                            	<div class="container-consulta-itens">
                            		<div class="consulta-cor-@{{itemCusto.id}}"></div>
                            	</div>
                            </td>
                            <td style="min-width: 200px;max-width: 200px;">
                            	<div class="container-consulta-itens">
                            		<div style="display: -webkit-box;">
	                            		<div class="consulta-tamanho-@{{itemCusto.id}}"></div>

	                            		<button title="Desmembrar Tamanhos" style="display: block; margin-left: 7px;" ng-if="itemCusto.ConsultaTamanho.selected.ID == 21" type="button" ng-click="vm.ReplicarTamanhos(itemCusto)" class="btn btn-success btn-sam" ng-disabled="(itemCusto.ConsultaModelo.selected == null) || (itemCusto.ConsultaCor.selected == null) || (itemCusto.ConsultaTamanho.selected == null) || (itemCusto.CALCULADO  == 0) || (vm.ConsultaPadrao.item.selected == false)">
									        <span class="glyphicon glyphicon-share-alt"></span>
									    </button>
									</div>
                            	</div>
                            </td>
                            <td>
                            	<input style="width: 100px" ng-disabled="vm.ConsultaPadrao.item.selected == false" type="number"  ng-min="1" min="1" class="form-control" ng-keyup="itemCusto.keyupQuantidade()" ng-model="itemCusto.Quantidade">
                            </td>
                            <td class="left-text">
                            	R$ @{{itemCusto.Cst_u_Produto | number:4}}
                            </td>
                            <td class="left-text">
                            	R$ @{{itemCusto.Cst_t_Produto | number:2}}
                            </td>
                            <td class="left-text">
                            	R$ @{{itemCusto.Despesa | number:2}}
                            </td>
                            <td>
                            	<div style="display: -webkit-box;">
	                            	<div class="input-group left-icon" style="width: 150px;">
										<div class="input-group-addon" style="background-color: #3ec33e;color: white; border-color: #3ec33e;">R$</div>
										<input type="number" ng-disabled="itemCusto.MUDAR_PRECO == false" ng-if="itemCusto.MUDAR_PRECO == true"  style="height: 34px;" class="form-control"  ng-keyup="itemCusto.keyupValor()" ng-model="itemCusto.PrecoVenda">
										<input type="text" ng-disabled="itemCusto.MUDAR_PRECO == false" ng-if="itemCusto.MUDAR_PRECO == false" style="height: 34px;" class="form-control"  ng-keyup="itemCusto.keyupValor()" ng-value="itemCusto.PrecoVenda | number:2">
										<div title="Bloqueia ou libera a digitação do valor" class="input-group-addon" ng-class="{'img-close': itemCusto.MUDAR_PRECO == false, 'img-open' : itemCusto.MUDAR_PRECO == true}" style="border-left: 0px;border-radius: 0px 4px 4px 0px; cursor: pointer;" ng-click="itemCusto.Alternar(1)">
										</div>
									</div>

                            		<button title="Consultar preço de venda" style="display: block; margin-left: 7px;"  type="button" ng-click="vm.PrecoMedioVenda(itemCusto)" class="btn btn-success btn-sam" ng-disabled="(itemCusto.ConsultaModelo.selected == null) || (itemCusto.ConsultaCor.selected == null) || (itemCusto.ConsultaTamanho.selected == null) || (itemCusto.CALCULADO  == 0) || (vm.ConsultaPadrao.item.selected == false)">
								        <span class="glyphicon glyphicon-search"></span>
								    </button>
								</div>
							</td>
                            <td class="left-text">
                            	R$ @{{itemCusto.TotalPrecoVenda | number:2}}
							</td>
                            <td class="left-text" title="IR:@{{itemCusto.ImpostoDeRendaDesc}}">
                            	R$ @{{itemCusto.ImpostoDeRenda | number:4}}
							</td>
                            <td>
                            	<div style="display: flex;">
	                            	<div class="input-group left-icon" style="width: 150px; display: inline-table; margin-right: 5px;">
										<div class="input-group-addon">%</div>
										<input type="number" ng-disabled="itemCusto.MUDAR_CONTRIBUICAO == false" ng-if="itemCusto.MUDAR_CONTRIBUICAO == true"  style="height: 34px;" class="form-control"  ng-keyup="itemCusto.keyupValor()" ng-model="itemCusto.Contribuicao">
										<input type="text" ng-disabled="itemCusto.MUDAR_CONTRIBUICAO == false" ng-if="itemCusto.MUDAR_CONTRIBUICAO == false" style="height: 34px;" class="form-control"  ng-keyup="itemCusto.keyupValor()" ng-value="itemCusto.Contribuicao | number:2">
										<div title="Bloqueia ou libera a digitação do valor" class="input-group-addon" ng-class="{'img-close': itemCusto.MUDAR_CONTRIBUICAO == false, 'img-open' : itemCusto.MUDAR_CONTRIBUICAO == true}" style="border-left: 0px;border-radius: 0px 4px 4px 0px; cursor: pointer;" ng-click="itemCusto.Alternar(2)">
										</div>
									</div>
									<div class="input-group left-icon" style="width: 120px; display: inline-table; margin-right: 5px;">
										<div class="input-group-addon">R$</div>
										<input type="text"  title="Contribuição unitária" style="height: 34px;" class="form-control" readonly="" ng-value="itemCusto.ContribuicaoReal2 | number:4">
									</div>
									<div class="input-group left-icon" style="width: 120px; display: inline-table; margin-right: 5px;">
										<div class="input-group-addon">R$</div>
										<input type="text" title="Contribuição total"  style="height: 34px;" class="form-control" readonly="" ng-value="itemCusto.ContribuicaoReal | number:4">
									</div>
								</div>
							</td>
							<td>
								<button style="display: block;" type="button" ng-click="vm.DetalharCusto(itemCusto)" class="btn btn-success" data-hotkey="f1" ng-disabled="(itemCusto.ConsultaModelo.selected == null) || (itemCusto.ConsultaCor.selected == null) || (itemCusto.ConsultaTamanho.selected == null) || (itemCusto.CALCULADO  == 0) || (vm.ConsultaPadrao.item.selected == false)">
						        	<span class="glyphicon glyphicon-th"></span> Detalhar
						    	</button>
							</td>
							<td>
								<button style="display: block;" type="button" ng-click="vm.RemoverItem(itemCusto)" class="btn btn-danger" data-hotkey="f1" >
							        <span class="glyphicon glyphicon-trash"></span> Remover
							    </button>
							</td>

                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
@php /*
		<div class="kpi-div sku" style="display: -webkit-box;" ng-repeat="itemCusto in vm.ListaItens track by $index">
			
			<div class="kpi-div container-consulta-itens">
				<div style="width:236px" class="consulta-modelo-@{{$index}}"></div>
				<div style="width:236px" class="consulta-cor-@{{$index}}"></div>
				<div style="width:137px" class="consulta-tamanho-@{{$index}}"></div>
			</div>
			
			<div class="kpi-div2">
				<div class="form-group">
					<label  title="Quantidade Pedido">Quantidade:</label>
					<div class="input-group left-icon" style="width: 130px;">
						<div style="" class="input-group-addon">@{{itemCusto.UnidadeMedida}}</div>
						<input  ng-disabled="vm.ConsultaPadrao.item.selected == false" type="number"  style="height: 36px;" ng-min="1" min="1" class="form-control" ng-keyup="itemCusto.keyupQuantidade()" ng-model="itemCusto.Quantidade">
					</div>
				</div>
			</div>
			<div class="kpi-div2">
				<div class="form-group">
					<label  title="Custo Unitário do Produto">Custo Unitário:</label>
					<div class="input-group left-icon" style="width: 140px;">
						<div class="input-group-addon" style="background-color: #3ec33e;color: white; border-color: #3ec33e;">R$</div>
						<input type="text"  style="height: 34px;" class="form-control" readonly="" ng-value="itemCusto.Cst_u_Produto | number:4">
					</div>
				</div>
			</div>
			<div class="kpi-div2">
				<div class="form-group">
					<label  title="Custo Total do Produto">Custo Total:</label>
					<div class="input-group left-icon" style="width: 140px;">
						<div class="input-group-addon" style="background-color: #3ec33e;color: white; border-color: #3ec33e;">R$</div>
						<input type="text"  style="height: 34px;" class="form-control" readonly="" ng-value="itemCusto.Cst_t_Produto | number:2">
					</div>
				</div>
			</div>
			<div class="kpi-div2">
				<div class="form-group">
					<label  title="Despesa Total do Produto">Despesa Total:</label>
					<div class="input-group left-icon" style="width: 140px;">
						<div class="input-group-addon" style="background-color: #3ec33e;color: white; border-color: #3ec33e;">R$</div>
						<input type="text"  style="height: 34px;" class="form-control" readonly="" ng-value="itemCusto.Despesa | number:2">
					</div>
				</div>	
			</div>
			<div class="kpi-div2">
				<div class="form-group">
					<label  title="Preço Unitário de Venda">Preço Unitário:</label>
					<div class="input-group left-icon" style="width: 170px;">
						<div class="input-group-addon" style="background-color: #3ec33e;color: white; border-color: #3ec33e;">R$</div>
						<input type="number" ng-disabled="itemCusto.MUDAR_PRECO == false" ng-if="itemCusto.MUDAR_PRECO == true"  style="height: 34px;"  step="0.01" ng-min="0.00" min="0.00" class="form-control"  ng-keyup="itemCusto.keyupValor()" ng-model="itemCusto.PrecoVenda">
						<input type="text" ng-disabled="itemCusto.MUDAR_PRECO == false" ng-if="itemCusto.MUDAR_PRECO == false" style="height: 34px;" class="form-control"  ng-keyup="itemCusto.keyupValor()" ng-value="itemCusto.PrecoVenda | number:2">
						<div title="Bloqueia ou libera a digitação do valor" class="input-group-addon" ng-class="{'img-close': itemCusto.MUDAR_PRECO == false, 'img-open' : itemCusto.MUDAR_PRECO == true}" style="border-left: 0px;border-radius: 0px 4px 4px 0px; cursor: pointer;" ng-click="itemCusto.Alternar(1)">
						</div>
					</div>
				</div>
			</div>
			<div class="kpi-div2">
				<div class="form-group">
					<label  title="Preço Total de Venda">Preço Total:</label>
					<div class="input-group left-icon" style="width: 140px;">
						<div class="input-group-addon" style="background-color: #3ec33e;color: white; border-color: #3ec33e;">R$</div>
						<input type="text"  style="height: 34px;" class="form-control" readonly="" ng-model="itemCusto.TotalPrecoVenda" ng-value="itemCusto.TotalPrecoVenda | number:2">
					</div>
				</div>
			</div>
			<div class="kpi-div2">
				<div class="form-group">
					<label  title="Imposto de Renda">IR:</label>
					<div class="input-group left-icon" style="width: 140px;">
						<div class="input-group-addon" style="background-color: #3ec33e;color: white; border-color: #3ec33e;">R$</div>
						<input type="text" title="IR:@{{itemCusto.ImpostoDeRendaDesc}}" style="height: 34px;" class="form-control" readonly="" ng-value="itemCusto.ImpostoDeRenda | number:4">
					</div>
				</div>
			</div>
			<div class="kpi-div2" ng-if="itemCusto.MUDAR_PRECO == true">
				<div class="form-group">
					<label  title="Margem de Contribuição">Markup:</label>
					<div class="input-group left-icon" style="width: 110px;">
						<div class="input-group-addon">%</div>
						<input type="text"  style="height: 34px;" class="form-control" readonly="" ng-value="itemCusto.MarckUp | number:2">
					</div>
				</div>				
			</div>
			<div class="kpi-div2">
				<div class="form-group" style="width: 475px;">
					<label  title="Margem de Contribuição" style="display: block;">Margem de Contribuição:</label>

					<div class="input-group left-icon" style="width: 170px; display: inline-table;">
						<div class="input-group-addon">%</div>
						<input type="number"  step="0.01" ng-min="0.00" min="0.00" ng-disabled="itemCusto.MUDAR_CONTRIBUICAO == false" ng-if="itemCusto.MUDAR_CONTRIBUICAO == true"  style="height: 34px;" class="form-control"  ng-keyup="itemCusto.keyupValor()" ng-model="itemCusto.Contribuicao">
						<input type="text" ng-disabled="itemCusto.MUDAR_CONTRIBUICAO == false" ng-if="itemCusto.MUDAR_CONTRIBUICAO == false" style="height: 34px;" class="form-control"  ng-keyup="itemCusto.keyupValor()" ng-value="itemCusto.Contribuicao | number:2">
						<div title="Bloqueia ou libera a digitação do valor" class="input-group-addon" ng-class="{'img-close': itemCusto.MUDAR_CONTRIBUICAO == false, 'img-open' : itemCusto.MUDAR_CONTRIBUICAO == true}" style="border-left: 0px;border-radius: 0px 4px 4px 0px; cursor: pointer;" ng-click="itemCusto.Alternar(2)">
						</div>
					</div>
					<div class="input-group left-icon" style="width: 140px; display: inline-table;">
						<div class="input-group-addon">R$</div>
						<input type="text"  title="Contribuição unitária" style="height: 34px;" class="form-control" readonly="" ng-value="itemCusto.ContribuicaoReal2 | number:4">
					</div>
					<div class="input-group left-icon" style="width: 140px; display: inline-table;">
						<div class="input-group-addon">R$</div>
						<input type="text" title="Contribuição total"  style="height: 34px;" class="form-control" readonly="" ng-value="itemCusto.ContribuicaoReal | number:4">
					</div>
				</div>				
			</div>
			<div class="kpi-div2">
				<div class="form-group">
					<label style="opacity: 0;" title="Margem de Contribuição">.</label>
					<button style="display: block;" type="button" ng-click="vm.DetalharCusto(itemCusto)" class="btn btn-success" data-hotkey="f1" ng-disabled="(itemCusto.ConsultaModelo.selected == null) || (itemCusto.ConsultaCor.selected == null) || (itemCusto.ConsultaTamanho.selected == null) || (itemCusto.CALCULADO  == 0) || (vm.ConsultaPadrao.item.selected == false)">
			        	<span class="glyphicon glyphicon-th"></span> Detalhar
			    	</button>
				</div>	
			</div>
			<div class="kpi-div2">
				<div class="form-group">
					<label style="opacity: 0;" title="Margem de Contribuição">.</label>
					<button style="display: block;" type="button" ng-click="vm.RemoverItem(itemCusto)" class="btn btn-danger" data-hotkey="f1" >
				        <span class="glyphicon glyphicon-trash"></span> Remover
				    </button>
				</div>	
			</div>
		</div>
	@php */

	</div>


	@include('custo._31010.modal_sku')
	@include('custo._31010.modal_detalhar')
	@include('custo._31010.modal_absorcao')
	@include('custo._31010.modal_proprio')
	@include('custo._31010.modal_materia')
	@include('custo._31010.modal_maodeobra')
	@include('custo._31010.modal_despesa')
	@include('custo._31010.modal_detalhar_despesa')
	@include('custo._31010.modal_detalhar_despesa2')
	@include('custo._31010.modal_trocar_produto')
	@include('logistica._14020.modal-frete.index')


</div>
@endsection

@section('script')
	
	<script src="{{ asset('assets/js/loader.js') }}"></script>
    <script src="{{ elixir('assets/js/_31010.js') }}"></script>

@append
