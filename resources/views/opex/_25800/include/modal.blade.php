	<div id="modal-edit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title TGRADE" id="myModalLabel">Parâmetros de Consulta</h4>
				</div>
				<div class="modal-body">
                        
                    @include('helper.include.view.consulta',
                        [
                          'label_descricao'   => 'GP',
                          'obj_consulta'      => 'Helper/include/gp',
                          'obj_ret'           => ['MASC','DESC'],
                          'campos_imputs'     => [['_id_gp','CODE'],['_ccusto_gp','CCUSTO'],['_bsc_grupo_gp','BSC_GRUPO'],['_efic_gp','EFIC_MINIMA'],['_desc','DESC'],['_CD','CODE']],
                          'campos_sql'        => ['CODE','DESC','BSC_GRUPO','EFIC_MINIMA','CCUSTO'],
                          'filtro_sql'        => [['so_ativos','so_ativos'],['so_familia3','so_familia3'],['ordenar_por_desc','ordenar_por_desc'],['sql_para_indicador','sql_para_indicador']],
                          'campos_tabela'     => [['MASC','80'],['DESC','200']],
                          'campos_titulo'     => ['ID','DESCRIÇÃO'],
                          'class1'            => 'input-medio',
                          'class2'            => 'consulta_gp_grup'
                        ]
                    )
                    
                    @include('helper.include.view.consulta',
                        [
                          'label_descricao'   => 'Grupos GP',
                          'obj_consulta'      => 'Helper/include/grupogp',
                          'obj_ret'           => ['MASK','DESCRICAO'],
                          'campos_imputs'     => [['_id_gp','ID'],['_ccusto_gp','CCUSTO'],['_bsc_grupo_gp','BSC_GRUPO'],['_efic_gp','EFIC_MINIMA'],['_desc','DESCRICAO'],['_CD','CODIGO']],
                          'campos_sql'        => ['ID','DESCRICAO','BSC_GRUPO','EFIC_MINIMA','CODIGO','CCUSTO'],
                          'filtro_sql'        => ['so_ativos','so_familia3','ordenar_por_desc','sql_para_indicador'],
                          'campos_tabela'     => [['MASK','80'],['DESCRICAO','200']],
                          'campos_titulo'     => ['ID','DESCRICÃO'],
                          'class1'            => 'input-medio',
                          'class2'            => 'consulta_gp_grup2',
                          'no_script'         => true
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
                    
                    @if ($estab == '0')
                    
                        @include('admin/_11020/include/listar',[
                        'required' => true,
                        'estab_cadastrado'	=> 1
                        ])
                        
                    @endif
                    
                    @include('helper.include.view.edit-data',['CLASSE'  => 'data-indicador'])
                    
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default fechar-modal" data-dismiss="modal"><span class="glyphicon glyphicon-chevron-left"></span>Voltar</button>
                    <button type="button" class="btn btn btn-primary filtrar-indicador"><span class="glyphicon glyphicon-filter"></span> Filtrar</button>
				</div>
			</div>
		</div>
	</div>
