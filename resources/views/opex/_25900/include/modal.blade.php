	<div id="modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title TGRADE" id="myModalLabel">Parâmetros de Consulta</h4>
                    <br>
				</div>
				<div class="modal-body">
                    
                    {{-- Estabelecimento --}}
                    @include('admin._11020.include.listar', [
                        'required'			=> 'required',
                        'autofocus'			=> 'autofocus',
                        'opcao_selec'		=> 'true'
                    ])
                    
                    @include('helper.include.view.edit-data',['CLASSE'  => 'data-indicador'])
                        
                    @include('opex._25900.include.area',
                        [
                          'obj_ret'           => ['MASC','DESCRICAO'],
                          'campos_imputs'     => [['_area_id','ID'],['_grupo_id','GRUPO_ID']],
                          'class1'            => 'input-medio',
                          'class2'            => 'consulta_area_grup',
                          'required'			=> 'required'
                        ]
                    )
                    
                    @include('opex._25900.include.perspectiva',
                        [
                          'obj_ret'           => ['MASC','DESCRICAO'],
                          'campos_imputs'     => [['_pespectiva_id','ID'],['_grupo_id','GRUPO_ID']],
                          'class1'            => 'input-medio perspectiva-bsc',
                          'class2'            => 'consulta_perspectiva_grup',
                          'no_script'         => true,
                          'required'			=> 'required'
                        ]
                    )
                    
                    @include('opex._25900.include.setor',
                        [
                          'obj_ret'           => ['MASC','DESCRICAO'],
                          'campos_imputs'     => [['_setor_id','ID'],['_grupo_id','GRUPO_ID'],['_setor_descricao','DESCRICAO']],
                          'class1'            => 'input-medio setor-bsc',
                          'class2'            => 'consulta_setor_grup',
                          'no_script'         => true,
                          'required'			=> 'required'
                        ]
                    )
                    
                    @include('opex._25900.include.grupossetor',
                        [
                          'obj_ret'           => ['MASC','DESCRICAO'],
                          'campos_imputs'     => [['_setor_id','ID'],['_grupo_id','ID'],['_setor_descricao','DESCRICAO']],
                          'class1'            => 'input-medio',
                          'class2'            => 'consulta_grupo_grup',
                          'no_script'         => true,
                          'required'			=> 'required'
                        ]
                    )
                    
                    <div class="empresas-selec">
                        <div class="panel panel-primary">
                            <div class="panel-heading">GP selecionadas:</div>
                            <div class="panel-body">
                                <div class="titulo-lista">
                                    <span class="lista_sel_id">ID</span>
                                    <span class="lista_sel_id">DESCRICAO</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default fechar-modal" data-dismiss="modal"><span class="glyphicon glyphicon-chevron-left"></span>Voltar</button>
                    <button type="button" class="btn btn btn-primary filtrar-indicador"><span class="glyphicon glyphicon-filter"></span> Filtrar</button>
				</div>
			</div>
		</div>
	</div>
