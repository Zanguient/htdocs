	@extends('helper.include.view.modal', ['id' => 'modal-caso', 'class_size' => 'modal-lg'])

	@section('modal-header-left')

	<h4 class="modal-title">
		Caso - @{{vm.caso_id}}
	</h4>

	@overwrite

	@section('modal-header-right')
		
		<button ng-if="vm.status_tela == 0" ng-click="vm.Acoes.gravarCaso()" ng-disabled="vm.btnGravar.disabled == true" type="submit" class="btn btn-success" data-hotkey="f10" data-loading-text="Gravando...">
			<span class="glyphicon glyphicon-ok"></span> 
			Gravar
		</button>

		<button  ng-click="vm.Acoes.Canselar()" ng-if="vm.status_tela == 0"  type="button" class="btn btn-danger btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="f11">
		  <span class="glyphicon glyphicon-ban-circle"></span> Cancelar
		</button>


		<button ng-disabled="(vm.user.FINALIZAR == 0) || vm.CasoIten.FECHADO == 1" ng-if="vm.status_tela == 1" ng-click="vm.Acoes.finalizarCaso()" ng-disabled="vm.btnAlterar.disabled == true" type="submit" class="btn btn-warning" data-hotkey="f10">
			<span class="glyphicon glyphicon-saved"></span> 
			Finalizar
		</button>

		<button ng-disabled="vm.user.ALTERAR == 0 && vm.user.CODIGO != vm.CasoIten.USUARIO_ID" ng-if="vm.status_tela == 1" ng-click="vm.Acoes.alterarCaso({{$caso_id}})" ng-disabled="vm.btnAlterar.disabled == true" type="submit" class="btn btn-primary" data-hotkey="f9">
			<span class="glyphicon glyphicon-edit"></span> 
			Alterar
		</button>


		<button ng-disabled="vm.user.EXCLUIR == 0" ng-if="vm.status_tela == 1" ng-click="vm.Acoes.excluirCaso({{$caso_id}})" ng-disabled="vm.btnExcluir.disabled == true" type="submit" class="btn btn-danger" data-hotkey="f12">
			<span class="glyphicon glyphicon-trash"></span> 
			Excluir
		</button>


		<button  ng-if="vm.status_tela == 1" ng-click="vm.Acoes.Canselar()"  type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
		  <span class="glyphicon glyphicon-chevron-left"></span> Voltar
		</button>

		<button ng-if="vm.status_tela == 2" ng-click="vm.Acoes.gravarCaso()" ng-disabled="vm.btnGravar.disabled == true" type="submit" class="btn btn-success" data-hotkey="f10" data-loading-text="Gravando...">
			<span class="glyphicon glyphicon-ok"></span> 
			Gravar
		</button>

		<a ng-if="vm.status_tela == 2" ng-click="vm.Acoes.canselarAlteracaoCaso()"  class="btn btn-danger btn-cancelar" data-hotkey="f11">
			<span class="glyphicon glyphicon-ban-circle"></span> 
			Cancelar
		</a>

	@overwrite

	@section('modal-body')

	<ul id="tab" class="nav nav-tabs" role="tablist"> 
        <li role="presentation" class="active tab-detalhamento">
            <a href="#tab-caso-container" id="tab-caso" role="tab" data-toggle="tab" aria-controls="tab-caso-container" aria-expanded="false">
                Cas<span style="text-decoration: underline;">o</span>
            </a>
        </li>
        <li role="presentation" class="tab-detalhamento" ng-if="vm.tabFeed.btn.visivel == true">
            <a href="#tab-feed-container" id="tab-feed" role="tab" data-toggle="tab" aria-controls="tab-feed-container" aria-expanded="false">
                <span style="text-decoration: underline;">F</span>eed
            </a>
        </li>
        <li role="presentation" class="tab-detalhamento" ng-if="vm.tabHistory.btn.visivel == true">
            <a ng-click="vm.Acoes.historico()" href="#tab-history-container" id="tab-history" role="tab" data-toggle="tab" aria-controls="tab-history-container" aria-expanded="false">
                <span style="text-decoration: underline;">H</span>istórico
            </a>
        </li>
        <li role="presentation" class="tab-detalhamento" ng-if="vm.tabFiles.btn.visivel == true && vm.user.RESPONSAVEL == 1">
            <a ng-click="vm.Acoes.comentario()" href="#tab-files-container" id="tab-files" role="tab" data-toggle="tab" aria-controls="tab-files-container" aria-expanded="false">
                Comentár<span style="text-decoration: underline;">i</span>os
            </a>
        </li>
        <li role="presentation" class="tab-detalhamento" ng-if="vm.tabFiles.btn.visivel == true">
            <a ng-click="vm.lembrete.atualizar()" href="#tab-lebretes-container" id="tab-lebretes" role="tab" data-toggle="tab" aria-controls="tab-lebretes-container" aria-expanded="false">
                Lem<span style="text-decoration: underline;">b</span>retes
            </a>
        </li>
        <li role="presentation" class="tab-detalhamento" ng-if="vm.tabFiles.btn.visivel == true && vm.user.RESPONSAVEL == 1">
            <a ng-click="vm.Envolvidos.atualizar()" href="#tab-envolvidos-container" id="tab-envolvidos" role="tab" data-toggle="tab" aria-controls="tab-envolvidos-container" aria-expanded="false">
                Envol<span style="text-decoration: underline;">v</span>idos
            </a>
        </li>
    </ul>

	<div role="tabpanel" class="tab-pane fade active in" id="tab-caso-container" aria-labelledby="tab-caso">

		<div class="barra_descricao">
			@{{vm.PainelCaso.DESCRICAO}}
		</div>

		<div class="consulta_caso_conteiner">
			<div class="consulta_motivos"></div>
			<div class="consulta_tipos"></div>
			<div class="consulta_origens"></div>
			<div class="consulta_responsavel"></div>
			<div class="consulta_status"></div>
			<div class="consulta_contato"></div>
			<div class="adicionar_contato" style="display: inline-flex;">
				<div class="consulta-container">
					<div class="consulta">
						<div class="form-group consulta_contato_caso_forme">
							<label for="consulta-descricao" style="opacity: 0;">.</label>
							<div class="input-group">
								<button style="height: 34px;" ng-click="vm.Acoes.modalAddContato()" ng-if="vm.PainelCaso.CONTATO_CADASTRO == 1" class="btn btn-sm btn-primary" data-hotkey="alt+c" id="btn-table-filter">
									<span class="glyphicon glyphicon-user"></span>
									Cadastrar Contato
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="consulta_conta"></div>

			<div class="painel_imputs imput-itens-cad-contato"></div>
			
			<div id="iframecontainer" style="display: none;">
			    <div id="iframe">
			      <div id="iframewrapper"><iframe onerror="alert('Failed')" frameborder="0" id="iframeResult" name="iframeResult"></iframe></div>
			    </div>
			</div>
		</div>
	</div>

	<div role="tabpanel" class="tab-pane fade" style="height: calc(100% - 42px);" id="tab-feed-container" aria-labelledby="tab-feed">

		<button 
			type="button" 
			class="btn btn-sm btn-success" 
			ng-click="vm.Acoes.feedEmail(0)"
			ng-disabled="vm.CasoIten.FECHADO == 1" >
			<span class="glyphicon glyphicon-plus"></span>
			Email
		</button>
		<button 
			type="button" 
			class="btn btn-sm btn-success" 
			ng-click="vm.Acoes.feedFile(0)"
			ng-disabled="vm.CasoIten.FECHADO == 1" >
			<span class="glyphicon glyphicon-plus"></span>
			Feed
		</button>

		<button 
			type="button" 
			class="btn btn-sm btn-default atualizar-files" 
			ng-click="vm.Acoes.openCaso(vm.CasoIten.ID,null)">
			<span class="glyphicon glyphicon-refresh"></span>
			Atualizar
		</button>

		<div class="pesquisa-obj-container2" style="margin-top: 5px;">
			<div class="input-group input-group-filtro-obj">
				<input type="search" ng-model="vm.filtroFeed" name="filtro_obj" class="form-control pesquisa filtro-obj" placeholder="Pesquise..." autocomplete="off" autofocus="">
				<button type="button" class="input-group-addon btn-filtro btn-filtro-obj btn-pesquisar">
					<span class="fa fa-search"></span>
				</button>
			</div>
		</div>
		
		<div class="conteiner-feed">
			<div class="feed-caso feed-caso@{{feed.ID}}" ng-repeat="feed in vm.Feed | filter:vm.filtroFeed">
				
				<div class="foto semfoto"></div>
				<div  class="corpo">

					<div class="de" ng-if="feed.USUARIO_ID > -1" >@{{feed.USER_NAME}}</div>
					<div class="de" ng-if="feed.USUARIO_ID == -1 && feed.CONTATO > 0" >@{{feed.CONTATO_NAME}}</div>
					<div class="de" ng-if="feed.USUARIO_ID == -1 && feed.CONTATO == -1" >@{{feed.DE}}</div>

					<div class="email-para" ng-if="(feed.PARA != feed.DE && feed.PARA != '') || (feed.EM_COPIA != feed.DE && feed.EM_COPIA != '') || (feed.EM_COPIA_OCULTA != feed.DE && feed.EM_COPIA_OCULTA != '')">
						para :@{{(feed.EM_COPIA_OCULTA+', '+feed.EM_COPIA+', '+feed.PARA + '').replace(";", ",").replace(", ,", ",").replace(",,", ",").replace(";", ",").replace(", ,", ",").replace(",,", ",").replace(";", ",").replace(", ,", ",").replace(",,", ",").replace(";", ",").replace(", ,", ",").replace(",,", ",")}}
					</div>
					

					<div class="tipo tipo@{{feed.TIPO}}" title="@{{feed.DESC_TIPO}}"></div>

					<div ng-click="vm.Acoes.modalFeed(feed)" title="Expandir" class="feebtn_modal"><span class="glyphicon glyphicon-fullscreen"></span></div>
					<div ng-click="vm.Acoes.modalFeed(feed)" title="Fechar" class="fecharfeebtn_modal"><span class="glyphicon glyphicon-remove"></span></div>

					<div ng-bind-html="trustAsHtml(feed.MENSAGEM, feed)" class=" mensagem mensagem-html-container"></div>

					<div class="arquivo-container2" style="margin-top: 0px; border: 0px;" ng-if="feed.FILE != unknown">
						<div class="file" ng-repeat="arq in feed.FILE.slice().reverse() track by $index">
						
							<div class="form-group @{{arq.TIPO}}">

								<img
									title="@{{arq.ID ? arq.ID +' - '+ arq.NOME : arq.NOME}}"
									class="pre-visualizacao-arquivo"
									ngf-src="arq.BINARIO"
									ng-if="
										(arq.TIPO.indexOf('image') > -1) 
									 || (arq.TIPO.indexOf('png')   > -1) 
									 || (arq.TIPO.indexOf('jpg')   > -1)
									 || (arq.TIPO.indexOf('jpeg')  > -1) 
									 || (arq.TIPO.indexOf('gif')   > -1)"
									 ng-click="arq.VER = true"
									 >

								<span
									title="@{{arq.ID ? arq.ID +' - '+ arq.NOME : arq.NOME}}"
									class="pre-visualizacao-arquivo A A@{{arq.TIPO}}"
									ng-if="
										(arq.TIPO.indexOf('image') == -1) 
									 && (arq.TIPO.indexOf('png')  == -1) 
									 && (arq.TIPO.indexOf('jpg')  == -1)
									 && (arq.TIPO.indexOf('jpeg') == -1) 
									 && (arq.TIPO.indexOf('gif')  == -1)"
									 ng-click="arq.VER = true"
									 ></span>
								
								<div class="visualizar-arquivo" ng-show="arq.VER">

									<a 
										class="btn btn-default download-arquivo" 
										href="@{{ arq.BINARIO }}" 
										download 
										data-hotkey="alt+b">
										
										<span class="glyphicon glyphicon-download"></span>
										{{ Lang::get('master.download') }}
									</a>
									
									<button 
										type="button" 
										class="btn btn-default esconder-arquivo" 
										data-hotkey="f11"
										ng-click="arq.VER = false">
										
										<span class="glyphicon glyphicon-chevron-left"></span>
										{{ Lang::get('master.voltar') }}
									</button>

									{{-- Visualização somente para imagem e pdf --}}
									<object   style="height: 100%;"
										data="@{{ arq.BINARIO }}"
										ng-class="{imagem: 
											(arq.TIPO.indexOf('image') > -1)
										 || (arq.TIPO.indexOf('png') > -1) 
										 || (arq.TIPO.indexOf('jpg') > -1) || (arq.TIPO.indexOf('jpeg') > -1) 
										 || (arq.TIPO.indexOf('gif') > -1)}"
										ng-if="
											(arq.TIPO.indexOf('pdf') > -1) 
										 || (arq.TIPO.indexOf('image') > -1) 
										 || (arq.TIPO.indexOf('png') > -1) 
										 || (arq.TIPO.indexOf('jpg') > -1) || (arq.TIPO.indexOf('jpeg') > -1) 
										 || (arq.TIPO.indexOf('gif') > -1)"></object>

									{{-- Msg de visualização indisponível. --}}
									<label
										class="lbl-visualizacao-indisponivel"
										ng-if="
											(arq.TIPO.indexOf('pdf') == -1) 
										 && (arq.TIPO.indexOf('image') == -1) 
										 && (arq.TIPO.indexOf('png') == -1) 
										 && (arq.TIPO.indexOf('jpg') == -1) && (arq.TIPO.indexOf('jpeg') == -1) 
										 && (arq.TIPO.indexOf('gif') == -1)">

										Visualização indisponível!
									</label>

								</div>
							</div>
						</div>
					</div> 

					@php /*
					<div class="buttons" ng-if="feed.TIPO == 1 || feed.TIPO == 4">
						<button 
							type="button" 
							class="btn btn-sm btn-default" 
							ng-click="vm.Acoes.responderEmail(feed,0)">
							<span class="glyphicon glyphicon-envelope"></span>
							Responder
						</button>

						<button 
							type="button" 
							class="btn btn-sm btn-default" 
							ng-click="vm.Acoes.responderEmail(feed,1)">
							<span class="glyphicon glyphicon-envelope"></span>
							Responder a todos
						</button>
					</div>
					@php */
				</div>

				<div class="buttons2">
					<button 
						type="button" 
						class="btn btn-sm btn-success" 
						ng-click="vm.Acoes.comentarFeed(feed)"
						ng-disabled="vm.CasoIten.FECHADO == 1" >
						<span class="glyphicon glyphicon-comment"></span>
						Comentar
					</button>

					<button 
						type="button" 
						class="btn btn-sm btn-default" 
						ng-click="vm.Acoes.gosteiFeed(feed)"
						ng-if="feed.USUARIO_GOSTOU == 0"
						ng-disabled="vm.CasoIten.FECHADO == 1">
						<span class="glyphicon glyphicon-heart"></span>
						Gostei @{{feed.QTD_GOSTOU}}
					</button>

					<button 
						type="button" 
						class="btn btn-sm btn-default" 
						ng-click="vm.Acoes.gosteiFeed(feed)"
						ng-if="feed.USUARIO_GOSTOU == 1"
						ng-disabled="vm.CasoIten.FECHADO == 1" 
						style="color: red;">
						<span class="glyphicon glyphicon-heart"></span>
						Gostei @{{feed.QTD_GOSTOU}}
					</button>

					<button 
						type="button" 
						class="btn btn-sm btn-primary" 
						ng-click="vm.Acoes.editarFeedArquivo(feed)"
						ng-disabled="vm.CasoIten.FECHADO == 1" 
						ng-if="vm.user.CODIGO == feed.USUARIO_ID || vm.user.FEED == 1">
						<span class="glyphicon glyphicon-pencil"></span>
						Editar
					</button>

					<div class="data">@{{feed.DATA_REGISTRO}}</div>
				</div>

				<div class="feed-caso feed-comentario  feed-caso@{{res.ID}}" ng-repeat="res in feed.COMENTARIO">
					
					<div class="foto semfoto"></div>
					<div  class="corpo">
						<div class="de">@{{res.USER_NAME}}</div>
						<div class="tipo tipo@{{res.TIPO}}" title="@{{res.DESC_TIPO}}"></div>

						<div ng-click="vm.Acoes.modalFeed(res)" title="Expandir" class="feebtn_modal"><span class="glyphicon glyphicon-fullscreen"></span></div>
						<div ng-click="vm.Acoes.modalFeed(res)" title="Fechar" class="fecharfeebtn_modal"><span class="glyphicon glyphicon-remove"></span></div>
						
						<div ng-bind-html="trustAsHtml(res.MENSAGEM)" class="mensagem"></div>

						<div class="arquivo-container2" style="margin-top: 0px; border: 0px;" ng-if="res.FILE != unknown">
							<div class="file" ng-repeat="arq in res.FILE.slice().reverse() track by $index">
							
								<div class="form-group">

									<img  style="height: 100%;" 
										title="@{{arq.ID ? arq.ID +' - '+ arq.NOME : arq.NOME}}"
										class="pre-visualizacao-arquivo"
										ngf-src="arq.BINARIO"
										ng-if="
											(arq.TIPO.indexOf('image') > -1) 
										 || (arq.TIPO.indexOf('png')   > -1) 
										 || (arq.TIPO.indexOf('jpg')   > -1) || (arq.TIPO.indexOf('jpeg')   > -1) 
										 || (arq.TIPO.indexOf('gif')   > -1)"
										 ng-click="arq.VER = true"
										 >

									<span
										title="@{{arq.ID ? arq.ID +' - '+ arq.NOME : arq.NOME}}"
										class="pre-visualizacao-arquivo A A@{{arq.TIPO}}"
										ng-if="
											(arq.TIPO.indexOf('image') == -1) 
										 && (arq.TIPO.indexOf('png') == -1) 
										 && (arq.TIPO.indexOf('jpg') == -1) && (arq.TIPO.indexOf('jpeg') == -1) 
										 && (arq.TIPO.indexOf('gif') == -1)"
										 ng-click="arq.VER = true"
										 ></span>
									
									<div class="visualizar-arquivo" ng-show="arq.VER">

										<a 
											class="btn btn-default download-arquivo" 
											href="@{{ arq.BINARIO }}" 
											download 
											data-hotkey="alt+b">
											
											<span class="glyphicon glyphicon-download"></span>
											{{ Lang::get('master.download') }}
										</a>
										
										<button 
											type="button" 
											class="btn btn-default esconder-arquivo" 
											data-hotkey="f11"
											ng-click="arq.VER = false">
											
											<span class="glyphicon glyphicon-chevron-left"></span>
											{{ Lang::get('master.voltar') }}
										</button>

										{{-- Visualização somente para imagem e pdf --}}
										<object style="height: 100%;" 
											data="@{{ arq.BINARIO }}"
											ng-class="{imagem: 
												(arq.TIPO.indexOf('image') > -1)
											 || (arq.TIPO.indexOf('png') > -1) 
											 || (arq.TIPO.indexOf('jpg') > -1) || (arq.TIPO.indexOf('jpeg') > -1) 
											 || (arq.TIPO.indexOf('gif') > -1)}"
											ng-if="
												(arq.TIPO.indexOf('pdf') > -1) 
											 || (arq.TIPO.indexOf('image') > -1) 
											 || (arq.TIPO.indexOf('png') > -1) 
											 || (arq.TIPO.indexOf('jpg') > -1) || (arq.TIPO.indexOf('jpeg') > -1) 
											 || (arq.TIPO.indexOf('gif') > -1)"></object>

										{{-- Msg de visualização indisponível. --}}
										<label
											class="lbl-visualizacao-indisponivel"
											ng-if="
												(arq.TIPO.indexOf('pdf') == -1) 
											 && (arq.TIPO.indexOf('image') == -1) 
											 && (arq.TIPO.indexOf('png') == -1) 
											 && (arq.TIPO.indexOf('jpg') == -1) && (arq.TIPO.indexOf('jpeg') == -1) 
											 && (arq.TIPO.indexOf('gif') == -1)">

											Visualização indisponível!
										</label>

									</div>
								</div>
							</div>
						</div> 

						@php /*
						<div class="buttons" ng-if="res.TIPO == 1 || res.TIPO == 4">
							<button 
								type="button" 
								class="btn btn-sm btn-default" 
								ng-click="vm.Acoes.responderEmail(res,0)">
								<span class="glyphicon glyphicon-envelope"></span>
								Responder
							</button>

							<button 
								type="button" 
								class="btn btn-sm btn-default" 
								ng-click="vm.Acoes.responderEmail(res,1)">
								<span class="glyphicon glyphicon-envelope"></span>
								Responder a todos
							</button>
						</div>
						@php */
					</div>

					<div class="buttons2">
						
						<button 
							type="button" 
							class="btn btn-sm btn-default" 
							ng-click="vm.Acoes.gosteiFeed(res)"
							ng-if="res.USUARIO_GOSTOU == 0"
							ng-disabled="vm.CasoIten.FECHADO == 1" >
							<span class="glyphicon glyphicon-heart"></span>
							Gostei @{{res.QTD_GOSTOU}}
						</button>

						<button 
							type="button" 
							class="btn btn-sm btn-default" 
							ng-click="vm.Acoes.gosteiFeed(res)"
							ng-if="res.USUARIO_GOSTOU == 1"
							ng-disabled="vm.CasoIten.FECHADO == 1" 
							style="color: red;">
							<span class="glyphicon glyphicon-heart"></span>
							Gostei @{{res.QTD_GOSTOU}}
						</button>

						<button 
							type="button" 
							class="btn btn-sm btn-primary" 
							ng-click="vm.Acoes.editarFeedArquivo(res)"
							ng-if="vm.user.CODIGO == res.USUARIO_ID  || vm.user.FEED == 1"
							ng-disabled="vm.CasoIten.FECHADO == 1" >
							<span class="glyphicon glyphicon-pencil"></span>
							Editar
						</button>

						<div class="data">@{{res.DATA_REGISTRO}}</div>
					</div>

				</div>

			</div>
		</div>

	</div>

	<div role="tabpanel" class="tab-pane fade" id="tab-history-container" aria-labelledby="tab-history">
		<div class="table-container">
		    <table class="table table-bordered table-header table-lc">
		        <thead>
		            <tr>
		            	<th class="hist-datahora">Data/Hora</th>
		                <th class="hist-usuario ">Usuário</th>
		                <th class="hist-acao    ">Ação</th>
		            </tr>
		        </thead>
		    </table>
		    <div class="scroll-table">
		        <table class="table table-striped table-bordered table-hover table-body table-lc table-lc-body table-consumo">
		            <tbody>
		                <tr tabindex="0" ng-repeat="item in vm.tabHistory.dados">
		                  <td class="hist-datahora" auto-title >@{{ item.DATAHORA_REGISTRO }}</td>
		                  <td class="hist-usuario " auto-title >@{{ item.USUARIO}}</td>
		                  <td class="hist-acao    " auto-title >@{{ item.HISTORICO }}</td>
		                </tr>               
		            </tbody>
		        </table>
		    </div>
		</div>
	</div>

	<div role="tabpanel" class="tab-pane fade" id="tab-files-container" aria-labelledby="tab-files">
		<button 
			type="button" 
			class="btn btn-sm btn-success" 
			ng-click="vm.Acoes.feedFile(1)">
			<span class="glyphicon glyphicon-plus"></span>
			Comentário
		</button>

		<div class="pesquisa-obj-container2" style="margin-top: 5px;">
			<div class="input-group input-group-filtro-obj">
				<input type="search" ng-model="vm.filtroFeed" name="filtro_obj" class="form-control pesquisa filtro-obj" placeholder="Pesquise..." autocomplete="off" autofocus="">
				<button type="button" class="input-group-addon btn-filtro btn-filtro-obj btn-pesquisar">
					<span class="fa fa-search"></span>
				</button>
			</div>
		</div>
		
		<div class="conteiner-feed">
			<div class="feed-caso" ng-repeat="feed in vm.tabComentario.dados | filter:vm.filtroFeed">
				
				<div class="foto semfoto"></div>
				<div  class="corpo">
					<div class="de">@{{feed.USER_NAME}}</div>
					<div class="tipo tipo@{{feed.TIPO}}" title="@{{feed.DESC_TIPO}}"></div>
					<div ng-bind-html="trustAsHtml(feed.MENSAGEM)" class="mensagem mensagem-html-container"></div>
					<div class="arquivo-container2" style="margin-top: 0px; border: 0px;" ng-if="feed.FILE != unknown">
						<div class="file" ng-repeat="arq in feed.FILE.slice().reverse() track by $index">
						
							<div class="form-group">

								<img
									title="@{{arq.ID ? arq.ID +' - '+ arq.NOME : arq.NOME}}"
									class="pre-visualizacao-arquivo"
									ngf-src="arq.BINARIO"
									ng-if="
										(arq.TIPO.indexOf('image') > -1) 
									 || (arq.TIPO.indexOf('png')   > -1) 
									 || (arq.TIPO.indexOf('jpg')   > -1) || (arq.TIPO.indexOf('jpeg')   > -1) 
									 || (arq.TIPO.indexOf('gif')   > -1)"
									 ng-click="arq.VER = true"
									 >

								<span
									title="@{{arq.ID ? arq.ID +' - '+ arq.NOME : arq.NOME}}"
									class="pre-visualizacao-arquivo A@{{arq.TIPO}}"
									ng-if="
										(arq.TIPO.indexOf('image') == -1) 
									 && (arq.TIPO.indexOf('png') == -1) 
									 && (arq.TIPO.indexOf('jpg') == -1) && (arq.TIPO.indexOf('jpeg') == -1) 
									 && (arq.TIPO.indexOf('gif') == -1)"
									 ng-click="arq.VER = true"
									 ></span>
								
								<div class="visualizar-arquivo" ng-show="arq.VER">

									<a 
										class="btn btn-default download-arquivo" 
										href="@{{ arq.BINARIO }}" 
										download 
										data-hotkey="alt+b">
										
										<span class="glyphicon glyphicon-download"></span>
										{{ Lang::get('master.download') }}
									</a>
									
									<button 
										type="button" 
										class="btn btn-default esconder-arquivo" 
										data-hotkey="f11"
										ng-click="arq.VER = false">
										
										<span class="glyphicon glyphicon-chevron-left"></span>
										{{ Lang::get('master.voltar') }}
									</button>

									{{-- Visualização somente para imagem e pdf --}}
									<object  style="height: 100%;"
										data="@{{ arq.BINARIO }}"
										ng-class="{imagem: 
											(arq.TIPO.indexOf('image') > -1)
										 || (arq.TIPO.indexOf('png') > -1) 
										 || (arq.TIPO.indexOf('jpg') > -1) || (arq.TIPO.indexOf('jpeg') > -1) 
										 || (arq.TIPO.indexOf('gif') > -1)}"
										ng-if="
											(arq.TIPO.indexOf('pdf') > -1) 
										 || (arq.TIPO.indexOf('image') > -1) 
										 || (arq.TIPO.indexOf('png') > -1) 
										 || (arq.TIPO.indexOf('jpg') > -1) || (arq.TIPO.indexOf('jpeg') > -1) 
										 || (arq.TIPO.indexOf('gif') > -1)"></object>

									{{-- Msg de visualização indisponível. --}}
									<label
										class="lbl-visualizacao-indisponivel"
										ng-if="
											(arq.TIPO.indexOf('pdf') == -1) 
										 && (arq.TIPO.indexOf('image') == -1) 
										 && (arq.TIPO.indexOf('png') == -1) 
										 && (arq.TIPO.indexOf('jpg') == -1) && (arq.TIPO.indexOf('jpeg') == -1) 
										 && (arq.TIPO.indexOf('gif') == -1)">

										Visualização indisponível!
									</label>

								</div>
							</div>
						</div>
					</div> 
				</div>

				<div class="buttons2">
					<button 
						type="button" 
						class="btn btn-sm btn-success" 
						ng-click="vm.Acoes.comentarFeed(feed)"
						>
						<span class="glyphicon glyphicon-comment"></span>
						Comentar
					</button>

					<button 
						type="button" 
						class="btn btn-sm btn-default" 
						ng-click="vm.Acoes.gosteiFeed(feed)"
						ng-if="feed.USUARIO_GOSTOU == 0"
						>
						<span class="glyphicon glyphicon-heart"></span>
						Gostei @{{feed.QTD_GOSTOU}}
					</button>

					<button 
						type="button" 
						class="btn btn-sm btn-default" 
						ng-click="vm.Acoes.gosteiFeed(feed)"
						ng-if="feed.USUARIO_GOSTOU == 1"
						style="color: red;">
						<span class="glyphicon glyphicon-heart"></span>
						Gostei @{{feed.QTD_GOSTOU}}
					</button>

					<button 
						type="button" 
						class="btn btn-sm btn-primary" 
						ng-click="vm.Acoes.editarFeedArquivo(feed)"
						ng-if="vm.user.CODIGO == feed.USUARIO_ID || vm.user.FEED == 1">
						<span class="glyphicon glyphicon-pencil"></span>
						Editar
					</button>

					<div class="data">@{{feed.DATA_REGISTRO}}</div>
				</div>

				<div class="feed-caso feed-comentario" ng-repeat="res in feed.COMENTARIO">
					
					<div class="foto semfoto"></div>
					<div  class="corpo">
						<div class="de">@{{res.USER_NAME}}</div>
						<div class="tipo tipo@{{res.TIPO}}" title="@{{res.DESC_TIPO}}"></div>
						
						<div ng-bind-html="trustAsHtml(res.MENSAGEM)" class="mensagem"></div>
						<div class="arquivo-container2" style="margin-top: 0px; border: 0px;" ng-if="res.FILE != unknown">
						<div class="file" ng-repeat="arq in res.FILE.slice().reverse() track by $index">
						
							<div class="form-group">

								<img
									title="@{{arq.ID ? arq.ID +' - '+ arq.NOME : arq.NOME}}"
									class="pre-visualizacao-arquivo"
									ngf-src="arq.BINARIO"
									ng-if="
										(arq.TIPO.indexOf('image') > -1) 
									 || (arq.TIPO.indexOf('png')   > -1) 
									 || (arq.TIPO.indexOf('jpg')   > -1) || (arq.TIPO.indexOf('jpeg')   > -1) 
									 || (arq.TIPO.indexOf('gif')   > -1)"
									 ng-click="arq.VER = true"
									 >

								<span
									title="@{{arq.ID ? arq.ID +' - '+ arq.NOME : arq.NOME}}"
									class="pre-visualizacao-arquivo A@{{arq.TIPO}}"
									ng-if="
										(arq.TIPO.indexOf('image') == -1) 
									 && (arq.TIPO.indexOf('png') == -1) 
									 && (arq.TIPO.indexOf('jpg') == -1) && (arq.TIPO.indexOf('jpeg') == -1) 
									 && (arq.TIPO.indexOf('gif') == -1)"
									 ng-click="arq.VER = true"
									 ></span>
								
								<div class="visualizar-arquivo" ng-show="arq.VER">

									<a 
										class="btn btn-default download-arquivo" 
										href="@{{ arq.BINARIO }}" 
										download 
										data-hotkey="alt+b">
										
										<span class="glyphicon glyphicon-download"></span>
										{{ Lang::get('master.download') }}
									</a>
									
									<button 
										type="button" 
										class="btn btn-default esconder-arquivo" 
										data-hotkey="f11"
										ng-click="arq.VER = false">
										
										<span class="glyphicon glyphicon-chevron-left"></span>
										{{ Lang::get('master.voltar') }}
									</button>

									{{-- Visualização somente para imagem e pdf --}}
									<object  style="height: 100%;"
										data="@{{ arq.BINARIO }}"
										ng-class="{imagem: 
											(arq.TIPO.indexOf('image') > -1)
										 || (arq.TIPO.indexOf('png') > -1) 
										 || (arq.TIPO.indexOf('jpg') > -1) || (arq.TIPO.indexOf('jpeg') > -1) 
										 || (arq.TIPO.indexOf('gif') > -1)}"
										ng-if="
											(arq.TIPO.indexOf('pdf') > -1) 
										 || (arq.TIPO.indexOf('image') > -1) 
										 || (arq.TIPO.indexOf('png') > -1) 
										 || (arq.TIPO.indexOf('jpg') > -1) || (arq.TIPO.indexOf('jpeg') > -1) 
										 || (arq.TIPO.indexOf('gif') > -1)"></object>

									{{-- Msg de visualização indisponível. --}}
									<label
										class="lbl-visualizacao-indisponivel"
										ng-if="
											(arq.TIPO.indexOf('pdf') == -1) 
										 && (arq.TIPO.indexOf('image') == -1) 
										 && (arq.TIPO.indexOf('png') == -1) 
										 && (arq.TIPO.indexOf('jpg') == -1) && (arq.TIPO.indexOf('jpeg') == -1) 
										 && (arq.TIPO.indexOf('gif') == -1)">

										Visualização indisponível!
									</label>

								</div>
							</div>
						</div>
					</div> 
					</div>

					<div class="buttons2">
						
						<button 
							type="button" 
							class="btn btn-sm btn-default" 
							ng-click="vm.Acoes.gosteiFeed(res)"
							ng-if="res.USUARIO_GOSTOU == 0"
							>
							<span class="glyphicon glyphicon-heart"></span>
							Gostei @{{res.QTD_GOSTOU}}
						</button>

						<button 
							type="button" 
							class="btn btn-sm btn-default" 
							ng-click="vm.Acoes.gosteiFeed(res)"
							ng-if="res.USUARIO_GOSTOU == 1"
							style="color: red;">
							<span class="glyphicon glyphicon-heart"></span>
							Gostei @{{res.QTD_GOSTOU}}
						</button>

						<button 
							type="button" 
							class="btn btn-sm btn-primary" 
							ng-click="vm.Acoes.editarFeedArquivo(res)"
							ng-if="vm.user.CODIGO == res.USUARIO_ID  || vm.user.FEED == 1"
							>
							<span class="glyphicon glyphicon-pencil"></span>
							Editar
						</button>

						<div class="data">@{{res.DATA_REGISTRO}}</div>
					</div>

				</div>

			</div>
		</div>
	</div>

	<div role="tabpanel" class="tab-pane fade" id="tab-lebretes-container" aria-labelledby="tab-lebretes">
		<button 
			type="button" 
			class="btn btn-sm btn-success" 
			ng-click="vm.lembrete.add()">
			<span class="glyphicon glyphicon-plus"></span>
			Lembretes
		</button>

		<button 
			type="button" 
			class="btn btn-sm btn-default" 
			ng-click="vm.lembrete.atualizar()">
			<span class="glyphicon glyphicon-refresh"></span>
			Atualizar
		</button>

		* Dê um duplo click em um dos registros para editar ou excluir

		<p></p>

		<div class="table-ec">
		    <table class="table table-striped table-bordered table-hover table-body table-consumo">
		        <thead>
		            <tr>
		            	<th>Status</th>
		            	<th>Id</th>
		            	<th>Título</th>
		            	<th>Mensagem</th>
		                <th>Data/Hora Regis.</th>
		                <th>Data/Hora Agend.</th>
		            </tr>
		        </thead>
		        <tbody>
		            <tr tabindex="0" ng-repeat="iten in vm.lembrete.dados" ng-dblclick="vm.lembrete.editar(iten)">
		                <td auto-title >
		                	<span ng-if="iten.EXECUTADO == 0" t-title="Notificação NÃO enviada" style="color: red;font-size: 21px;text-align: center;width: 100%;"   class="glyphicon glyphicon-bell" aria-hidden="true"></span>
		                	<span ng-if="iten.EXECUTADO == 1" t-title="Notificação enviada"     style="color: green;font-size: 21px;text-align: center;width: 100%;" class="glyphicon glyphicon-bell" aria-hidden="true"></span>
		                </td>
		                <td auto-title >@{{ iten.ID }}</td>
		                <td auto-title >@{{ iten.TITULO}}</td>
		                <td auto-title ng-bind-html="trustAsHtml(iten.MENSAGEM)"></td>
		                <td auto-title >@{{ iten.DATA_HORA    | date: "dd/MM/yyyy HH:mm"}}</td>
		                <td auto-title >@{{ iten.AGENDAMENTO  | date: "dd/MM/yyyy HH:mm"}}</td>
		            </tr>               
		        </tbody>
		    </table>
		</div>
	</div>

	<div role="tabpanel" class="tab-pane fade" id="tab-envolvidos-container" aria-labelledby="tab-envolvidos">

		<button 
			type="button" 
			class="btn btn-sm btn-default" 
			ng-click="vm.Envolvidos.atualizar()">
			<span class="glyphicon glyphicon-refresh"></span>
			Atualizar
		</button>

		<p></p>

		<div class="consulta_envolvidos">
			
		</div>

		<p></p>

		<div class="table-ec">
		    <table class="table table-striped table-bordered table-hover table-body table-consumo">
		        <thead>
		            <tr>
		            	<th>Usuário</th>
		            	<th>Nome</th>
		            	<th style="width: 100px;">nome</th>
		            </tr>
		        </thead>
		        <tbody>
		            <tr tabindex="0" ng-repeat="iten in vm.Envolvidos.dados">
		                <td auto-title >@{{ iten.USUARIO }}</td>
		                <td auto-title >@{{ iten.NOME    }}</td>
		                <td auto-title >
		                	<button 
								type="button" 
								class="btn btn-sm btn-danger" 
								ng-click="vm.Envolvidos.excluir(iten)">
								<span class="glyphicon glyphicon-trash"></span>
								Remover
							</button>	
		                </td>
		            </tr>               
		        </tbody>
		    </table>
		</div>
	</div>

@overwrite