@extends('master')

@section('titulo')
{{ Lang::get($menu.'.titulo') }}
@endsection

@section('estilo')
<link rel="stylesheet" href="{{ elixir('assets/css/22040.css') }}" />
@endsection

@section('conteudo')
<div ng-controller="Ctrl as vm" ng-cloak>
    <ul class="list-inline acoes">

        <li>
            <button type="button" class="btn btn-primary btn-processar" data-hotkey="alt+p" id="btn-processar" disabled>
                <span class="glyphicon glyphicon-tasks"></span>
                {{ Lang::get($menu.'.processar') }}
            </button>
        </li>
        <li>
            <button type="button" class="btn btn-success btn-inline btn-distribuir-auto" data-hotkey="alt+a" id="btn-distribuir-auto" disabled>
                <span class="glyphicon glyphicon-flash"></span>
                {{ Lang::get($menu.'.processar-auto') }}
            </button>
        </li>
        <li>
            <a href="{{ url('_22040') }}" class="btn btn-danger btn-cancelar" data-confirm="yes" data-hotkey="f11">
                <span class="glyphicon glyphicon-ban-circle"></span> 
                {{ Lang::get('master.cancelar') }}
            </a>
        </li>
    </ul>
    <form class="form-inline form-filtrar">    
        <fieldset>
            <legend>{{ Lang::get($menu.'.info-remessa') }}</legend>

            <div class="form-group">
                <label>{{ Lang::get($menu.'.remessa') }} / {{ Lang::get($menu.'.pedido') }}:</label>
                <div class="input-group">
                    <input 
                        type="text" 
                        id="remessa" 
                        class="form-control" 
                        required 
                        autofocus 
                        autocomplete="off" 
                        data-toggle="tooltip" 
                        data-html="true" 
                        data-placement="bottom" 
                        title="
                            Insira uma remessa para gerar um componente<br/>
                            Insira 'REP' para gerar remessa de reposição de estoque<br/>
                            Insira 'REQ' para gerar remessa a partir de requisições<br/>
                            Insira 'PD+Nº Pedido' para gerar remessa a partir de um pedido
                        " 
                        value="{{ Input::get('remessa') }}" />
                    <input type="hidden" class="_remessa-id" />
                    <input type="hidden" class="_requisicao" />

                    <button type="button" id="selec-remessa" class="input-group-addon btn-filtro" tabindex="-1">
                        <span class="glyphicon glyphicon-triangle-right"></span>
                    </button>
                    <button type="button" id="limpar-remessa" class="input-group-addon btn-filtro" tabindex="-1">
                        <span class="fa fa-close"></span>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label>{{ Lang::get('master.estab') }}:</label>
                <input type="text" id="estab" class="estab" readonly required tabindex="-1" />
                <input type="hidden" class="_estab-id" />
                <input type="hidden" class="_estab-descricao" />
            </div>

            @include('helper.include.view.consulta', [
                'label_descricao'   => 'GP:',
                'obj_consulta'      => 'Ppcp/include/_22030-gp',
                'obj_ret'           => ['ID','DESCRICAO'],
                'campos_sql'        => ['ID','DESCRICAO','FAMILIA_ID','FAMILIA_DESCRICAO','FAMILIA_UM_ALTERNATIVA','PERFIL','CONTROLE_TALAO','HABILITA_QUEBRA_TALAO_SKU'],
                'campos_imputs'     => [['_gp_id','ID'],['_gp_descricao','DESCRICAO'],['_quebra-talao-sku','HABILITA_QUEBRA_TALAO_SKU']],
                'filtro_sql'        => [['STATUS','1'],['FAMILIA','0'],['ORDER','DESCRICAO,ID']],
                'campos_tabela'     => [['ID','80'],['DESCRICAO','200']],
                'campos_titulo'     => ['ID','DESCRIÇÃO'],
                'class1'            => 'input-medio',
                'class2'            => 'consulta_gp_grup',
                'required'          => 'required',
                'selecionado'		  => 'true',
                'recebe_valor'	  => [
                    ['_familia-id'				, 'FAMILIA_ID'				],
                    ['_familia-descricao'		, 'FAMILIA_DESCRICAO'		],
                    ['_familia-um-alternativa'	, 'FAMILIA_UM_ALTERNATIVA'	],
                    ['_perfil-valor'			, 'PERFIL'					],
                    ['_controle-talao'			, 'CONTROLE_TALAO'			]
                ]
            ])

            <div class="form-group">
                <label>{{ Lang::get('master.familia') }}:</label>
                <input type="text" id="familia" class="familia" readonly required />
                <input type="hidden" class="_familia-id" />
                <input type="hidden" class="_familia-descricao" />
                <input type="hidden" class="_familia-um-alternativa" />
            </div>

            <div class="form-group">
                <label>{{ Lang::get('master.data-prod') }}:</label>
                <div class="input-group">
                    <input type="date" id="data-prod" class="form-control" required />
                    <button type="button" id="limpar-data" class="input-group-addon btn-filtro" tabindex="-1" disabled>
                        <span class="fa fa-close"></span>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-inline btn-filtrar" data-hotkey="alt+f" id="btn-table-filter" disabled>
                    <span class="glyphicon glyphicon-filter"></span>
                    {{ Lang::get('master.filtrar') }}
                </button>
            </div>

        </fieldset>

        <input type="hidden" name="_perfil_valor"	class="_perfil-valor"   />
        <input type="hidden" name="_controle_talao"	class="_controle-talao" />

        <div class="conteudo-filtro">
            <fieldset>
                <table class="table table-striped table-bordered table-hover table-22040">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-right">{{ Lang::get($menu.'.densidade') }}</th>
                            <th class="text-right">{{ Lang::get($menu.'.espessura') }}</th>
                            <th>{{ Lang::get('master.produto') }}</th>
                            <th>{{ Lang::get('master.perfil') }}</th>
                            <th class="text-right">{{ Lang::get('master.tamanho') }}</th>
                            <th class="text-right">{{ Lang::get('master.qtd-abrev') }}</th>
                            <th class="text-right">{{ Lang::get($menu.'.qtd_a_prog') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </fieldset>
            <fieldset>
                <legend>{{ Lang::get('master.ups') }}</legend>
                <div class="up-container">
                    <div class="up-bloco">
                        <label>{{ Lang::get('master.up') }}:</label>
                        <div class="estacao-container">
                            <div class="estacao-bloco">
                                <div class="acoes-ordenar-estacao">
                                    <button type="button" class="btn btn-xs btn-default btn-subir" title="{{ Lang::get($menu.'.subir-title') }}" disabled>
                                        <span class="glyphicon glyphicon-chevron-up"></span>
                                    </button>
                                    <button type="button" class="btn btn-xs btn-default btn-descer" title="{{ Lang::get($menu.'.descer-title') }}" disabled>
                                        <span class="glyphicon glyphicon-chevron-down"></span>
                                    </button>
                                </div>

                                <div class="estacao-header-text">
                                    <label>{{ Lang::get('master.estacao') }}:</label>
                                    <label class="estacao-perfil">{{ Lang::get('master.perfil') }}:</label>
                                </div>

                                <div class="acoes-estacao">
                                    <button type="button" class="btn btn-xs btn-primary btn-incluir-consumo" title="{{ Lang::get($menu.'.incluir-title') }}" disabled>
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                    <button type="button" class="btn btn-xs btn-danger btn-excluir-consumo" title="{{ Lang::get($menu.'.excluir-title') }}" disabled>
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </button>
                                </div>
                                <table class="table table-striped table-bordered table-hover estacao">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th class="text-right densidade">{{ Lang::get($menu.'.densidade-abrev') }}</th>
                                            <th class="text-right espessura">{{ Lang::get($menu.'.espessura-abrev') }}</th>
                                            <th class="modelo">{{ Lang::get('master.modelo') }}</th>
                                            <th class="cor">{{ Lang::get('master.cor') }}</th>
                                            <th class="text-right tamanho">{{ Lang::get('master.tamanho-abrev') }}</th>
                                            <th class="text-right qtd-prog">{{ Lang::get($menu.'.qtd_prog') }}</th>
                                            <th class="text-right tempo">{{ Lang::get('master.tempo') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                <input type="hidden" class="_qtd-restante" value="0" />
                                <input type="hidden" class="_bloco-ultimo" value="0" />
                                <input type="hidden" class="_densidade-ultimo" value="0" />
                                <input type="hidden" class="_espessura-ultimo" value="0" />
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>
    </form>
    <input type="hidden" class="input-clone" value="" />
    
    @include('ppcp._22040.create.modal-reposicao-origem')
</div>
@endsection

@section('popup-form-start')

	<form action="{{ route('_22040.store') }}" url-redirect="{{ url('sucessoGravar/_22040') }}" method="POST" class="form-inline js-gravar edit popup-form">
		<input type="hidden" name="_token" value="{{ csrf_token() }}" />
	
@endsection

@section('popup-head-button')

	<li>
		<button type="submit" class="btn btn-success js-gravar" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}">
			<span class="glyphicon glyphicon-ok"></span>
			{{ Lang::get('master.gravar') }}
		</button>
	</li>

@endsection

@section('popup-head-title')

	{{ Lang::get($menu.'.remessa-proc') }}

@endsection

@section('popup-body')

	<div class="remessa-processada-container"></div>

@endsection

@section('popup-form-end')
	</form>
@endsection


@section('script')
	<script src="{{ elixir('assets/js/table.js') }}"></script>
	<script src="{{ elixir('assets/js/formatter.js') }}"></script>
    <script src="{{ elixir('assets/js/form-action.js') }}"></script>
	<script src="{{ elixir('assets/js/_22040.ng.js') }}"></script>
	<script src="{{ elixir('assets/js/_22040-create.js') }}"></script>
@append
