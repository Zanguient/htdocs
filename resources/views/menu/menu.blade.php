<div id="menu" class="scroll-dark fechado">
		
	<div class="menu-container" ng-controller="MenuCtrl as vm" ng-init="vm.LoadMenus()">
		<input type="text" name="username" style="display:none" value="fake input" /> 
		<div class="input-group">
            <input 
                type="text" 
                id="menu-filtro" 
                class="form-control" 
                placeholder="Pesquise..." 
                autocomplete="off" 
                ng-model="vm.filtrar_menu" 
                ng-init="vm.filtrar_menu = ''" 
                ng-change="vm.menu_grupo = ''; vm.DropdownMenu()"
                ng-keydown="vm.menuSelecionar($event)">
			<button 
                type="button" 
                id="btn-filtro-menu" 
                class="input-group-addon btn-filtro"
				title="Atualizar menus liberados"
                ng-click="vm.btnCarregarMenus()"><span class="fa fa-refresh"></span></button>
		</div>
		
		<div id="menu-filtro-resultado" class="scroll-light">
			<input type="hidden" name="url-base" id="url-base" value="{{ url('') }}" />{{-- usado pelo Ajax --}}
			<div id="menu-fechar" title="Fechar (Esc)"><span class="fa fa-close"></span></div>
			<div id="menu-filtro-titulo">Resultado da busca...</div>
            <div id="menu-filtro-itens">
                <ul class="nav" >
                    <li ng-repeat="menu in vm.menus_filtered = (vm.$storage.menus 
                    | filter: vm.FiltrarMenu 
                    | filter: vm.FiltrarGrupo 
                    | find: {
                        model : vm.menu_grupo,
                        fields : [
                            'GRUPO'
                        ]
                    }
                    | find: {
                        model : ( vm.filtrar_menu == '' && vm.menu_grupo == '' ) ? '*' : vm.filtrar_menu,
                        fields : [
                            'CONTROLE',
                            'DESCRICAO'
                        ]
                    })
                    track by $index">
					
                        <a href="{{ url('') }}/_@{{ menu.URL == '' ? menu.CONTROLE : menu.URL}}" ng-if="menu.URL.indexOf('://') == -1 && menu.REL!=1" class="tipo-@{{ menu.TIPO }}">@{{ menu.CONTROLE }} - @{{ menu.DESCRICAO }}</a>
                        <a href="@{{ menu.URL }}" ng-if="menu.URL.indexOf('://') != -1 && menu.REL!=1" target="_blank" class="tipo-@{{ menu.TIPO }}">@{{ menu.CONTROLE }} - @{{ menu.DESCRICAO }}</a>

						<a href="{{ url('') }}/_@{{ menu.CONTROLE_REL}}/@{{menu.ID}} " ng-if="menu.REL==1" class="tipo-@{{ menu.TIPO }}">@{{ menu.CONTROLE }} - @{{ menu.DESCRICAO }}</a>
						
                    </li>
                </ul>
            </div>
            <div class="menu-legenda">
                <div class="item-legenda">
                    <div class="cor1-legenda"></div>
                    <div class="desc-legenda">Cadastros/Operacional</div>
                </div>

                <div class="item-legenda">
                    <div class="cor3-legenda"></div>
                    <div class="desc-legenda">Relatórios</div>
                </div>
            </div>
		</div>
        <div id="menu-itens" ng-init="vm.menu_grupo = ''">
            <button disabled ng-disabled="vm.IndexOfAttr(vm.$storage.grupos,'GRUPO','ADM' ) == -1"  type="button" ng-click="vm.menu_grupo = 'ADM' ; vm.filtrar_menu = ''; vm.DropdownMenu(true); " grupo="ADM"  data-id="11" class="btn btn-default admin "><span>Admin. do Sistema</span></button>
	    </div>
    </div>
	
</div>