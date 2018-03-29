@extends('helper.include.view.modal', ['id' => 'modal-materia', 'class_size' => 'modal-full'])

@section('modal-start')
    <form class="form-inline" name="confirmarSaida" ng-submit="vm.Gp.consultar()">
@overwrite

@section('modal-header-left')

	<h4 class="modal-title">
		Matéria-Prima
	</h4>

@overwrite

@section('modal-header-right')
    <button type="button" class="btn btn-default  btn-cancelar" data-dismiss="modal" data-hotkey="esc">
        <span class="glyphicon glyphicon-chevron-left"></span>
        Voltar
    </button>
@overwrite

@section('modal-body')

    <div class="item-ficha">
        
        @include('custo._31010.info_detalhamento')
        
        @php /*
        <div class="corpo-2">
            <div class="table-ec">
                <div class="scroll-table">
                    <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
                        <thead>
                            <tr>
                                <th title="">
                                    <button type="button" class="btn btn-default btn-xs" ng-click="vm.Item.Ficha.AdicionaProduto(null)">
                                        <span class="glyphicon glyphicon-plus"></span>
                                        Adicionar
                                    </button>   
                                </th>
                                <th title="">-</th>
                                <th title="Nível na ficha técnica">Prod. ID</th>
                                <th title="Produto">Produto</th>
                                <th title="Produto">ICMS / Valor</th>
                                <th title="Consumo">Consumo</th>
                                <th title="Consumo">Custo Médio</th>
                                <th  class="left-text" title="Custo do Consumo da Matéria-prima">C. Cons. Matéria-prima</th>
                                <th  class="left-text" title="Custo Total do Consumo da Matéria-prima">C. Tot. Cons. Matéria-prima</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr ng-repeat-start="iten in vm.Item.Ficha.ITENS"
                                tabindex="-1"     
                                class="tr-fixed-1 pai_@{{iten.PRODUTO_CONSUMO}} nivel_@{{iten.NIVEL}} tag_@{{iten.PRODUTO_CONSUMO}}_@{{iten.NIVEL}}"
                                ng-if="iten.NIVEL == 0"
                                ng-class="{totalizador: iten.TOTALIZADOR == 1}"
                                >
                                <td auto-title ng-if="iten.TOTALIZADOR == 0">
                                    <button type="button" class="btn btn-default btn-xs" ng-click="vm.Item.Ficha.AdicionaProduto(iten)">
                                        <span class="glyphicon glyphicon-plus"></span>
                                        Adicionar
                                    </button>   
                                </td>
                                <td auto-title ng-if="iten.TOTALIZADOR == 0">
                                    <button type="button" class="btn btn-default btn-xs" ng-click="vm.Item.Ficha.RemoveProduto(iten)">
                                        <span class="glyphicon glyphicon-minus"></span>
                                        Remover
                                    </button>   
                                </td>
                                <td auto-title ng-click="vm.Item.Ficha.MontarFilhos(iten,0)" ng-if="iten.TOTALIZADOR == 1" class="left-text"></td>
                                <td auto-title ng-click="vm.Item.Ficha.MontarFilhos(iten,0)" ng-if="iten.TOTALIZADOR == 1" class="left-text"></td>

                                <td auto-title ng-click="vm.Item.Ficha.MontarFilhos(iten,0)" >@{{iten.PRODUTO_CONSUMO}}</td>
                                <td auto-title ng-click="vm.Item.Ficha.MontarFilhos(iten,0)" >@{{iten.PRODUTO_DESCRICAO}}</td>
                                <td auto-title ng-click="vm.Item.Ficha.MontarFilhos(iten,0)" class="left-text" ng-if="iten.ICMS  > 0 || iten.VLR_ICMS2 > 0">@{{iten.ICMS}}% / R$ @{{iten.VLR_ICMS2 | number:5}}</td>
                                <td auto-title ng-click="vm.Item.Ficha.MontarFilhos(iten,0)" ng-if="iten.ICMS <= 0 && iten.VLR_ICMS2 <= 0"></td>
                                <td auto-title ng-click="vm.Item.Ficha.MontarFilhos(iten,0)" ng-if="iten.TOTALIZADOR == 1" class="left-text"></td>
                                <td auto-title ng-click="vm.Item.Ficha.MontarFilhos(iten,0)" ng-if="iten.TOTALIZADOR == 1" class="left-text"></td>
                                <td auto-title ng-click="vm.Item.Ficha.MontarFilhos(iten,0)" ng-if="iten.TOTALIZADOR == 0" class="left-text">@{{iten.CONSUMO | number:6}} @{{iten.UNIDADEMEDIDA_SIGLA}}</td>
                                <td auto-title ng-click="vm.Item.Ficha.MontarFilhos(iten,0)" ng-if="iten.TOTALIZADOR == 0" class="left-text">R$ @{{iten.CUSTO_MEDIO | number:6}}</td>
                                <td auto-title ng-click="vm.Item.Ficha.MontarFilhos(iten,0)" class="left-text">R$ @{{iten.CUSTO2 | number:6}}</td>
                                <td auto-title ng-click="vm.Item.Ficha.MontarFilhos(iten,0)" class="left-text">R$ @{{iten.TOTAL2 | number:2}}</td>
                            </tr>
                            
                            <tr ng-if="iten.NIVEL == 0" style="display: none;" class="_pai_0_@{{iten.PRODUTO_CONSUMO}} tag_@{{iten.PRODUTO_CONSUMO}}_@{{iten.NIVEL}}"></tr>
                            <tr ng-repeat-end ng-if="false"></tr>
 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @php */

        <div class="corpo-2">
            <div class="table-ec">
                <div class="scroll-table">
                    <table class="table table-striped table-bordered table-hover tabela-itens-caso table-body table-lc table-lc-body table-consumo">
                        <thead>
                            <tr>
                                <th title="">
                                    @php /*
                                    <button type="button" class="btn btn-default btn-xs" ng-click="vm.Item.Ficha.AdicionaProduto(null)">
                                        <span class="glyphicon glyphicon-plus"></span>
                                        Adicionar
                                    </button>
                                    @php */   
                                </th>
                                <th title=""></th>
                                <th title="Nível na ficha técnica">Prod. ID</th>
                                <th title="Produto">Produto</th>
                                <th title="Produto">ICMS / Valor</th>
                                <th title="Consumo">Consumo</th>
                                <th title="Consumo">Custo Médio</th>
                                <th  class="left-text" title="Custo do Consumo da Matéria-prima">C. Cons. Matéria-prima</th>
                                <th  class="left-text" title="Custo Total do Consumo da Matéria-prima">C. Tot. Cons. Matéria-prima</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr ng-repeat-start="iten in vm.Item.Ficha.LISTA_MATERIA"
                                tabindex="-1"     
                                class="tr-fixed-1 pai_@{{iten.PRODUTO_CONSUMO}} nivel_@{{iten.NIVEL}} tag_@{{iten.PRODUTO_CONSUMO}}_@{{iten.NIVEL}}"
                                ng-class="{totalizador: iten.TOTALIZADOR == 1}"
                                ng-click="iten.ABERTO = iten.ABERTO == true ? false : true"
                                >
                                <td auto-title ng-if="iten.TOTALIZADOR == 0" >  
                                    <button type="button" class="btn btn-default btn-xs" ng-click="vm.Item.Ficha.TrocarProduto(iten)">
                                        <span class="glyphicon glyphicon-refresh"></span>
                                        Trocar
                                    </button>   
                                </td>

                                <td auto-title >
                                    <span ng-if="iten.SUB_ITENS.length > 0 && iten.ABERTO == false" class="glyphicon glyphicon-plus"></span>
                                    <span ng-if="iten.SUB_ITENS.length > 0 && iten.ABERTO == true"  class="glyphicon glyphicon-minus"></span>
                                    <span ng-if="iten.SUB_ITENS.length == 0"></span>
                                </td>

                                <td auto-title  >@{{iten.PRODUTO_CONSUMO}}</td>
                                <td auto-title  >@{{iten.PRODUTO_DESCRICAO}}</td>
                                <td auto-title  class="left-text" ng-if="iten.ICMS  > 0 || iten.VLR_ICMS2 > 0">@{{iten.ICMS}}% / R$ @{{iten.VLR_ICMS2 | number:5}}</td>
                                <td auto-title  ng-if="iten.ICMS <= 0 && iten.VLR_ICMS2 <= 0"></td>
                                <td auto-title  ng-if="iten.TOTALIZADOR == 1" class="left-text"></td>
                                <td auto-title  ng-if="iten.TOTALIZADOR == 1" class="left-text"></td>
                                <td auto-title  ng-if="iten.TOTALIZADOR == 0" class="left-text">@{{iten.CONSUMO | number:6}} @{{iten.UNIDADEMEDIDA_SIGLA}}</td>
                                <td auto-title  ng-if="iten.TOTALIZADOR == 0" class="left-text">R$ @{{iten.CUSTO_MEDIO | number:6}}</td>
                                <td auto-title  class="left-text">R$ @{{(iten.CUSTO2 + (iten.VLR_ICMS2 * iten.CONSUMO)) | number:6}}</td>
                                <td auto-title  class="left-text">R$ @{{iten.TOTAL2  + ((iten.VLR_ICMS2 * iten.CONSUMO) * vm.Item.Quantidade) | number:2}}</td>
                            </tr>

                            <tr ng-repeat-start="sub_itens in iten.SUB_ITENS"
                                tabindex="-1"  
                                ng-if="iten.ABERTO == true"    
                                class="tr-fixed-1 pai_@{{sub_itens.PRODUTO_CONSUMO}} nivel_@{{sub_itens.NIVEL}} tag_@{{sub_itens.PRODUTO_CONSUMO}}_@{{sub_itens.NIVEL}}"
                                ng-class="{totalizador: sub_itens.TOTALIZADOR == 1}"
                                ng-click="sub_itens.ABERTO = sub_itens.ABERTO == true ? false : true"
                                >
                                <td auto-title></td>

                                <td auto-title >
                                    <span ng-if="sub_itens.SUB_ITENS.length > 0 && sub_itens.ABERTO == false" class="glyphicon glyphicon-plus"></span>
                                    <span ng-if="sub_itens.SUB_ITENS.length > 0 && sub_itens.ABERTO == true"  class="glyphicon glyphicon-minus"></span>
                                    <span ng-if="sub_itens.SUB_ITENS.length == 0"></span>
                                </td>

                                <td auto-title  >@{{sub_itens.PRODUTO_CONSUMO}}</td>
                                <td auto-title  >@{{sub_itens.PRODUTO_DESCRICAO}}</td>
                                <td auto-title  class="left-text" ng-if="sub_itens.ICMS  > 0 || sub_itens.VLR_ICMS2 > 0">@{{sub_itens.ICMS}}% / R$ @{{sub_itens.VLR_ICMS2 | number:5}}</td>
                                <td auto-title  ng-if="sub_itens.ICMS <= 0 && sub_itens.VLR_ICMS2 <= 0"></td>
                                <td auto-title  ng-if="sub_itens.TOTALIZADOR == 1" class="left-text"></td>
                                <td auto-title  ng-if="sub_itens.TOTALIZADOR == 1" class="left-text"></td>
                                <td auto-title  ng-if="sub_itens.TOTALIZADOR == 0" class="left-text">@{{sub_itens.CONSUMO | number:6}} @{{sub_itens.UNIDADEMEDIDA_SIGLA}}</td>
                                <td auto-title  ng-if="sub_itens.TOTALIZADOR == 0" class="left-text">R$ @{{sub_itens.CUSTO_MEDIO | number:6}}</td>
                                <td auto-title  class="left-text">R$ @{{sub_itens.CUSTO2 + ( sub_itens.VLR_ICMS2 * sub_itens.CONSUMO) | number:6}}</td>
                                <td auto-title  class="left-text">R$ @{{sub_itens.TOTAL2 + ((sub_itens.VLR_ICMS2 * sub_itens.CONSUMO) * vm.Item.Quantidade) | number:2}}</td>
                            </tr>

                            <tr ng-repeat-start="sub_itens2 in sub_itens.SUB_ITENS"
                                tabindex="-1"
                                ng-if="sub_itens.ABERTO == true && iten.ABERTO == true"      
                                class="tr-fixed-1 pai_@{{sub_itens2.PRODUTO_CONSUMO}} nivel_@{{sub_itens2.NIVEL}} tag_@{{sub_itens2.PRODUTO_CONSUMO}}_@{{sub_itens2.NIVEL}}"
                                ng-class="{totalizador: sub_itens2.TOTALIZADOR == 1}"
                                ng-click="sub_itens2.ABERTO = sub_itens2.ABERTO == true ? false : true"
                                >
                                <td auto-title></td>

                                <td auto-title >
                                    <span ng-if="sub_itens2.SUB_ITENS.length > 0 && sub_itens2.ABERTO == false" class="glyphicon glyphicon-plus"></span>
                                    <span ng-if="sub_itens2.SUB_ITENS.length > 0 && sub_itens2.ABERTO == true"  class="glyphicon glyphicon-minus"></span>
                                    <span ng-if="sub_itens2.SUB_ITENS.length == 0"></span>
                                </td>

                                <td auto-title  >@{{sub_itens2.PRODUTO_CONSUMO}}</td>
                                <td auto-title  >@{{sub_itens2.PRODUTO_DESCRICAO}}</td>
                                <td auto-title  class="left-text" ng-if="sub_itens2.ICMS  > 0 || sub_itens2.VLR_ICMS2 > 0">@{{sub_itens2.ICMS}}% / R$ @{{sub_itens2.VLR_ICMS2 | number:5}}</td>
                                <td auto-title  ng-if="sub_itens2.ICMS <= 0 && sub_itens2.VLR_ICMS2 <= 0"></td>
                                <td auto-title  ng-if="sub_itens2.TOTALIZADOR == 1" class="left-text"></td>
                                <td auto-title  ng-if="sub_itens2.TOTALIZADOR == 1" class="left-text"></td>
                                <td auto-title  ng-if="sub_itens2.TOTALIZADOR == 0" class="left-text">@{{sub_itens2.CONSUMO | number:6}} @{{sub_itens2.UNIDADEMEDIDA_SIGLA}}</td>
                                <td auto-title  ng-if="sub_itens2.TOTALIZADOR == 0" class="left-text">R$ @{{sub_itens2.CUSTO_MEDIO | number:6}}</td>
                                <td auto-title  class="left-text">R$ @{{sub_itens2.CUSTO2 + ( sub_itens2.VLR_ICMS2 * sub_itens2.CONSUMO) | number:6}}</td>
                                <td auto-title  class="left-text">R$ @{{sub_itens2.TOTAL2 + ((sub_itens2.VLR_ICMS2 * sub_itens2.CONSUMO) * vm.Item.Quantidade) | number:2}}</td>
                            </tr>

                            <tr ng-repeat="sub_itens3 in sub_itens2.SUB_ITENS"
                                tabindex="-1"
                                ng-if="sub_itens2.ABERTO == true && sub_itens.ABERTO == true && iten.ABERTO == true"     
                                class="tr-fixed-1 pai_@{{sub_itens3.PRODUTO_CONSUMO}} nivel_@{{sub_itens3.NIVEL}} tag_@{{sub_itens3.PRODUTO_CONSUMO}}_@{{sub_itens3.NIVEL}}"
                                ng-class="{totalizador: sub_itens3.TOTALIZADOR == 1}"
                                ng-click="sub_itens3.ABERTO = sub_itens3.ABERTO == true ? false : true"
                                >
                                <td auto-title></td>

                                <td auto-title >
                                    <span ng-if="sub_itens3.SUB_ITENS.length > 0 && sub_itens3.ABERTO == false" class="glyphicon glyphicon-plus"></span>
                                    <span ng-if="sub_itens3.SUB_ITENS.length > 0 && sub_itens3.ABERTO == true"  class="glyphicon glyphicon-minus"></span>
                                    <span ng-if="sub_itens3.SUB_ITENS.length == 0"></span>
                                </td>

                                <td auto-title  >@{{sub_itens3.PRODUTO_CONSUMO}}</td>
                                <td auto-title  >@{{sub_itens3.PRODUTO_DESCRICAO}}</td>
                                <td auto-title  class="left-text" ng-if="sub_itens3.ICMS  > 0 || sub_itens3.VLR_ICMS2 > 0">@{{sub_itens3.ICMS}}% / R$ @{{sub_itens3.VLR_ICMS2 | number:5}}</td>
                                <td auto-title  ng-if="sub_itens3.ICMS <= 0 && sub_itens3.VLR_ICMS2 <= 0"></td>
                                <td auto-title  ng-if="sub_itens3.TOTALIZADOR == 1" class="left-text"></td>
                                <td auto-title  ng-if="sub_itens3.TOTALIZADOR == 1" class="left-text"></td>
                                <td auto-title  ng-if="sub_itens3.TOTALIZADOR == 0" class="left-text">@{{sub_itens3.CONSUMO | number:6}} @{{sub_itens3.UNIDADEMEDIDA_SIGLA}}</td>
                                <td auto-title  ng-if="sub_itens3.TOTALIZADOR == 0" class="left-text">R$ @{{sub_itens3.CUSTO_MEDIO | number:6}}</td>
                                <td auto-title  class="left-text">R$ @{{sub_itens3.CUSTO2 + ( sub_itens3.VLR_ICMS2 * sub_itens3.CONSUMO) | number:6}}</td>
                                <td auto-title  class="left-text">R$ @{{sub_itens3.TOTAL2 + ((sub_itens3.VLR_ICMS2 * sub_itens3.CONSUMO) * vm.Item.Quantidade) | number:2}}</td>
                            </tr>

                            <tr 
                                tabindex="-1"
                                ng-if="sub_itens2.ABERTO == true && sub_itens.ABERTO == true && iten.ABERTO == true && sub_itens2.SUB_ITENS.length > 0"     
                                class="totalizador nivel_3"
                                >
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td  class="left-text">R$ @{{sub_itens2.TOTAL_CUSTO + ( sub_itens2.TOTAL_ICMS * sub_itens2.CONSUMO) | number:6}}</td>
                                <td  class="left-text">R$ @{{sub_itens2.TOTAL_GERAL + ((sub_itens2.TOTAL_ICMS * sub_itens2.CONSUMO) * vm.Item.Quantidade) | number:2}}</td>
                            </tr>

                            <tr ng-repeat-end ng-if="false"></tr>

                            <tr 
                                tabindex="-1"
                                ng-if="sub_itens.ABERTO == true && iten.ABERTO == true && sub_itens.SUB_ITENS.length > 0"     
                                class="totalizador nivel_2"
                                >
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td  class="left-text">R$ @{{sub_itens.TOTAL_CUSTO + ( sub_itens.TOTAL_ICMS * sub_itens.CONSUMO) | number:6}}</td>
                                <td  class="left-text">R$ @{{sub_itens.TOTAL_GERAL + ((sub_itens.TOTAL_ICMS * sub_itens.CONSUMO) * vm.Item.Quantidade) | number:2}}</td>
                            </tr>

                            <tr ng-repeat-end ng-if="false"></tr>

                            <tr 
                                tabindex="-1"
                                ng-if="iten.ABERTO == true && iten.SUB_ITENS.length > 0"     
                                class="totalizador nivel_1"
                                >
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td  class="left-text">R$ @{{iten.TOTAL_CUSTO + ( iten.TOTAL_ICMS * iten.CONSUMO) | number:6}}</td>
                                <td  class="left-text">R$ @{{iten.TOTAL_GERAL + ((iten.TOTAL_ICMS * iten.CONSUMO) * vm.Item.Quantidade) | number:2}}</td>
                            </tr>

                            <tr ng-repeat-end ng-if="false"></tr>

                            <tr 
                                tabindex="-1"
                                ng-if="vm.Item.Ficha.LISTA_MATERIA.length > 0"     
                                class="totalizador nivel_0"
                                >
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Total</td>
                                <td class="left-text">R$ @{{vm.Item.Ficha.LISTA_MATERIA.TOTAL_ICMS | number:6}}</td>
                                <td></td>
                                <td></td>
                                <td class="left-text">R$ @{{(vm.Item.Ficha.LISTA_MATERIA.TOTAL_CUSTO + vm.Item.Ficha.LISTA_MATERIA.TOTAL_ICMS) | number:6}}</td>
                                <td class="left-text">R$ @{{(vm.Item.Ficha.LISTA_MATERIA.TOTAL_GERAL + (vm.Item.Ficha.LISTA_MATERIA.TOTAL_ICMS * vm.Item.Quantidade)) | number:2}}</td>
                            </tr>
 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>      

    </div>



@overwrite

@section('modal-end')
    </form>
@overwrite