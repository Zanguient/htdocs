<ul class="list-inline popup-acoes" style="float: none;">
    <li class="imprimir-consumo">
		<label>{{ Lang::get('master.familia') }}:</label>
		<select id="filtro-familia-id-consumo">
			<option value="">{{ Lang::get('master.todos') }}</option>
			@foreach($remessa_familia_consumo as $fam)
				<option value="{{ $fam->FAMILIA_ID }}">{{ $fam->FAMILIA_DESCRICAO }}</option>
			@endforeach
		</select>
        <button type="button" class="btn btn-warning" id="imprimir-consumo" data-hotkey="alt+i" data-loading-text="{{ Lang::get('master.imprimindo') }}">
            <span class="glyphicon glyphicon-print"></span> 
            Imprimir Consumo
        </button>
    </li>
    <li>
		<button type="button" class="btn btn-grey160 gerar-historico" data-hotkey="alt+h" data-toggle="modal" data-target="#modal-historico">
			<span class="glyphicon glyphicon-time"></span> {{ Lang::get('master.historico') }}
		</button>
    </li>
</ul>
<div class="remessa-popup-show">
	<input type="checkbox" name="status" class="chk-switch {{ trim($remessa->STATUS) }}"
		   data-size="small"
		   data-on-color="success" 
		   data-off-color="danger" 
		   data-on-text="ATIVO"
		   data-off-text="INATIVO"
		   data-label-width="10"
		   disabled
		   checked="{{ (trim($remessa->STATUS) === '1') ? 'true' : 'false' }}"
	/>

    <fieldset readonly>
        <div class="row">
            <legend>{{ Lang::get('master.info-geral') }}</legend>
            <div class="form-group">
                <label for="remessa">{{ Lang::get('ppcp/_22040.remessa') }}:</label>
                <input type="text" name="remessa" id="remessa" class="form-control input-bold input-110" title="{{ 'ID:'.$remessa->REMESSA_ID }}" value="{{ $remessa->REMESSA }}" readonly required />
            </div>
            <div class="form-group">
                <label for="estab">{{ Lang::get('master.estab') }}:</label>
                <input type="search" name="estab" id="estab" class="form-control input-maior" value="{{ $remessa->ESTABELECIMENTO_ID }} - {{ $remessa->ESTABELECIMENTO_NOMEFANTASIA }}"  autocomplete="off" required readonly />
                <input type="hidden" name="_estab_id" value="{{ $remessa->ESTABELECIMENTO_ID }}" />
            </div>
            <div class="form-group">
                <label for="familia">{{ Lang::get('master.familia') }}:</label>
                <input type="text" name="familia" id="familia" class="form-control" required value="{{ $remessa->FAMILIA_ID }} - {{ $remessa->FAMILIA_DESCRICAO }}" readonly />
                <input type="hidden" name="_familia_id" value="{{ $remessa->FAMILIA_ID }}" />
                <input type="hidden" name="_familia_desc" value="{{ $remessa->FAMILIA_DESCRICAO }}" />
            </div>
            <div class="form-group">
                <label for="data">Data:</label>
                <input type="date" name="data" id="data" class="form-control" required value="{{ $remessa->DATA }}" readonly/>
            </div>
        </div>
 
        <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
            <ul id="myTabs" class="nav nav-tabs" role="tablist"> 
                <li role="presentation" class="active">
                    <a href="#taloes" id="taloes-tab" role="tab" data-toggle="tab" aria-controls="taloes" aria-expanded="true">Talões</a>
                </li> 
                <li role="presentation" class="">
                    <a href="#ups" role="tab" id="ups-tab" data-toggle="tab" aria-controls="ups" aria-expanded="false">UP's</a>
                </li> 
            </ul>
            <div id="myTabContent" class="tab-content"> 
                <div role="tabpanel" class="tab-pane fade active in" id="taloes" aria-labelledby="taloes-tab">   
                    <section class="talao">
                        <div class="panel panel-primary panel-table">                                                              
                            <div class="panel-heading panel-title">                                                                      
                                <div class="titulo-lista">                                                         
                                    <span></span>                                                        
                                    <span class="text-center">Cód. Talão                                          </span>                                      
                                    <span>Modelo                                                                  </span>                                                                  
                                    <span class="text-right">Dens.                                                </span> 
                                    <span class="text-right">Esp.                                                 </span>     
                                    <span>Unid. Produtiva                                                         </span>                                                      
                                    <span>Estação                                                                 </span>                                                             
                                    <span class="text-right"title="Quantidade Programada">Qtd.                    </span>                                                      
                                    <span class="text-right" title="Quantidade Alternativa Programada">Qtd. Alter.</span>                                                      
                                    <span class="text-right">Tempo (Min.)                                         </span>                                                             
                                    <span class="text-center">Data/Hora Início                                    </span>                                                    
                                    <span class="text-center">Data/Hora Fim                                       </span>
                                    <span class="text-center">Talão Origem                                        </span>
                                    <span class="text-center">                                                    </span>
                                </div>                                                                                           
                            </div>                                                                                               
                            <div class="panel-body">                                                                             
                                <div class="panel-group" id="accordion">
                                @foreach ( $taloes as $talao )
             
									<div class="panel panel-default">
										<div class="panel-heading" id="{{ $talao->ID }}">
											<a role="button" data-toggle="collapse" href="#collapse{{ $talao->ID }}">
												<span class="t-status text-center status{{ $talao->PROGRAMACAO_STATUS }}" title="{{ $talao->PROGRAMACAO_STATUS_DESCRICAO }}"></span>
												<span class="text-center">{{ $talao->REMESSA_TALAO_ID }}                                        </span>
												<span                    >{{ $talao->MODELO_ID }} - {{ $talao->MODELO_DESCRICAO }}              </span>
												<span class="text-right ">{{ number_format($talao->DENSIDADE, 2, ',', '.') }}                   </span>
												<span class="text-right ">{{ number_format($talao->ESPESSURA, 2, ',', '.') }}                   </span>
												<span                    >{{ $talao->UP_ID }} - {{ $talao->UP_DESCRICAO }}                      </span>
												<span                    >{{ $talao->ESTACAO }} - {{ $talao->ESTACAO_DESCRICAO }}               </span>
												<span class="text-right ">{{ number_format($talao->QUANTIDADE, 4, ',', '.') }} {{ $talao->UM }} </span>
												<span class="text-right ">{{ number_format($talao->QUANTIDADE_ALTERNATIVA, 4, ',', '.') }} {{ $talao->UM_ALTERNATIVA }} </span>
												<span class="text-right ">{{ number_format($talao->TEMPO, 2, ',', '.') }}                       </span>
												<span class="text-center">{{ date_format(date_create($talao->DATAHORA_INICIO), 'd/m/Y H:i:s') }}</span>
												<span class="text-center">{{ date_format(date_create($talao->DATAHORA_FIM), 'd/m/Y H:i:s') }}   </span>
												<span class="text-center" title="{{ $talao->TALOES_ORIGEM }}">{{ $talao->TALOES_ORIGEM }}       </span>
                                                <span class="text-right">
                                                    <button type="button" class="btn btn-xs btn-default btn-reabrir-talao btn-visible{{ $talao->STATUS }}" data-PROGRAMACAO_ID="{{$talao->PROGRAMACAO_ID}}" data-REMESSA_ID="{{$talao->REMESSA_ID}}" data-ID="{{$talao->ID}}" data-TALAO_ID="{{$talao->REMESSA_TALAO_ID}}">
                                                        <span class="glyphicon glyphicon-th-large"></span>Reabrir
                                                    </button>
                                                    
                                                </span>
											</a>
										</div>
										<div id="collapse{{ $talao->ID }}" class="panel-collapse collapse">
										  <div class="panel-body">

											<table class="table table-striped table-bordered table-hover table-itens">
												<thead>
													<tr>
														<th></th>
														<th class="text-center">Talão                                                    </th>
														<th                    >Modelo                                                   </th>
														<th                    >Cor                                                      </th>
														<th class="text-right ">Tam.                                                     </th>
                                                        <th class="text-right " title="Quantidade Programada">Qtd.                       </th>
														<th class="text-right " title="Quantidade Produzida">Qtd. Prod.                  </th>
														<th class="text-right " title="Quantidade Alternativa Programada">Quantidade     </th>
														<th class="text-right " title="Quantidade Alternativa Produzida">Quantidade Prod.</th>
													</tr>
												</thead>
												<tbody>
												@foreach ( $taloes_detalhe as $detalhe )  
													@if ( $detalhe->REMESSA_TALAO_ID == $talao->REMESSA_TALAO_ID )
													<tr>
														<td class="t-status text-center status{{ $detalhe->STATUS }}" title="{{ $detalhe->STATUS_DESCRICAO }}"></td>
														<td class="text-center         ">{{ $detalhe->ID }}                                             </td>
														<td                             >{{ $detalhe->MODELO_ID }} - {{ $detalhe->MODELO_DESCRICAO }}   </td>
														<td                             >{{ $detalhe->COR_ID }} - {{ $detalhe->COR_DESCRICAO }}         </td>
														<td class="text-right          ">{{ $detalhe->TAMANHO_DESCRICAO }}                              </td>
														<td class="text-right          ">{{ number_format($detalhe->QUANTIDADE, 4, ',', '.') }} {{ $detalhe->UM }}</td>
														<td class="text-right          ">{{ number_format($detalhe->QUANTIDADE_PRODUCAO, 4, ',', '.') }} {{ $detalhe->UM }}</td>
														<td class="text-right          ">{{ number_format($detalhe->QUANTIDADE_ALTERN, 4, ',', '.') }} {{ $detalhe->UM_ALTERNATIVA }}</td>
														<td class="text-right          ">{{ number_format($detalhe->QUANTIDADE_ALTERN_PRODUCAO, 4, ',', '.') }} {{ $detalhe->UM_ALTERNATIVA }}</td>
													</tr>
													@endif
												@endforeach
												</tbody>
											</table>                               

										  </div>
										</div>
									</div>
                                @endforeach
								</div>
                            </div>
                        </div>
						
						<div class="legenda-container">
							<label class="label-legenda">{{ Lang::get('master.legenda') }} {{ Lang::get('master.talao') }}</label>
							<ul class="legenda legenda-talao">
								<li>
									<div class="cor-legenda"></div>
									<div class="texto-legenda">{{ Lang::get($menu.'.parado') }}</div>
								</li>
								<li>
									<div class="cor-legenda"></div>
									<div class="texto-legenda">{{ Lang::get($menu.'.iniciado-parado') }}</div>
								</li>
								<li>
									<div class="cor-legenda"></div>
									<div class="texto-legenda">{{ Lang::get($menu.'.em-andamento') }}</div>
								</li>
								<li>
									<div class="cor-legenda"></div>
									<div class="texto-legenda">{{ Lang::get($menu.'.finalizado') }}</div>
								</li>
								<li>
									<div class="cor-legenda"></div>
									<div class="texto-legenda">{{ Lang::get($menu.'.encerrado') }}</div>
								</li>
							</ul>
						</div>
						
						<div class="legenda-container">
							<label class="label-legenda">{{ Lang::get('master.legenda') }} {{ Lang::get('master.talao-detalhe') }}</label>
							<ul class="legenda legenda-talao-detalhe">
								<li>
									<div class="cor-legenda"></div>
									<div class="texto-legenda">{{ Lang::get('master.em-aberto') }}</div>
								</li>
								<li>
									<div class="cor-legenda"></div>
									<div class="texto-legenda">{{ Lang::get('master.em-producao') }}</div>
								</li>
								<li>
									<div class="cor-legenda"></div>
									<div class="texto-legenda">{{ Lang::get('master.produzido') }}</div>
								</li>
								<li>
									<div class="cor-legenda"></div>
									<div class="texto-legenda">{{ Lang::get('master.encerrado') }}</div>
								</li>
							</ul>
						</div>
                    </section> 
                </div> 
                <div role="tabpanel" class="tab-pane fade" id="ups" aria-labelledby="ups-tab"> 
                    <div class="up-container">

                    @foreach ( $ups as $up )
                        <div class="up-bloco" data-up="{{ $up->ID }}">
                            <label>{{ Lang::get('master.up') }}: {{ $up->ID }} - {{ $up->DESCRICAO }}</label>
                            <div class="estacao-container">
                            @foreach ( $estacoes as $estacao )
                                @if ( $estacao->UP_ID == $up->ID )
                                <div class="estacao-bloco" data-estacao="{{ $estacao->ESTACAO }}">
                                    <div class="acoes-ordenar-estacao">
                                        <button type="button" class="btn btn-xs btn-default btn-subir" title="{{ Lang::get($menu.'.subir-title') }}" disabled>
                                            <span class="glyphicon glyphicon-chevron-up"></span>
                                        </button>
                                        <button type="button" class="btn btn-xs btn-default btn-descer" title="{{ Lang::get($menu.'.descer-title') }}" disabled>
                                            <span class="glyphicon glyphicon-chevron-down"></span>
                                        </button>
                                    </div>
									<div class="estacao-header-text">
										<label>{{ Lang::get('master.estacao') }}: {{ $estacao->ESTACAO }} - {{ $estacao->ESTACAO_DESCRICAO }}</label>
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
                                                <th></th>
                                                <th class="text-center ">{{ Lang::get($menu.'.talao') }}          </th>
                                                <th class="text-right  ">{{ Lang::get($menu.'.densidade-abrev') }}</th>
                                                <th class="text-right  ">{{ Lang::get($menu.'.espessura-abrev') }}</th>
                                                <th                     >{{ Lang::get('master.modelo') }}         </th>
                                                <th class="text-right  ">{{ Lang::get($menu.'.qtd_prog') }}       </th>
                                                <th class="text-right  ">{{ Lang::get('master.tempo') }}          </th>
                                                <th class="text-center ">{{ Lang::get('master.data-ini') }}       </th>
                                                <th class="text-center ">{{ Lang::get('master.data-fim') }}       </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ( $taloes as $talao )     
                                            @if ( $talao->UP_ID == $up->ID && $talao->ESTACAO == $estacao->ESTACAO )
                                            <tr>
                                                <td><input type="checkbox" name="talao" class="checkbox-talao" />                                  </td>
                                                <td class="t-status text-center status{{ $talao->PROGRAMACAO_STATUS }}" title="{{ $talao->PROGRAMACAO_STATUS_DESCRICAO }}"></td>
                                                <td class="text-center">{{ $talao->REMESSA_TALAO_ID }}                                             </td>
                                                <td class="text-right ">{{ number_format($talao->DENSIDADE, 2, ',', '.') }}                        </td>
                                                <td class="text-right ">{{ number_format($talao->ESPESSURA, 2, ',', '.') }}                        </td>
                                                <td                    >{{ $talao->MODELO_ID }} - {{ $talao->MODELO_DESCRICAO }}                   </td>
                                                <td class="text-right ">{{ number_format($talao->QUANTIDADE, 4, ',', '.') }}                       </td>
                                                <td class="text-right ">{{ number_format($talao->TEMPO, 2, ',', '.') }}                            </td>
                                                <td class="text-center">{{ date_format(date_create($talao->DATAHORA_INICIO), 'd/m/Y H:i:s') }}     </td>
                                                <td class="text-center">{{ date_format(date_create($talao->DATAHORA_FIM), 'd/m/Y H:i:s') }}        </td>                                    
                                            </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            @endforeach
                            </div>
                        </div>
                    @endforeach					
                    </div>     
					
					<ul class="legenda">
                        <li>
                            <div class="cor-legenda"></div>
                            <div class="texto-legenda">{{ Lang::get($menu.'.parado') }}</div>
                        </li>
                        <li>
                            <div class="cor-legenda"></div>
                            <div class="texto-legenda">{{ Lang::get($menu.'.iniciado-parado') }}</div>
                        </li>
                        <li>
                            <div class="cor-legenda"></div>
                            <div class="texto-legenda">{{ Lang::get($menu.'.em-andamento') }}</div>
                        </li>
                        <li>
                            <div class="cor-legenda"></div>
                            <div class="texto-legenda">{{ Lang::get($menu.'.finalizado') }}</div>
                        </li>
                        <li>
                            <div class="cor-legenda"></div>
                            <div class="texto-legenda">{{ Lang::get($menu.'.encerrado') }}</div>
                        </li>
					</ul>	
                </div> 
            </div> 
        </div>          
    </fieldset>    
</div>