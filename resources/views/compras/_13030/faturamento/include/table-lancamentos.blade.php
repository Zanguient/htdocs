<fieldset>
    <legend>Lançamentos</legend>
    <div class="form-group">
        <!--<section class="tabela">-->        
            <table class="table table-hover table-bordered table-striped table-faturamento">
                <thead>
                <tr>
                    <th class="t-center t-min-big-normal t-max-big-normal">Estabelecimento</th>
                    <th class="t-center t-min-medium-extra t-max-medium-extra">Período</th>
                    <th class="t-center t-min-medium t-max-medium">Valor</th>
                    <th class="t-center t-min-medium-normal t-max-medium-normal">Data/Hora Inclusão</th>       
                    <th class="t-center t-min-small-short t-max-small-short">Ações</th>          
                </tr>
                </thead>
                <tbody>
                @foreach ( $faturamentos as $faturamento )
                <tr data-id="{{ floatval($faturamento->ID) }}">
                    <td class="t-big-normal" field-js="alterar-input">
                        <span class="span" name="estab">{{ $faturamento->ESTABELECIMENTO_ID . ' - ' . $faturamento->ESTABELECIMENTO_DESCRICAO }}</span>
                        
                        @include('admin._11020.include.listar',[
                            'no_script'        =>  true,
                            'form_group'       =>  false,
                            'class'            =>  't-input input',
                            'style'            =>  'display:none;',
                            'estab_cadastrado' =>  $faturamento->ESTABELECIMENTO_ID,
                        ])   

                    </td>
                    <td class="t-medium-extra" field-js="alterar-input">
                        <span class="span" name="data">{{ $faturamento->DATA_DESCRICAO }}</span>
                        @include('helper.include.view.input-mes-ano',[
                            'form_group'   =>  false,
                            'mes_class'    =>  't-input input',
                            'ano_class'    =>  't-input input',
                            'mes_style'    =>  'width:58%;display:none;',
                            'ano_style'    =>  'width:43%;display:none;',
                            'mes_selected' =>  $faturamento->MES,
                            'ano_selected' =>  $faturamento->ANO,
                        ])  
                    </td>
                    <td class="t-right t-medium" field-js="alterar-input">
                        <span class="span" name="valor">R$ {{ number_format($faturamento->VALOR, 4, ',', '.') }}</span>
                        <input type="text" name="valor" class="form-control t-input input input-text-right " value="{{ number_format($faturamento->VALOR, 4, ',', '') }}" style="display: none;">
                    </td>
                    <td class="t-center t-medium-normal" field-js="alterar-disabled">
                        <span>{{ date_format(date_create($faturamento->DATAHORA), 'd/m/Y H:i:s') }}</span>
                    </td>
                    <td class="t-center t-btn">
                        <button 
                            type="button" 
                            class="btn btn-primary btn-sm btn-alterar" 
                            title="Alterar"
                        >
                            <span class="glyphicon glyphicon-edit"></span>
                        </button>
                        <button 
                            type="button" 
                            class="btn btn-success btn-sm btn-confirm" 
                            title="Gravar" 
                            style="display: none;"
                        >
                            <span class="glyphicon glyphicon-ok"></span>
                        </button>
                        <button 
                            type="button" 
                            class="btn btn-danger btn-sm btn-excluir" 
                            title="Excluir"
                        >
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                        <button 
                            type="button" 
                            class="btn btn-danger btn-sm btn-cancel" 
                            title="Cancelar" 
                            style="display: none;"
                        >
                            <span class="glyphicon glyphicon-ban-circle"></span>
                        </button>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        <!--</section>-->
    </div>
</fieldset>