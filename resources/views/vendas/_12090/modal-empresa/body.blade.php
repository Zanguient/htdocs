@extends('helper.include.view.modal', ['id' => 'modal-empresa', 'class_size' => 'modal-full'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Cota.gravarAlteracao()">
@overwrite

@section('modal-header-left')

<h4 class="modal-title">
    Detalhamento do Cliente
</h4>

@overwrite

@section('modal-header-right')

	<ul class="list-inline acoes" ng-if="vm.Empresa.empresaRepresentate()">    
		<li>
            <button data-consulta-historico data-tabela="TBEMPRESA" data-tabela-id="@{{ vm.Empresa.SELECTED.EMPRESA_ID }}" type="button" class="btn gerar-historico" data-hotkey="alt+h">
                <span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
            </button>
        </li>   
	</ul>

    <button type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
      <span class="glyphicon glyphicon-chevron-left"></span> Voltar
    </button>

@overwrite

@section('modal-body')
    <style>
        #panel-status {
            position: absolute;
            top: 6px;
            right: 6px;
            z-index: 1;
        }
        
        #panel-status > div {
            color: white;
            padding: 5px 20px;
            border-radius: 6px;
            box-shadow: 1px 2px 4px 0px black;
            font-weight: bold;
            float: left;
            margin-right: 10px;
        }
        
        #panel-status .empresa-status-1 {    
            background: rgb(0, 154, 0);
        }
        
        #panel-status .empresa-status-0 {
            background: rgb(202, 3, 3);
        }
        
        #panel-status .pedido-status-0 {
            background: rgb(0, 154, 0);
        }
        
        #panel-status .pedido-status-1 {
            background: rgb(202, 3, 3);
        }
        
        #panel-status .nf-status-0 {
            background: rgb(0, 154, 0);
        }
        
        #panel-status .nf-status-1 {
            background: rgb(202, 3, 3);
        }
        
    </style>
    <div ng-if="vm.Empresa.empresaRepresentate()">
        <div id="panel-status">
            <a style="margin: 1px;" class="btn @{{ vm.Empresa.SELECTED.BLOQUEIA_PEDIDO == '0' ? 'btn-success' : 'btn-danger' }}">@{{ vm.Empresa.SELECTED.BLOQUEIA_PEDIDO_DESCRICAO }} para Novos Pedidos</a>

            <a  style="margin: 1px;" href="{{ url('/_12100?') }}cliente=@{{ vm.Empresa.SELECTED.EMPRESA_ID }}&representante=@{{ vm.Empresa.SELECTED.REPRESENTANTE_ID }}" target="_blank" class="btn @{{ vm.Empresa.SELECTED.BLOQUEIA_NOTAFISCAL == '0' ? 'btn-success' : 'btn-danger' }}">@{{ vm.Empresa.SELECTED.BLOQUEIA_NOTAFISCAL_DESCRICAO }} para Documentos Fiscais</a>

            <a  style="margin: 1px;"  href class="btn @{{ vm.Empresa.SELECTED.EMPRESA_STATUS == '1' ? 'btn-success' : 'btn-danger' }}">@{{ vm.Empresa.SELECTED.EMPRESA_STATUS_DESCRICAO }}</a>
        </div>

        <fieldset style="margin-top: 25px;">
            <legend>Informações Gerais</legend>        
            <div class="row">
                <div class="form-group">
                    <label>Id:</label>
                    <input type="text" class="form-control input-menor text-center" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_ID }}"/>
                </div>
                <div class="form-group">
                    <label>Nome Fantasia:</label>
                    <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_NOMEFANTASIA }}"/>
                </div>
                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_RAZAO_SOCIAL }}"/>
                </div>       
            </div>
            <div class="row break large">
                <div class="form-group">
                    <input type="checkbox" id="empresa-cliente" class="form-control" disabled ng-checked="vm.Empresa.SELECTED.EMPRESA_HABILITA_CLIENTE == '1'"/>
                    <label for="empresa-cliente">Cliente</label>
                </div>
                <div class="form-group">
                    <input type="checkbox" id="empresa-representante" class="form-control" disabled ng-checked="vm.Empresa.SELECTED.EMPRESA_HABILITA_REPRESENTANTE == '1'"/>
                    <label for="empresa-representante">Representante</label>
                </div>
                <div class="form-group">
                    <input type="checkbox" id="empresa-fornecedor" class="form-control" disabled ng-checked="vm.Empresa.SELECTED.EMPRESA_HABILITA_FORNECEDOR == '1'"/>
                    <label for="empresa-fornecedor">Fornecedor</label>
                </div>
                <div class="form-group">
                    <input type="checkbox" id="empresa-transportadora" class="form-control" disabled ng-checked="vm.Empresa.SELECTED.EMPRESA_HABILITA_TRANSPORTADORA == '1'"/>
                    <label for="empresa-transportadora">Transportadora</label>
                </div>
            </div>
        </fieldset>            
        <div class="row break large">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#tab-geral">Informações Gerais</a>
                </li>
                <li>
                    <a data-toggle="tab" href="#tab-endereco">Transporte / Pagamento / Cobrança</a>
                </li>                    
                <li>
                    <a data-toggle="tab" href="#tab-observacao">Observações</a>
                </li>                    
                <li>
                    <a data-toggle="tab" href="#tab-cliente">Parâmetros de Cliente</a>
                </li>                    
                <li>
                    <a data-toggle="tab" href="#tab-modelo-preco" ng-click="vm.Empresa.consultarModelosPreco()">Preço por Modelo</a>
                </li>                    
            </ul>
            <div class="tab-content">
                <div id="tab-geral" class="tab-pane fade in active">
                    <fieldset>
                        <legend>Documentos e Inscrições</legend>        
                        <div class="row">
                            <div class="form-group">
                                <label>CNPJ/CPF:</label>
                                <input type="text" class="form-control" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_CNPJ_MASK }}"/>
                            </div>
                            <div class="form-group">
                                <label>Inscrição Estadual:  <span style="margin-left: 5px;" class="glyphicon glyphicon-info-sign" ttitle="<b>Informe a IE</b> para os Contribuintes de ICMS;<br/><b>ISENTO</b> para os Contribuintes Isentos;<br/><b>Deixe vazio</b> para os Não Contribuintes de ICMS"></span></label>
                                <input type="text" class="form-control" readonly required  value="@{{ vm.Empresa.SELECTED.EMPRESA_IE }}"/>
                            </div>
                            <div class="form-group">
                                <label>Inscrição Municipal:</label>
                                <input type="text" class="form-control" readonly required/>
                            </div>
                        </div>                         
                    </fieldset>
                    <fieldset>
                        <legend>Endereço</legend>        
                        <div class="row">
                            <div class="form-group">
                                <label>CEP:</label>
                                <input type="text" class="form-control" readonly required  value="@{{ vm.Empresa.SELECTED.EMPRESA_ENDERECO_CEP_MASK }}"/>
                            </div>
                            <div class="form-group">
                                <label>Endereço:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_ENDERECO }}"/>
                            </div>
                            <div class="form-group">
                                <label>Nº:</label>
                                <input type="text" class="form-control input-menor" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_ENDERECO_NUMERO }}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label>Complemento:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_ENDERECO_COMPLEMENTO }}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label>UF:</label>
                                <input type="text" class="form-control" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_UF }}"/>
                            </div>
                            <div class="form-group">
                                <label>Cidade:</label>
                                <input type="text" class="form-control" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_CIDADE }}"/>
                            </div>
                            <div class="form-group">
                                <label>Bairro:</label>
                                <input type="text" class="form-control" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_ENDERECO_BAIRRO }}"/>
                            </div>
                            <div class="form-group">
                                <label>Geolocalização:</label>
                                <a href="https://www.google.com/maps/?q=@{{ vm.Empresa.SELECTED.EMPRESA_LATITUDE }},@{{ vm.Empresa.SELECTED.EMPRESA_LONGITUDE }}" target="_blank">Google Maps [@{{ vm.Empresa.SELECTED.EMPRESA_LATITUDE }},@{{ vm.Empresa.SELECTED.EMPRESA_LONGITUDE }}]</a>
                            </div>
                        </div>                          
                    </fieldset>
                    <fieldset>
                        <legend>Site / Email's</legend>        
                        <div class="row">
                            <div class="form-group">
                                <label>Site:</label>
                                <input type="text" class="form-control input-maior normal-case" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_SITE }}"/>
                            </div>
                            <div class="form-group">
                                <label>Email Geral:</label>
                                <input type="text" class="form-control input-maior normal-case" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_EMAIL }}"/>
                            </div>
                        </div>                      
                        <div class="row">
                            <div class="form-group">
                                <label>Email para Danfe:</label>
                                <input type="text" class="form-control input-maior normal-case" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_EMAIL_XML }}"/>
                            </div>
                            <div class="form-group">
                                <label>Email para Cobrança:</label>
                                <input type="text" class="form-control input-maior normal-case" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_CPA }}"/>
                            </div>
                        </div>                      
                    </fieldset>
                    <fieldset>
                        <legend>Informações de Contato</legend>        
                        <div class="row">
                            <div class="form-group">
                                <label>Fone¹:</label>
                                <input type="text" class="form-control" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_FONE_MASK }}"/>
                            </div>
                            <div class="form-group">
                                <label>Fone²/Fax:</label>
                                <input type="text" class="form-control" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_FAX_MASK }}"/>
                            </div>
                            <div class="form-group">
                                <label>Celular:</label>
                                <input type="text" class="form-control" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_CELULAR_MASK }}"/>
                            </div>
                            <div class="form-group">
                                <label>Contato:</label>
                                <input type="text" class="form-control" readonly required value="@{{ vm.Empresa.SELECTED.EMPRESA_CONTATO }}"/>
                            </div>
                        </div>                         

                    </fieldset>
                </div>
                <div id="tab-endereco" class="tab-pane fade">
                    <fieldset>
                        <legend>Endereço de Cobrança</legend>        
                        <div class="row">
                            <div class="form-group">
                                <label>CEP:</label>
                                <input type="text" class="form-control" readonly required  value="@{{ vm.Empresa.SELECTED.COBRANCA_CEP_MASK }}"/>
                            </div>
                            <div class="form-group">
                                <label>Endereço:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.COBRANCA_ENDERECO }}"/>
                            </div>
                            <div class="form-group">
                                <label>Nº:</label>
                                <input type="text" class="form-control input-menor" readonly required value="@{{ vm.Empresa.SELECTED.COBRANCA_NUMERO }}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label>Complemento:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.COBRANCA_COMPLEMENTO }}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label>UF:</label>
                                <input type="text" class="form-control" readonly required value="@{{ vm.Empresa.SELECTED.COBRANCA_UF }}"/>
                            </div>
                            <div class="form-group">
                                <label>Cidade:</label>
                                <input type="text" class="form-control" readonly required value="@{{ vm.Empresa.SELECTED.COBRANCA_CIDADE }}"/>
                            </div>
                            <div class="form-group">
                                <label>Bairro:</label>
                                <input type="text" class="form-control" readonly required value="@{{ vm.Empresa.SELECTED.COBRANCA_BAIRRO }}"/>
                            </div>
                            <div class="form-group">
                                <label>Geolocalização:</label>
                                <a href="https://www.google.com/maps/?q=@{{ vm.Empresa.SELECTED.COBRANCA_LATITUDE }},@{{ vm.Empresa.SELECTED.COBRANCA_LONGITUDE }}" target="_blank">Google Maps [@{{ vm.Empresa.SELECTED.COBRANCA_LATITUDE }},@{{ vm.Empresa.SELECTED.COBRANCA_LONGITUDE }}]</a>
                            </div>
                        </div>                          
                    </fieldset>
                    <fieldset>
                        <legend>Endereço de Cobrança</legend>        
                        <div class="row">
                            <div class="form-group">
                                <label>CEP:</label>
                                <input type="text" class="form-control" readonly required  value="@{{ vm.Empresa.SELECTED.ENTREGA_CEP_MASK }}"/>
                            </div>
                            <div class="form-group">
                                <label>Endereço:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.ENTREGA_ENDERECO }}"/>
                            </div>
                            <div class="form-group">
                                <label>Nº:</label>
                                <input type="text" class="form-control input-menor" readonly required value="@{{ vm.Empresa.SELECTED.ENTREGA_NUMERO }}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label>Complemento:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.ENTREGA_COMPLEMENTO }}"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label>UF:</label>
                                <input type="text" class="form-control" readonly required value="@{{ vm.Empresa.SELECTED.ENTREGA_UF }}"/>
                            </div>
                            <div class="form-group">
                                <label>Cidade:</label>
                                <input type="text" class="form-control" readonly required value="@{{ vm.Empresa.SELECTED.ENTREGA_CIDADE }}"/>
                            </div>
                            <div class="form-group">
                                <label>Bairro:</label>
                                <input type="text" class="form-control" readonly required value="@{{ vm.Empresa.SELECTED.ENTREGA_BAIRRO }}"/>
                            </div>
                            <div class="form-group">
                                <label>Geolocalização:</label>
                                <a href="https://www.google.com/maps/?q=@{{ vm.Empresa.SELECTED.ENTREGA_LATITUDE }},@{{ vm.Empresa.SELECTED.ENTREGA_LONGITUDE }}" target="_blank">Google Maps [@{{ vm.Empresa.SELECTED.ENTREGA_LATITUDE }},@{{ vm.Empresa.SELECTED.ENTREGA_LONGITUDE }}]</a>
                            </div>
                        </div>                          
                    </fieldset>
                    <fieldset>
                        <legend>Informações de Transporte</legend>        
                        <div class="row">
                            <div class="form-group" ng-if="vm.Empresa.SELECTED.C_TRANSPORTADORA_ID > 0">
                                <label>Transportadora @{{ vm.Empresa.SELECTED.F_TRANSPORTADORA_ID > 0 ? ' - Cliente' : '' }}:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.C_TRANSPORTADORA_ID | lpad : [5,0] }} - @{{ vm.Empresa.SELECTED.C_TRANSPORTADORA_RAZAOSOCIAL }}"/>
                            </div>
                            <div class="form-group" ng-if="vm.Empresa.SELECTED.F_TRANSPORTADORA_ID > 0">
                                <label>Transportadora @{{ vm.Empresa.SELECTED.C_TRANSPORTADORA_ID > 0 ? ' - Fornecedor' : '' }}:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.F_TRANSPORTADORA_ID | lpad : [5,0] }} - @{{ vm.Empresa.SELECTED.F_TRANSPORTADORA_RAZAOSOCIAL }}"/>
                            </div>
                            <div class="form-group input-menor">
                                <label>Frete para Pedidos:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.FRETE }} - @{{ vm.Empresa.SELECTED.FRETE_DESCRICAO }}"/>
                            </div>
                        </div>                   
                    </fieldset>
                    <fieldset>
                        <legend>Condição e Forma de Pagamento</legend>        
                        <div class="row" ng-if="vm.Empresa.SELECTED.C_PAGAMENTO_FORMA > 0">
                            <div class="form-group">
                                <label>Cond. de Pagamento @{{ vm.Empresa.SELECTED.F_PAGAMENTO_FORMA > 0 ? '- Cliente' : '' }}:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.C_PAGAMENTO_CONDICAO_DESCRICAO }}"/>
                            </div>
                            <div class="form-group">
                                <label>Forma de Pagamento:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.C_PAGAMENTO_FORMA_DESCRICAO }}"/>
                            </div>
                        </div>                       
                        <div class="row" ng-if="vm.Empresa.SELECTED.F_PAGAMENTO_FORMA > 0">
                            <div class="form-group">
                                <label>Cond. de Pagamento @{{ vm.Empresa.SELECTED.F_PAGAMENTO_FORMA > 0 ? '- Transportadora' : '' }}:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.F_PAGAMENTO_CONDICAO_DESCRICAO }}"/>
                            </div>
                            <div class="form-group">
                                <label>Forma de Pagamento:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.F_PAGAMENTO_FORMA_DESCRICAO }}"/>
                            </div>
                        </div>                       
                    </fieldset>
                </div>
                <div id="tab-observacao" class="tab-pane fade">
                    <textarea class="form-control normal-case" style="height: 100%; width: 100%; resize: none;" wrap="off" readonly>@{{ vm.Empresa.SELECTED.EMPRESA_OBSERVACAO }}</textarea>
                </div>
                <div id="tab-cliente" class="tab-pane fade">
                    <fieldset>
                        <legend>Informações Comerciais</legend>        
                        <div class="row">
                            <div class="form-group">
                                <label>Representante:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.REPRESENTANTE_ID | lpad : [5,0] }} - @{{ vm.Empresa.SELECTED.REPRESENTANTE_RAZAOSOCIAL }}"/>
                            </div>
                            <div class="form-group">
                                <label ttitle="Percentual de Comsissão do Representante">% Com. Rep.:</label>
                                <input type="text" class="form-control input-menor" readonly required value="@{{ vm.Empresa.SELECTED.REPRESENTANTE_COMISSAO | number : 2 }}%"/>
                            </div>
                        </div>                       
                        <div class="row">
                            <div class="form-group">
                                <label>Vendedor:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.VENDEDOR_ID | lpad : [5,0] }} - @{{ vm.Empresa.SELECTED.VENDEDOR_NOME }}"/>
                            </div>
                            <div class="form-group">
                                <label ttitle="Percentual de Comsissão do Vendedor">% Com. Vend.:</label>
                                <input type="text" class="form-control input-menor" readonly required value="@{{ vm.Empresa.SELECTED.VENDEDOR_COMISSAO | number : 2 }}%"/>
                            </div>
                        </div>   
                        <div class="row">
                            <div class="form-group">
                                <input type="checkbox" id="cliente-tag" class="form-control" disabled ng-checked="vm.Empresa.SELECTED.TAG == '1'"/>
                                <label for="cliente-tag" ttitle="Envio de tag's para o produto ao cliente">Tag</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="cliente-confirmacao" class="form-control" disabled ng-checked="vm.Empresa.SELECTED.CONFIRMACAO_AUTOMATICA == '1'"/>
                                <label for="cliente-confirmacao">Confirmação Automática do Pedido</label>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="cliente-grade" class="form-control" disabled ng-checked="vm.Empresa.SELECTED.ATENDE_GRADE_COMPLETA == '1'"/>
                                <label for="cliente-grade">Atende Grade Completa do Pedido</label>
                            </div>
                        </div>            
                        <div class="row">
                            <div class="form-group">
                                <label>Conta Principal:</label>
                                <input type="text" class="form-control input-maior" readonly required value="@{{ vm.Empresa.SELECTED.CONTA_PRINCIPAL_ID | lpad : [5,0] }} - @{{ vm.Empresa.SELECTED.CONTA_PRINCIPAL_NOMEFANTASIA }}"/>
                            </div>
                        </div>                     
                    </fieldset>
                    <fieldset>
                        <legend>Informações Financeiras / Fiscais</legend>        
                        <div class="row">
                            <div class="form-group">
                                <label>Limite de Crédito:</label>
                                <div class="input-group dinheiro">
                                    <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                                    <input type="text" name="cota" class="form-control" decimal="2" min="0" required readonly  value="@{{ vm.Empresa.SELECTED.LIMITE_CREDITO | number : 2 }}" />
                                </div>                                
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="fiscal-tributario" class="form-control" disabled  ng-checked="vm.Empresa.SELECTED.SUBSTITUIDO_TRIBUTARIO == '1'" />
                                <label for="fiscal-tributario">Substituído Tributário</label>
                            </div>
                        </div>                       
                        <div class="row break large" style="margin-top: 15px;">
                            <textarea class="form-control normal-case" wrap="off" readonly="" rows="10" style="width: calc(100% - 10px);">@{{ vm.Empresa.SELECTED.OBSERVACAO_NF  }}</textarea>
                        </div>                   
                    </fieldset>
                </div>
                <div id="tab-modelo-preco" class="tab-pane fade" style="overflow: hidden;">
                    @include('vendas._12090.modal-empresa.tab-modelo-preco.body') 
                </div>
            </div>
        </div>
        <div class="row">
            Consulta Cadastro <a href="http://www.receita.fazenda.gov.br/PessoaJuridica/CNPJ/cnpjreva/Cnpjreva_Vstatus.asp?origem=comprovante&cnpj=@{{ vm.Empresa.SELECTED.EMPRESA_CNPJ }}" target="_blank">Receita Federal</a> /  <a href="http://www.sintegra.gov.br/" target="_blank">Sintegra</a> 
        </div>
    </div>
    <div ng-if="!vm.Empresa.empresaRepresentate()">
        <div class="alert alert-danger">
            Usuário sem permissão para consultar esta empresa.
        </div>
    </div>
@overwrite
