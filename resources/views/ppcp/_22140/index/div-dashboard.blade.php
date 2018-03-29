<!--<div class="dashboard">
    <div class="dash-container">
        <div class="row escala">
            <div class="col-estacao">ESCALA DE TEMPO</div> 
            <div class="col-escala" ng-repeat="dia in vm.DADOS.DIAS track by $index" style="min-width: calc(@{{ dia.MINUTOS.length }}px * 8 + 1px);">
                <div class="col-minuto" ng-repeat="minuto in dia.MINUTOS track by $index">
                    <label>@{{ dia.DIA + ' ' +  minuto.HORA | toDate | date:'dd/MM HH:mm' }}</label>
                </div>
            </div>
        </div>
        <div class="row estacao" ng-repeat="estacao in vm.DADOS.ESTACOES track by $index">
            <div class="col-estacao">@{{ estacao.GP_ID }} @{{ estacao.GP_DESCRICAO }} / @{{ estacao.ESTACAO }} @{{ estacao.ESTACAO_DESCRICAO }}</div>
            <div class="col-escala" ng-repeat="dia in estacao.DIAS track by $index" style="min-width: calc(@{{ dia.MINUTOS.length }}px * 8 + 1px);">
                <div class="col-minuto" ng-repeat="minuto in dia.MINUTOS track by $index">
                    <label>@{{ dia.DIA + ' ' +  minuto.HORA | toDate | date:'dd/MM HH:mm' }}</label>
                </div>
            </div>
            
            <div class="col-talao" ng-repeat="talao in estacao.TALOES track by $index" style="min-width: calc(@{{ talao.TEMPO_TOTAL }}px * 8); left: calc(240px + (@{{ talao.MINUTO_INICIO }}px * 8));">
                <div class="remessa" title="Remessa @{{ talao.REMESSA }}">REM. @{{ talao.REMESSA }}</div>
                <div class="talao" title="Talão @{{ talao.REMESSA_TALAO_ID }}">TL. @{{ talao.REMESSA_TALAO_ID }}</div>
                <span class="glyphicon glyphicon-info-sign info-talao" data-toggle="popover" data-placement="auto" data-element-content="#talao-@{{ talao.ID }}" bs-init>
                    <div id="talao-@{{ talao.ID }}" style="display: none">
                        <fieldset style="padding-bottom: 10px">
                            <legend>Informações do Talão</legend>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Data/Hora Início</th>
                                        <th>Data/Hora Fim</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>@{{ talao.DATAHORA_INICIO | toDate | date:'dd/MM HH:mm'  }}</td>
                                        <td>@{{ talao.DATAHORA_FIM | toDate | date:'dd/MM HH:mm'  }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </div>
                </span>
                <div class="line" style="background: linear-gradient(rgba(255, 255, 255, 0), @{{ talao.FERRAMENTA_RGB }});"></div>
            </div>
        </div>        
    </div>
</div>-->



<div class="dashboard">
    <div class="dash-container">
        <div class="row escala">
            <div class="col-estacao">ESCALA DE TEMPO</div> 
            <div class="col-escala" ng-repeat="dia in vm.DADOS.DIAS track by $index" style="min-width: calc(@{{ dia.MINUTOS.length }}px * 8 + 1px);">
                <div class="col-minuto" ng-repeat="minuto in dia.MINUTOS track by $index">
                    <label>@{{ dia.DIA + ' ' +  minuto.HORA | toDate | date:'dd/MM HH:mm' }}</label>
                </div>
            </div>
        </div>
        <div class="row estacao" ng-repeat="estacao in vm.DADOS.ESTACOES track by $index">
            <div class="col-estacao">@{{ estacao.GP_ID }} @{{ estacao.GP_DESCRICAO }} / @{{ estacao.ESTACAO }} @{{ estacao.ESTACAO_DESCRICAO }}</div>
            <div class="col-escala" ng-repeat="dia in estacao.DIAS track by $index" style="min-width: calc(@{{ dia.MINUTOS.length }}px * 8 + 1px);">
                <div class="col-minuto" ng-repeat="minuto in dia.MINUTOS track by $index">
                    <label>@{{ dia.DIA + ' ' +  minuto.HORA | toDate | date:'dd/MM HH:mm' }}</label>
                </div>
            </div>
            
            <div class="col-talao" ng-repeat="talao in estacao.TALOES track by $index" style="min-width: calc(@{{ talao.TEMPO_TOTAL }}px * 8); left: calc(240px + (@{{ talao.MINUTO_INICIO }}px * 8));">
                <div class="remessa" title="Remessa @{{ talao.REMESSA }}">REM. @{{ talao.REMESSA }}</div>
                <div class="talao" title="Talão @{{ talao.REMESSA_TALAO_ID }}">TL. @{{ talao.REMESSA_TALAO_ID }}</div>
                <span class="glyphicon glyphicon-info-sign info-talao" data-toggle="popover" data-placement="auto" data-element-content="#talao-@{{ talao.ID }}" bs-init>
                    <div id="talao-@{{ talao.ID }}" style="display: none">
                        <fieldset style="padding-bottom: 10px">
                            <legend>Informações do Talão</legend>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Data/Hora Início</th>
                                        <th>Data/Hora Fim</th>
                                        <th>Data/Hora Ref Inicio</th>
                                        <th>Data/Hora Ref Fim</th>
                                        <th>Tempo Desconto</th>
                                        <th>Talao Ref Inicio</th>
                                        <th>Talao Ref Fim</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>@{{ talao.DATAHORA_INICIO | toDate | date:'dd/MM HH:mm'  }}</td>
                                        <td>@{{ talao.DATAHORA_FIM | toDate | date:'dd/MM HH:mm'  }}</td>
                                        <td>@{{ talao.REF_INICIO_DATAHORA | toDate | date:'dd/MM HH:mm'  }}</td>
                                        <td>@{{ talao.REF_FIM_DATAHORA | toDate | date:'dd/MM HH:mm'  }}</td>
                                        <td>@{{ talao.MINUTOS_DESCONTO }}</td>
                                        <td>@{{ talao.REF_INICIO_TALAO }}</td>
                                        <td>@{{ talao.REF_FIM_TALAO }}</td>

                                    </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </div>
                </span>
                <div class="line" style="background: linear-gradient(rgba(255, 255, 255, 0), @{{ talao.FERRAMENTA_RGB }});"></div>
            </div>
        </div>        
    </div>
</div>