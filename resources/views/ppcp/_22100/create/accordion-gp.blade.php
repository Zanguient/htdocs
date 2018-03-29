<div class="container-gp">
    <fieldset>
        <legend>
        <span>Grupos de Produção</span><div style="
            float: right;
                    "><span style="
            font-weight: bold;
        ">Total programado:</span><span style="
            width: 55PX;
            display: inline-block;
            text-align: right;
            font-weight: bold;
            font-size: 12px;
        ">@{{ (vm.TOTAL_GERAL || 0) | number : 0 }}</span></div>
        </legend>
        <div class="agrupamento-gp">
            
            
            <div class="panel panel-primary panel-table">                                                              
                <div class="panel-heading panel-title">                                                                      
                    <div class="titulo-lista">                                                         
                        <span class="wid-gp-estacao" title="Grupo de Produção/Estação">GP/Estação</span>
                        <span class="wid-qtd text-right" title="Quantidade Programada">Qtd. Prog.</span>
                        <!--<span class="wid-perfil-sku" >Perfil Sku</span>-->
                        <span class="wid-medidas text-center">Larg. x Comp. x Alt.</span> 
                        <span class="wid-setup" title="Quantidade de Troca de Ferramenta">TF</span>     
                        <span class="wid-escala">Escala de Tempo Alocado</span>     
                        <span class="wid-qtd text-right" title="Tempo total disponivel para a data informada">TP Disp.</span>
                        <span class="wid-tempo-utilizado" title="Tempo Alocado - Habilita a programação da estação, desconsiderando o tempo já alocado em outras remessas (programação cheia)">T.O.</span>
                        <span class="wid-scroll"></span>
                    </div>                                                                                           
                </div>                                                                                               
                <div class="panel-body">           
                    <div class="panel-group accordion" id="accordion" role="tablist" aria-multiselectable="true">
                        <div
                            ng-class="(gp_up.GP_ID == vm.Gp.selected_id) ? 'selected' : ''" 
                            ng-repeat="gp_up in vm.GPS"
                            class="panel panel-default">
                            <div 
                                class="panel-heading" 
                                role="tab" 
                                id="heading@{{ $index }}">
                                <a role="button" data-toggle="collapse" href="#collapse@{{ $index }}" aria-controls="collapse@{{ $index }}">

                                    <span class="descricao gp">
                                        @{{ gp_up.GP_DESCRICAO }}
                                    </span>
                                    <span class="pares-programados">
                                        @{{ gp_up.QUANTIDADE_PROGRAMADA | number : 0 }}
                                    </span>
                                    <span class="wid-medidas">
                                    </span>
                                    <span class="wid-setup text-center">
                                        @{{ gp_up.QUANTIDADE_FERRAMENTAS | number: 0 }}
                                    </span>
                                    <div class="tempo-disponivel gp">
                                        <div class="tempo-barra-progresso-container" title="@{{ gp_up.MINUTOS_PROGRAMADOS | number : 2 }}' / @{{ gp_up.PERCENTUAL_UTILIZADO_ASC | number : 1 }}%">
                                            <span class="tempo-barra-progresso" style="width: @{{ gp_up.PERCENTUAL_UTILIZADO_DESC }}%"></span>
                                            <span class="tempo-alocado">@{{ gp_up.MINUTOS_PROGRAMADOS | number : 2 }}' / @{{ gp_up.PERCENTUAL_UTILIZADO_ASC | number : 1 }}%</span>
                                        </div>
                                    </div>
                                    <span class="minutos-total text-right">
                                        @{{ gp_up.MINUTOS | number : 0 }}'
                                    </span>
                                </a>
                            </div>
                            <div id="collapse@{{ $index }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading@{{ $index }}">
                                <div class="panel-body">
                                    @include('ppcp._22100.create.gp-container.accordion-estacao')
                                </div>
                            </div>
                        </div>	
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</div>