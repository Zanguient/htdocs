@php $i = 0;
@php $x = 0;

{{-- Nível C.Custo --}}
@foreach ($ret['ccustos'] as $ccusto) 

    @php $ccusto_valor  = "R$ ". number_format($ccusto->VALOR,  2, ',', '.')
    @php $ccusto_extra  = "R$ ". number_format($ccusto->EXTRA,  2, ',', '.')
    @php $ccusto_total  = "R$ ". number_format($ccusto->TOTAL,  2, ',', '.')
    @php $ccusto_outros = "R$ ". number_format($ccusto->OUTROS, 2, ',', '.')
    @php $ccusto_saldo  = "R$ ". number_format($ccusto->SALDO,  2, ',', '.')
    @php $ccusto_util   = $ccusto->CCUSTO <> 99999999999 ? "R$ ". number_format($ccusto->UTIL,   2, ',', '.')  : ''
    @php $ccusto_perc   = $ccusto->CCUSTO <> 99999999999 ? number_format($ccusto->PERC_UTIL, 2, ',', '.') .'%' : ''
    @php $ccusto_custo  = isset($ccusto->CUSTO_SETOR) ? number_format($ccusto->CUSTO_SETOR, 2, ',', '.') . '%' : ''

    @php $i++
    @php $negativo = floatval($ccusto->PERC_UTIL) > 100 ? 'negativo' : ''

    <div class="panel panel-default heading{{ $ccusto->CCUSTO }}avo">
    	<div class="panel-heading heading-ccusto {{ $negativo }} {{ ($ccusto->CCUSTO == 9999999999 ? 'total' : '') }} {{ ($ccusto->CCUSTO == 99999999999 ? 'faturado' : '') }} {{ $negativo }}" role="tab" id="heading{{ $ccusto->CCUSTO }}">
           <a role="button" data-toggle="collapse" href="#collapse{{ $ccusto->CCUSTO }}" aria-controls="collapse{{ $ccusto->CCUSTO }}">
               <div class="label">{{ $ccusto->CCUSTO_MASK }}</div>
               <div class="label limit-width">{{ $ccusto->CCUSTO_DESCRICAO }}</div>
               <div class="label">{{ $ccusto_valor  }}</div>
               <div class="label">{{ $ccusto_extra  }}</div>
               <div class="label">{{ $ccusto_total  }}</div>
               <div class="label">{{ $ccusto_outros }}</div>
               <div class="label">{{ $ccusto_util   }}</div>
               <div class="label">{{ $ccusto_perc   }}</div>
               <div class="label">{{ $ccusto_saldo  }}</div>
               <div class="label">{{ $ccusto_custo  }}</div>
           </a>
        </div>
        <div id="collapse{{ $ccusto->CCUSTO }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $ccusto->CCUSTO }}">
            <div class="panel-body">
                <div class="panel-group" id="accordion{{ $i }}" role="tablist" aria-multiselectable="true">

            {{-- Nível Data --}}
            @foreach ($ret['periodos'] as $periodo) 

                @php $periodo_valor  = "R$ ". number_format($periodo->VALOR,  2, ',', '.')
                @php $periodo_extra  = "R$ ". number_format($periodo->EXTRA,  2, ',', '.')
                @php $periodo_total  = "R$ ". number_format($periodo->TOTAL,  2, ',', '.')
                @php $periodo_outros = "R$ ". number_format($periodo->OUTROS, 2, ',', '.')
                @php $periodo_util   = $periodo->CCUSTO <> 99999999999 ? "R$ ". number_format($periodo->UTIL,   2, ',', '.')  : ''
                @php $periodo_saldo  = "R$ ". number_format($periodo->SALDO,  2, ',', '.')
                @php $periodo_perc   = $periodo->CCUSTO <> 99999999999 ? number_format($periodo->PERC_UTIL, 2, ',', '.') .'%' : ''
                @php $periodo_custo  = isset($periodo->CUSTO_SETOR) ? number_format($periodo->CUSTO_SETOR, 2, ',', '.') . '%' : ''

                @if ($periodo->CCUSTO === $ccusto->CCUSTO)

                    @php $x++
                    @php $periodo_id = $periodo->CCUSTO . $periodo->ANO . $periodo->MES
                    @php $negativo = floatval($periodo->PERC_UTIL) > 100 ? 'negativo' : ''

                    <div class="panel panel-default heading{{ $periodo_id }}pai">
                        <div class="panel-heading heading-periodo {{ $negativo }}" role="tab" id="heading{{ $periodo_id }}">
                             <a role="button" data-toggle="collapse" {{ ($periodo->CCUSTO == 99999999999 ? '' : 'href=#collapse' . $periodo_id) }} aria-controls="collapse{{ $periodo_id }}">
                               <div class="label">{{ $periodo->PERIODO_DESCRICAO }}</div>
                               <div class="label">{{ $periodo_valor  }}</div>
                               <div class="label">{{ $periodo_extra  }}</div>
                               <div class="label">{{ $periodo_total  }}</div>
                               <div class="label">{{ $periodo_outros }}</div>
                               <div class="label">{{ $periodo_util   }}</div>
                               <div class="label">{{ $periodo_perc   }}</div>
                               <div class="label">{{ $periodo_saldo  }}</div>
                               <div class="label">{{ $periodo_custo  }}</div>
                               </a>
                        </div>
                        <div id="collapse{{ $periodo_id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading{{ $periodo_id }}"> 
                            <div class="panel-body">
                                <div class="panel-group heading{{ $periodo_id }}grupo" id="accordion{{ $i . $x }}" role="tablist" aria-multiselectable="true">

                            {{-- Nível Conta Contábil --}}
                            @foreach ($ret['contas'] as $conta)

                                @if ($conta->CCUSTO === $periodo->CCUSTO && $conta->ANO === $periodo->ANO && $conta->MES === $periodo->MES )

                                    @php $conta_id = $periodo_id  . $conta->CCONTABIL

                                    @if ( $permissaoMenu->ALTERAR ) 
                                        {{-- $EditRota = '<button type="button" class="btn btn-primary btn-sm" onclick="window.location.href=\'' . route('_13030.edit', $conta->ID) . '\'"><span class="glyphicon glyphicon-edit"></span> Alterar</button>' --}}
                                        @php $EditRota = '<button type="button" class="btn btn-primary btn-sm popup-show" id="' . $conta->ID . '"><span class="glyphicon glyphicon-info-sign"></span> Detalhar</button>'
                                    @else
                                        @php $EditRota = '<button type="button" class="btn btn-primary btn-sm" onclick="window.location.href=\'' . route('_13030.show', $conta->ID) . '\'"><span class="glyphicon glyphicon-info-sign"></span> Detalhar</button>'
                                    @endif

                                    @php $DelClass = $permissaoMenu->EXCLUIR ? 'deletar' : ''
                                    @php $DelPerm  = $permissaoMenu->EXCLUIR ? '' : 'disabled'
                                    @php $destaque = $conta->DESTAQUE == 1 ? 'destaque' : ''
                                    @php $negativo = floatval($conta->PERC_UTIL) > 100 ? 'negativo' : ''
                                    @php $totaliza = (($conta->TOTALIZA == 1) && ($conta->CCUSTO <> 9999999999)) ? '<span class="glyphicon glyphicon-plus" data-toggle="tooltip" title="Esta C. Contábil será contabilizada no totalizador geral"></span>' : ''

                                    <div class="panel panel-default heading{{ $conta_id }}filho">
                                        <div class="panel-heading heading-ccontabil {{ $destaque }} {{ $negativo }}" role="tab" id="heading{{ $conta_id }}">
                                            <a role="button" data-toggle="collapse" {{ ($conta->CCUSTO == 9999999999 ? '' : 'href=#collapse' . $conta_id) }} aria-controls="collapse{{ $conta_id }}"> 
                                                <div class="label">   {{ $conta->CCONTABIL_MASK                             }}</div>
                                                <div class="label">   {{ $conta->CCONTABIL_DESCRICAO }} {!! $totaliza !!}     </div>
                                                <div class="label">R$ {{ number_format($conta->VALOR,  2, ',', '.')         }}</div>
                                                <div class="label">R$ {{ number_format($conta->EXTRA,  2, ',', '.')         }}</div>
                                                <div class="label">R$ {{ number_format($conta->TOTAL,  2, ',', '.')         }}</div>
                                                <div class="label">R$ {{ number_format($conta->OUTROS, 2, ',', '.')         }}</div>
                                                <div class="label">R$ {{ number_format($conta->UTIL,   2, ',', '.')         }}</div>
                                                <div class="label">   {{ number_format($conta->PERC_UTIL, 2, ',', '.') .'%' }}</div> 
                                                <div class="label">R$ {{ number_format($conta->SALDO,  2, ',', '.')         }}</div>
                                            </a>
                                            @if ( !($conta->CCUSTO == 9999999999) ) 
                                            <div class="acoes">
                                                {!! $EditRota !!}
                                                <button type="button" class="btn btn-danger btn-sm {{ $DelClass }}" {{ $DelPerm }} contaid="{{ $conta->ID }}" classdelete="heading{{ $conta_id }}filho" classpai="heading{{ $periodo->CCUSTO . $periodo->ANO . $periodo->MES }}pai" classavo="heading{{ $periodo->CCUSTO }}avo" classgrupopai="heading{{ $periodo->CCUSTO . $periodo->ANO . $periodo->MES }}grupo">
                                                    <span class="glyphicon glyphicon-trash"></span> Excluir
                                                </button>
                                            </div>
                                            @endif
                                        </div>
                                        <div id="collapse{{ $conta_id }}" class="panel-collapse collapse lista-itens" role="tabpanel" aria-labelledby="heading{{ $conta_id }}">
                                            <div class="list-group">
                                                <ul class="list-group">

                                                @php $count = 0

                                                {{-- Nível Itém --}}
                                                @foreach($ret['itens'] as $iten)

                                                    @if( $iten->CCUSTO === $conta->CCUSTO && $iten->CCONTABIL === $conta->CCONTABIL && $iten->MES === $conta->MES && $iten->ANO === $conta->ANO )

                                                        @php $nat_class = strripos($iten->NATUREZA,'D') === 0 ? 'nat-debito' : 'nat-credit'
                                                        @php $nat_desc  = strripos($iten->NATUREZA,'D') === 0 ? 'Natureza: Débito' : 'Natureza: Crédito'

                                                        <li class="list-group-item">
                                                            <div class="label">{{ $iten->DESCRICAO }}</div>
                                                            <div class="label">R$ {{ number_format($iten->VALOR, 2, ',', '.') }}</div>
                                                            <div class="label {!! $nat_class !!}" data-toggle="tooltip" data-placement="auto" title="{!! $nat_desc !!}">{{ $iten->NATUREZA }}</div>
                                                            <div class="label">{{ date_format(date_create($iten->DATA), 'd/m/Y') }}</div>
                                                        </li>
                                                        @php $count++
                                                    @endif
                                                @endforeach
                                                </ul>
                                                <div class="panel-footer">Nº de registros: {{ $count }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
                </div>
            </div>
        </div>
    </div> 
@endforeach 

