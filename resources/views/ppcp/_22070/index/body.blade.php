<div class="up-container">
@foreach ( $ups as $up )
    <div class="up-bloco" data-up="{{ $up->ID }}" data-up-descricao="{{ $up->DESCRICAO }}">
        <label>{{ Lang::get('master.up') }}: {{ $up->ID }} - {{ $up->DESCRICAO }}</label>
        <div class="estacao-container">
        @foreach ( $estacoes as $estacao )
            @if ( $estacao->UP_ID == $up->ID )
            <div class="estacao-bloco" data-estacao="{{ $estacao->ESTACAO }}" data-estacao-descricao="{{ $estacao->ESTACAO_DESCRICAO }}">
                <label>{{ Lang::get('master.estacao') }}: {{ $estacao->ESTACAO }} - {{ $estacao->ESTACAO_DESCRICAO }} / {{ Lang::get('master.perfil') }}: {{ $estacao->PERFIL_SKU_DESCRICAO }}</label>
                <div class="acoes-estacao">
                    <button type="button" class="btn btn-xs btn-default btn-reprogramar-para" title="{{ Lang::get($menu.'.incluir-title') }}" data-perfil="{{ trim($estacao->PERFIL_SKU) }}" disabled>
                        <span class="glyphicon glyphicon-calendar"></span> Reprogramar para...
                    </button>
                </div>
                
                <table class="table table-striped table-bordered table-hover estacao">
                    <thead>
                        <tr>
                            <th class="checkbox-talao"></th>
                            <th></th>
                            <th class="text-center ">{{ Lang::get($menu.'.remessa') }} - {{ Lang::get($menu.'.talao') }}</th>
                            <th class="text-center ">Dt. Rem.</th>
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
                            <td class="checkbox-talao"><input type="checkbox" name="talao">                                    </td>
                            <td class="t-status text-center status-{{ $talao->PROGRAMACAO_STATUS }}" title="{{ $talao->PROGRAMACAO_STATUS_DESCRICAO }}"></td>
                            <td class="text-center">{{ $talao->REMESSA }} - {{ $talao->REMESSA_TALAO_ID }}                     </td>
                            <td class="text-center">{{ date('d/m',strtotime($talao->REMESSA_DATA)) }}                     </td>
                            <td class="text-right ">{{ number_format($talao->DENSIDADE, 2, ',', '.') }}                        </td>
                            <td class="text-right ">{{ number_format($talao->ESPESSURA, 2, ',', '.') }}                        </td>
                            <td                    >{{ $talao->MODELO_ID }} - {{ $talao->MODELO_DESCRICAO }}                   </td>
                            <td class="text-right ">{{ number_format($talao->QUANTIDADE, 4, ',', '.') }}                       </td>
                            <td class="text-right ">{{ number_format($talao->TEMPO, 2, ',', '.') }}'                           </td>
                            <td class="text-center">{{ date_format(date_create($talao->DATAHORA_INICIO), 'd/m H:i') }}     </td>
                            <td class="text-center">{{ date_format(date_create($talao->DATAHORA_FIM), 'd/m H:i') }}        </td> 
                            
                            <input type="hidden" class="_data-programacao"   value="{{ date_format(date_create($talao->PROGRAMACAO_DATA), 'Y-m-d') }}" />
                            <input type="hidden" class="_data-inicio"        value="{{ date_format(date_create($talao->DATAHORA_INICIO), 'Y-m-d H:i:s') }}" data-data-inicial="{{ date("Y-m-d H:i:s",strtotime("$talao->DATAHORA_FIM + 1 minute")) }}" />
                            <input type="hidden" class="_talao-id"           value="{{ $talao->ID               }}"   />
                            <input type="hidden" class="_remessa-id"         value="{{ $talao->REMESSA_ID       }}"   />
                            <input type="hidden" class="_remessa-talao-id"   value="{{ $talao->REMESSA_TALAO_ID }}"   />
                            <input type="hidden" class="_programacao-id"     value="{{ $talao->PROGRAMACAO_ID }}"     />
                            <input type="hidden" class="_up-id"              value="{{ $talao->UP_ID }}"              />
                            <input type="hidden" class="_estacao"            value="{{ $talao->ESTACAO }}"            />
                            <input type="hidden" class="_estabelecimento-id" value="{{ $talao->ESTABELECIMENTO_ID }}" />
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