@extends('helper.include.view.modal', ['id' => 'modal-defeito', 'class_size' => 'modal-lg'])

    @section('modal-header-left')

    <h4 class="modal-title">
        Defeito - [@{{vm.DADOS_DEFEITO.data_inicio}} - @{{vm.DADOS_DEFEITO.data_fim}}] Def.:@{{vm.DADOS_DEFEITO.filtro.defeito}} Prod:@{{vm.DADOS_DEFEITO.filtro.producao}}
    </h4>

    @overwrite

    @section('modal-header-right')

        <button type="button" class="btn btn-default btn-fechar-modal btn-voltar" data-dismiss="modal" data-hotkey="esc">
          <span class="glyphicon glyphicon-chevron-left"></span> Voltar
        </button>

    @overwrite

    @section('modal-body')

        <div class="conteiner-filtro">

            <div class="filtro-item" ng-repeat="iten in vm.DADOS.DEFEITO.FILTRO">
                <div class="filtro-campo">@{{iten.TIPO}}:</div>
                <div class="filtro-valor">@{{iten.DESC}}</div>
                <div class="filtro-fechar" ng-click="vm.Acao.DeletarFiltro(iten)">x</div>
            </div>
                                                                                        
            <button class="filtro-btn btn btn-sm btn-primary js-filter-btn"
                    ng-click="vm.Acao.filtrar()"
                    ng-if="vm.DADOS.DEFEITO.FILTRO.length > 0">
                    <span class="glyphicon glyphicon-filter"></span>
                    Filtrar
            </button>
            
            <div class="ajuda-filtro"
                 ng-if="vm.DADOS.DEFEITO.FILTRO.length == 0"
            ><div>Dé um duplo click em um dos itens das tabelas para criar uma filtragem</div></div>
                
        </div>

        <fieldset class="tab-container">
    
        <ul id="tab" class="nav nav-tabs acoes" role="tablist"> 
            <li role="presentation" class="active tab-detalhamento">
                <a href="#tab-linha-container" id="tab-linha" role="tab" data-toggle="tab" aria-controls="tab-linha-container" aria-expanded="false">
                    Agrupamento por Linha
                </a>
            </li>
            <li role="presentation" class="tab-detalhamento">
                <a href="#tab-defeito-container" id="tab-defeito" role="tab" data-toggle="tab" aria-controls="tab-defeito-container" aria-expanded="false">
                    Agrupamento por Defeito
                </a>
            </li>
            <li role="presentation" class="tab-detalhamento">
                <a href="#tab-setor-defeito-container" id="tab-setor-defeito" role="tab" data-toggle="tab" aria-controls="tab-setor-defeito-container" aria-expanded="false">
                    Agrupamento por Setor do Defeito
                </a>
            </li>
            <li role="presentation" class="tab-detalhamento">
                <a href="#tab-gp-container" id="tab-gp" role="tab" data-toggle="tab" aria-controls="tab-gp-container" aria-expanded="false">
                    Agrupamento por GP
                </a>
            </li>
            <li role="presentation" class="tab-detalhamento">
                <a href="#tab-cor-container" id="tab-cor" role="tab" data-toggle="tab" aria-controls="tab-cor-container" aria-expanded="false">
                    Agrupamento por Cor
                </a>
            </li>
            <li role="presentation" class="tab-detalhamento">
                <a href="#tab-densidade-container" id="tab-densidade" role="tab" data-toggle="tab" aria-controls="tab-densidade-container" aria-expanded="false">
                    Agrupamento por Densidade
                </a>
            </li>
            <li role="presentation" class="tab-detalhamento">
                <a href="#tab-espessura-container" id="tab-espessura" role="tab" data-toggle="tab" aria-controls="tab-espessura-container" aria-expanded="false">
                    Agrupamento por Espessura
                </a>
            </li>
            <li role="presentation" class="tab-detalhamento">
                <a href="#tab-modelo-container" id="tab-modelo" role="tab" data-toggle="tab" aria-controls="tab-modelo-container" aria-expanded="false">
                    Agrupamento por Modelo
                </a>
            </li>
            <li role="presentation" class="tab-detalhamento">
                <a href="#tab-perfil-container" id="tab-perfil" role="tab" data-toggle="tab" aria-controls="tab-perfil-container" aria-expanded="false">
                    Agrupamento por Perfil
                </a>
            </li>
            <li role="presentation" class="tab-tamanho">
                <a href="#tab-tamanho-container" id="tab-tamanho" role="tab" data-toggle="tab" aria-controls="tab-tamanho-container" aria-expanded="false">
                    Agrupamento por Tamanho
                </a>
            </li>
        </ul>

        <div role="tabpanel" class="tab-pane fade active in" id="tab-linha-container" aria-labelledby="tab-linha">
            @include('vendas._12050.show.tab',['TIPO' => 'LINHA', 'DESC' => 'Linha'])
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab-defeito-container" aria-labelledby="tab-defeito">
            @include('vendas._12050.show.tab',['TIPO' => 'DEFEITO', 'DESC' => 'Tipo Def.'])
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab-setor-defeito-container" aria-labelledby="tab-setor-defeito">
            @include('vendas._12050.show.tab',['TIPO' => 'DEFEITO_SETOR', 'DESC' => 'Setor Def.'])
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab-gp-container" aria-labelledby="tab-gp">
            @include('vendas._12050.show.tab',['TIPO' => 'GP', 'DESC' => 'GP'])
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab-cor-container" aria-labelledby="tab-cor">
            @include('vendas._12050.show.tab',['TIPO' => 'COR', 'DESC' => 'Cor'])
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab-densidade-container" aria-labelledby="tab-densidade">
            @include('vendas._12050.show.tab',['TIPO' => 'DENSIDADE', 'DESC' => 'Dencidade'])
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab-espessura-container" aria-labelledby="tab-espessura">
            @include('vendas._12050.show.tab',['TIPO' => 'ESPESSURA', 'DESC' => 'Espessura'])
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab-modelo-container" aria-labelledby="tab-modelo">
            @include('vendas._12050.show.tab',['TIPO' => 'MODELO', 'DESC' => 'Modelo'])
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab-perfil-container" aria-labelledby="tab-perfil">
            @include('vendas._12050.show.tab',['TIPO' => 'PERFIL', 'DESC' => 'Perfil'])
        </div>

        <div role="tabpanel" class="tab-pane fade" id="tab-tamanho-container" aria-labelledby="tab-tamanho">
            @include('vendas._12050.show.tab',['TIPO' => 'TAMANHO', 'DESC' => 'Tamanho'])
        </div>

    </fieldset>

    @overwrite



