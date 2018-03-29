@extends('helper.include.view.modal', ['id' => 'modal-historico-movimentacao', 'class_size' => 'modal-lg'])

@section('modal-header-left')

	<h4 class="modal-title">
		Histórico de Movimentação
	</h4>

@overwrite

@section('modal-header-right')
    <button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-chevron-left"></span> 
        Voltar
    </button>
@overwrite

@section('modal-body')
    <div class="row">
        <div class="input-group input-group-filtro-obj" style="margin-bottom: 10px;">
            <input 
                type="search" 
                name="filtro_obj" 
                class="form-control pesquisa filtro-obj ng-pristine ng-valid ng-empty ng-touched" 
                placeholder="Pesquise..." 
                autocomplete="off" 
                title="Filtragem por: Ferramenta, GP, Estação" 
                ng-init="vm.HISTORICO_FILTRO = ''"
                ng-model="vm.HISTORICO_FILTRO"
                >
            <button type="button" class="input-group-addon btn-filtro btn-filtro-obj btn-pesquisar">
                <span class="fa fa-search"></span>
            </button>
        </div>
        
        <div class="table-container">
            <table class="table table-bordered table-header">
                <thead>
                    <tr>
                        <th class="data-hora">
                            Data/Hora
                        </th>
                        <th class="status">
                            Tipo / Status / Justificativa
                        </th>
                        <th class="estacao">
                            GP / Estação
                        </th>
                        <th class="remessa">
                            Remessa / Talão
                        </th>
                        <th class="operador">
                            Operador
                        </th>
                    </tr>
                </thead>
            </table>
            <div class="scroll-table">
                <table class="table table-striped table-bordered table-hover table-body">
                    <tbody>
                        <tr ng-repeat="item in vm.FERRAMENTA_HISTORICO
                            | find: {
                                model : vm.HISTORICO_FILTRO,
                                fields : [
                                    'FERRAMENTA_TIPO_DESCRICAO',
                                    'GP_DESCRICAO',
                                    'ESTACAO_DESCRICAO',
                                    'REMESSA',
                                    'REMESSA_TALAO_ID',
                                    'OPERADOR_DESCRICAO'
                                ]
                            }"
                            tabindex="0"
                            >
                            <td class="data-hora">
                                @{{ item.DATAHORA | toDate | date:'dd/MM HH:mm' }}
                            </td>
                            <td 
                                class="status"
                                data-toggle="tooltip"
                                title="@{{ item.JUSTIFICATIVA_DESCRICAO }}"
                                >
                                @{{ item.FERRAMENTA_TIPO_DESCRICAO }} / 
                                @{{ item.STATUS_DESCRICAO }} 
                                @{{ item.JUSTIFICATIVA_ID > 0 ? ' - ' + item.JUSTIFICATIVA_DESCRICAO : '' }}
                            </td>
                            <td class="estacao">
                                @{{ (item.GP_DESCRICAO != null) ? item.GP_DESCRICAO + ' - ' + item.ESTACAO_DESCRICAO : '' }}
                            </td>
                            <td class="remessa">
                                @{{ (item.REMESSA != null) ? item.REMESSA + ' / ' + item.REMESSA_TALAO_ID : '' }}
                            </td>    
                            <td class="operador">
                                @{{ item.OPERADOR_DESCRICAO }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>        
    </div>

@overwrite