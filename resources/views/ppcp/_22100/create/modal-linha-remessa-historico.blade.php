@extends('helper.include.view.modal', ['id' => 'modal-linha-remessa-historico'])

@section('modal-header-left')

	<h4 class="modal-title">
		Histórico de Remessas da Linha
	</h4>

@overwrite

@section('modal-header-right')
    <button type="button" class="btn btn-default btn-voltar" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-chevron-left"></span> 
        Voltar
    </button>
@overwrite

@section('modal-body')
	
<div class="alert alert-warning">Atenção: Os dados exibidos são de até uma semana atrás.</div>

<div class="table-container table-remessa-historico">
    <table class="table table-bordered table-header">
        <thead>
            <tr>
                <th class="wid-remessa">
                    Remessa
                </th>
                <th class="wid-remessa-data" title="Data da remessa">
                    Data
                </th>
                <th class="wid-gp" title="Grupo de Produção">
                    GP
                </th>
                <th class="wid-estacao" title="Estação de Trabalho">
                    Estação
                </th>
            </tr>
        </thead>
    </table>
    <div class="scroll-table">
        <table class="table table-striped table-bordered table-hover table-body">
            <tbody>
                <tr ng-repeat="item in vm.Linha.selected.REMESSA_HISTORICO"
                    tabindex="0"
                    >
                    <td class="wid-remessa">
                        @{{ item.REMESSA }}
                    </td>
                    <td class="wid-remessa-data">
                        @{{ item.REMESSA_DATA | toDate | date:'dd/MM' : 'UTC' }}
                    </td>
                    <td class="wid-gp">
                        @{{ item.GP_DESCRICAO }}
                    </td>
                    <td class="wid-estacao">
                        @{{ item.ESTACAO_DESCRICAO }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>    


@overwrite
