<div id="programacao-filtro" class="table-filter collapse in" aria-expanded="true">   
    <form class="form-inline" ng-submit="vm.filtrar()">    
        
        {{-- Estabelecimento --}}
        @include('admin._11020.include.listar', [
            'required'			=> 'required',
            'autofocus'			=> 'autofocus',
            'opcao_selec'		=> 'true',
            'model'             => 'vm.filtro.estabelecimento_id'
        ])

        {{-- Agrupamento --}}
        @include('helper.include.view.consulta',
            [
                'label_descricao'   => 'Agrupamento:',
                'obj_consulta'      => 'Ppcp/include/_22110-agrupamento',
                'obj_ret'           => ['ID','DESCRICAO'],
                'campos_sql'        => ['ID','DESCRICAO','DATA_FINAL','PERFIL'],
                'campos_imputs'     => [
                                            ['_agrupamento_id',       'ID'       ,'','vm.filtro.agrupamento_id'],
                                            ['_agrupamento_descricao','DESCRICAO','','vm.filtro.agrupamento_descricao'],
                                            ['_pedido_perfil',        'PERFIL'   ,'','vm.filtro.pedido_perfil']
                                        ],
                'filtro_sql'        => [
                                          ['STATUS','1']
                                       ],
                'campos_tabela'     => [['ID','50'],['DESCRICAO','150'],['LOCALIZACAO_DESCRICAO','200'],['DATA_INICIAL','100'],['DATA_FINAL','100']],
                'campos_titulo'     => ['ID','DESCRIÇÃO','LOCALIZAÇÃO','DATA INICIAL','DATA FINAL'],
                'class1'            => 'input-medio',
                'required'		    => 'required'
            ]
        )

        {{-- Familia --}}
        @include('helper.include.view.consulta',
            [
                'label_descricao'   => 'Família:',
                'obj_consulta'      => 'Produto/include/_27010-familia',
                'obj_ret'           => ['ID','DESCRICAO'],
                'campos_sql'        => ['ID','DESCRICAO','FAMILIA_ID_MP'],
                'campos_imputs'     => [
                                            ['_familia_id',       'ID',           '','vm.filtro.familia_id'],
                                            ['_familia_descricao','DESCRICAO',    '','vm.filtro.familia_descricao'],
                                            ['_familia_mp',       'FAMILIA_ID_MP','','vm.filtro.familia_id_mp']],
                'filtro_sql'        => [
                                        ['STATUS','1'],       /* {{ Família Ativa   }} */
                                        ['TIPOPRODUTO_ID',2], /* {{ Produto Acabado }} */
                                       ],
                'campos_tabela'     => [['ID','80'],['DESCRICAO','200']],
                'campos_titulo'     => ['ID','DESCRIÇÃO'],
                'class1'            => 'input-medio-extra',
                'required'		    => 'required',
                'no_script'         => true
            ]
        )

        <div class="form-group">
            <label title="Data para produção da remessa">Data Remessa:</label>
            <div class="input-group">
                <input type="date" ng-model="vm.filtro.data_remessa" toDate id="data-prod" class="form-control" required />
                <button type="button" class="input-group-addon btn-filtro" tabindex="-1">
                    <span class="fa fa-close"></span>
                </button>
            </div>
        </div>

        <div class="form-group">
            <label title="Data para produção da remessa">Data Disponibilidade:</label>
            <div class="input-group">
                <input type="date" ng-model="vm.filtro.data_disponibilidade" toDate id="data-prod" class="form-control" required />
                <button type="button" class="input-group-addon btn-filtro" tabindex="-1">
                    <span class="fa fa-close"></span>
                </button>
            </div>
        </div>
        
        <div class="form-group">
            <label for="tonalidade">Tonalidade:</label>
            <select name="tonalidade" id="tonalidade" ng-model="vm.filtro.tonalidade" required ng-init="vm.filtro.tonalidade='%'">
                <option value="%">Todas</option>
              <option value="C">Claro</option>
              <option value="E">Escuro</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="tipo">Tipo Remessa:</label>
            <select name="tonalidade" id="tipo" ng-model="vm.filtro.tipo" required ng-init="vm.filtro.tipo='1'">
                <option value="1">Normal</option>
              <option value="2">Vip</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="amostra">Amostra:</label>
            <select name="amostra" id="tonalidade" ng-model="vm.filtro.amostra" required ng-init="vm.filtro.amostra='0'">
                <option value="0">Não</option>
              <option value="1">Sim</option>
            </select>
        </div>
            
        <button type="submit" class="btn btn-xs btn-primary btn-filtrar btn-table-filter" data-hotkey="alt+f">
            <span class="glyphicon glyphicon-filter"></span>
            {{ Lang::get('master.filtrar') }}
        </button>
    </form>
</div>

<!--<button type="button" class="btn btn-xs btn-default" id="filtrar-toggle" data-toggle="collapse" data-target="#programacao-filtro" aria-expanded="true" aria-controls="programacao-filtro">
    Filtro
    <span class="caret"></span>
</button>  -->