<table class="dashboard table table-bordered table-striped table-condensed example-animate-container">
    <thead>
        <tr>
            <th style="min-width: 210px;">GP/Estação</th>
            <th ng-repeat="data in vm.DADOS.DATAS" style="min-width: @{{ data.MINUTOS * 0.39448 }}px">
                @{{ data.DATA | date:'dd/MM/yy' }} / Minutos: @{{ data.MINUTOS }}'
            </th>
        </tr>
    </thead>
    <tbody>
        <tr ng-repeat="estacao in vm.DADOS.ESTACOES">
            <td>@{{ estacao.GP_DESCRICAO }} / @{{ estacao.ESTACAO_DESCRICAO }}</td>
            <td ng-repeat="data in vm.DADOS.DATAS" style="position: relative;">
                <div 
                    class="escala"
                    style="
                        background-color: rgb(238, 238, 238);
                        position: relative;
                        height: 16px;
                        width: 100%;
                        overflow: hidden;
                        border-radius: 11px;
                        box-shadow: 0 1px 1px rgb(160, 160, 160);
                    "
                >
                        
                    <div ng-repeat="talao in data.TALOES track by $index" 
                        style="
                            position: absolute;
                            height: 100%;
                            background: rgb(120, 175, 100);
                            transition: .5s;
                            left : @{{ ((  talao.MINUTO_INICIO / data.MINUTOS)*100).toFixed(3)       }}%; 
                            width: @{{ ((( talao.TEMPO         / data.MINUTOS)*100)+0.01).toFixed(3) }}%;
                        "
                        data-toggle="popover" 
                        data-placement="top" 
                        data-element-content="#talao-@{{ talao.ID }}"
                        bs-init
                    >
                        <div id="talao-@{{ talao.ID }}" style="display: none">
                            <fieldset style="padding-bottom: 10px">
                                <legend>Localização</legend>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th title="Grupo de Produção">GP</th>
                                            <th>Estação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>@{{ talao.GP_ID }}</td>
                                            <td>@{{ talao.UP_ID }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>