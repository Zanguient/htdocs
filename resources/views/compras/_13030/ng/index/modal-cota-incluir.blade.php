@extends('helper.include.view.modal', ['id' => 'modal-cota-incluir', 'class_size' => 'modal-big'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.CotaIncluir.gravar()">
@overwrite

@section('modal-header-left')

<h4 class="modal-title">
    Incluir Cota
</h4>

@overwrite

@section('modal-header-right')

    <button type="submit" class="btn btn-success" data-hotkey="F10">
        <span class="glyphicon glyphicon-ok"></span> Gravar
    </button>

    <button ng-click="vm.CotaIncluir.cancelar()" type="button" class="btn btn-danger btn-cancelar" data-hotkey="F11">
        <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
    </button>

@overwrite

@section('modal-body')

    <fieldset>
        <legend>{{ Lang::get('master.info-geral') }}</legend>
        <div class="row">              
            <div class="consulta-ccusto" style="display: inline-block;"></div>
            <div class="consulta-ccontabil" style="display: inline-block;"></div>
        </div>
        <div class="row">              
            @php $meses = array(1 => ['01','Janeiro'],['02','Fevereiro'],['03','Março'],['04','Abril'],['05','Maio'],['06','Junho'],['07','Julho'],['08','Agosto'],['09','Setembro'],['10','Outubro'],['11','Novembro'],['12','Dezembro'])
            <div class="form-group">
                <label>Data Inicial:</label>
                <select ng-init="vm.CotaIncluir.DADOS.MES_1 = '{{ date('n',strtotime('-1 Month')) }}'" ng-model="vm.CotaIncluir.DADOS.MES_1" class="form-control" required>
                    <option disabled>Mês</option>
                    @for ($i = 1; $i < 13; $i++)
                     <option value="{{ $i }}">{{ $meses[$i][1] }}</option>
                    @endfor
                </select>
                <select ng-init="vm.CotaIncluir.DADOS.ANO_1 = '{{ date('Y',strtotime('-1 Month')) }}'" ng-model="vm.CotaIncluir.DADOS.ANO_1" class="form-control" required>
                    <option disabled>Ano</option>
                    @for ($i = 2000; $i < 2041; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label>Data Final:</label>
                <select ng-init="vm.CotaIncluir.DADOS.MES_2 = '{{ date('n') }}'" ng-model="vm.CotaIncluir.DADOS.MES_2" class="form-control" required>
                    <option disabled>Mês</option>
                    @for ($i = 1; $i < 13; $i++)
                     <option value="{{ $i }}">{{ $meses[$i][1] }}</option>
                    @endfor
                </select>
                <select ng-init="vm.CotaIncluir.DADOS.ANO_2 = '{{ date('Y') }}'" ng-model="vm.CotaIncluir.DADOS.ANO_2" class="form-control" required>
                    <option disabled>Ano</option>
                    @for ($i = 2000; $i < 2041; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label for="valor">Valor:</label>
                <div class="input-group left-icon required">
                    <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                    <input type="number" ng-model="vm.CotaIncluir.DADOS.VALOR" class="form-control valor mask-numero" min="0" step="0.01"  decimal="2" required/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <input type="checkbox" name="bloqueio" id="incluir-bloqueia" class="form-control" ng-checked="vm.CotaIncluir.DADOS.BLOQUEIA == 1" ng-click="vm.CotaIncluir.DADOS.BLOQUEIA = vm.CotaIncluir.DADOS.BLOQUEIA == 1 ? 0 : 1" />
                <label for="incluir-bloqueia" data-toggle="tooltip" title="{{ Lang::get('compras/_13030.bloqueio-desc') }}" >{{ Lang::get('compras/_13030.bloqueio') }}</label>
            </div>
            <div class="form-group">
                <input type="checkbox" name="notificacao" id="incluir-notifica" class="form-control" ng-checked="vm.CotaIncluir.DADOS.NOTIFICA == 1" ng-click="vm.CotaIncluir.DADOS.NOTIFICA = vm.CotaIncluir.DADOS.NOTIFICA == 1 ? 0 : 1" />
                <label for="incluir-notifica"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.notificacao-desc') }}" >{{ Lang::get('compras/_13030.notificacao') }}</label>
            </div>
            <div class="form-group">
                <input type="checkbox" name="destaque" id="incluir-destaque" class="form-control" ng-checked="vm.CotaIncluir.DADOS.DESTAQUE == 1" ng-click="vm.CotaIncluir.DADOS.DESTAQUE = vm.CotaIncluir.DADOS.DESTAQUE == 1 ? 0 : 1" />
                <label for="incluir-destaque"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.destaque-desc') }}" >{{ Lang::get('compras/_13030.destaque') }}</label>
            </div>		    
            <div class="form-group">
                <input type="checkbox" name="totaliza" id="incluir-totaliza" class="form-control" ng-checked="vm.CotaIncluir.DADOS.TOTALIZA == 1" ng-click="vm.CotaIncluir.DADOS.TOTALIZA = vm.CotaIncluir.DADOS.TOTALIZA == 1 ? 0 : 1" />
                <label for="incluir-totaliza"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.totaliza-desc') }}" >{{ Lang::get('compras/_13030.totaliza') }}</label>
            </div>		                
        </div>
        <div class="row">
            <br/>

            <div class="alert alert-warning">
                <p><b>Atenção!</b></p>
                <p>Se já existir cota no período informado, permanecerá inalterada.</p>
            </div>                
        </div>
    </fieldset>
@overwrite

@section('modal-end')
    </form>
@overwrite