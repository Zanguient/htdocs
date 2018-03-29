@if ( $ref == 'index' )
<ul class="list-inline popup-acoes">
    <li>
        <a href="{{ $permissaoMenu->ALTERAR ? route('_13030.edit', $id) : '#' }}" class="btn btn-primary btn-hotkey btn-alterar" {{ $permissaoMenu->ALTERAR ? '' : 'disabled' }}>
            <span class="glyphicon glyphicon-edit"></span>
             {{ Lang::get('master.alterar') }}
        </a>
    </li>
    <li>
        <a 
            href="javascript:void(0);" 
            class="btn btn-danger btn-hotkey btn-excluir {{ $permissaoMenu->EXCLUIR ? 'deletar' : '' }}" {{ $permissaoMenu->EXCLUIR ? '' : 'disabled' }}
            contaid="{{ $cota->ID }}" 
            classdelete="heading{{ $cota->CCUSTO . $cota->ANO . $cota->MES . $cota->CCONTABIL }}filho" 
            classpai="heading{{ $cota->CCUSTO . $cota->ANO . $cota->MES }}pai" 
            classavo="heading{{ $cota->CCUSTO }}avo" 
            classgrupopai="heading{{ $cota->CCUSTO . $cota->ANO . $cota->MES }}grupo"
            >
            <span class="glyphicon glyphicon-trash"></span>
             {{ Lang::get('master.excluir') }}
        </a>
    </li>
</ul>   
@endif

@php /*
@elseif ( $ref == 'dre' )
<ul class="list-inline popup-acoes">
    <li>
        <button type="button" class="btn btn-default popup-voltar2 btn-voltar" data-hotkey="esc" data-loading-text="{{ Lang::get('master.voltar') }}"><span class="glyphicon glyphicon-chevron-left"></span> {{ Lang::get('master.voltar') }}</button>
    </li>
</ul>   
@endif
@php */

<form class="form-inline">
    <fieldset>
        <legend>{{ Lang::get('master.info-geral') }}</legend>
        <div class="row">              
            <div class="form-group">
                <label>{{ Lang::get('master.id') }}:</label>
                <input type="text" name="ccusto_descricao" class="form-control input-menor" readonly value="{{ $cota->ID }}" required/>
                <input type="hidden" name="_ccusto" class="form-control" value="{{ floatval($cota->ID) }}" />
            </div>                
            <div class="form-group">
                <label>{{ Lang::get('master.ccusto') }}:</label>
                <input type="text" name="ccusto_descricao" class="form-control input-maior" readonly value="{{ $cota->CCUSTO_MASK . ' - ' . $cota->CCUSTO_DESCRICAO }}" required/>
                <input type="hidden" name="_ccusto" class="form-control" value="{{ floatval($cota->CCUSTO) }}" />
            </div>
            <div class="form-group">
                <label>{{ Lang::get('master.ccontabil') }}:</label>
                <input type="text" name="ccontabil_descricao" class="form-control input-maior" readonly value="{{ $cota->CCONTABIL_MASK . ' - ' . $cota->CCONTABIL_DESCRICAO }}" required/>
                <input type="hidden" name="_ccontabil" class="form-control" value="{{ floatval($cota->CCONTABIL) }}" />
            </div>
            <div class="form-group">
                <label>{{ Lang::get('master.periodo') }}:</label>
                <input type="text" name="periodo" class="form-control" readonly value="{{ $cota->PERIODO_DESCRICAO }}" required/>
                <input type="hidden" name="_mes" class="form-control" value="{{ $cota->MES }}" />
                <input type="hidden" name="_ano" class="form-control" value="{{ $cota->ANO }}" />
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <input type="checkbox" name="bloqueio" id="bloqueia" class="form-control" {{ ($cota->BLOQUEIO == 1) ? 'checked' : '' }} disabled />
                <label for="bloqueia" data-toggle="tooltip" title="{{ Lang::get('compras/_13030.bloqueio-desc') }}" disabled >{{ Lang::get('compras/_13030.bloqueio') }}</label>
            </div>
            <div class="form-group">
                <input type="checkbox" name="notificacao" id="notifica" class="form-control" {{ ($cota->NOTIFICACAO == 1) ? 'checked' : '' }} disabled />
                <label for="notifica"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.notificacao-desc') }}" disabled >{{ Lang::get('compras/_13030.notificacao') }}</label>
            </div>
            <div class="form-group">
                <input type="checkbox" name="destaque" id="destaque" class="form-control" {{ ($cota->DESTAQUE == 1) ? 'checked' : '' }} disabled />
                <label for="destaque"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.destaque-desc') }}" disabled >{{ Lang::get('compras/_13030.destaque') }}</label>
            </div>		    
            <div class="form-group">
                <input type="checkbox" name="totaliza" id="totaliza" class="form-control" {{ ($cota->TOTALIZA == 1) ? 'checked' : '' }} disabled />
                <label for="totaliza"  data-toggle="tooltip" title="{{ Lang::get('compras/_13030.totaliza-desc') }}" disabled >{{ Lang::get('compras/_13030.totaliza') }}</label>
            </div>		                
        </div>
        <div class="row">
            <div class="form-group">
                <label>Cota:</label>
                <div class="input-group dinheiro">
                    <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                    <input type="text" name="cota" class="form-control mask-numero cota-alterar" decimal="2" min="0" value="{{ number_format($cota->VALOR, 2, ',', '.') }}" required readonly value="555"/>
                </div>
            </div>
            <div class="form-group">
                <label>Extra (+):</label>
                <div class="input-group dinheiro">
                    <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                    <input type="text" name="cota" class="form-control mask-numero cota-alterar" decimal="2" min="0" value="{{ number_format($cota->EXTRA, 2, ',', '.') }}" required readonly value="555"/>
                </div>
            </div>
            <div class="form-group">
                <label>Subtotal:</label>
                <div class="input-group dinheiro">
                    <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                    <input type="text" name="cota" class="form-control mask-numero cota-alterar" decimal="2" min="0" value="{{ number_format($cota->TOTAL, 2, ',', '.') }}" required readonly value="555"/>
                </div>
            </div>
            <div class="form-group">
                <label>Reduções (-):</label>
                <div class="input-group dinheiro">
                    <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                    <input type="text" name="cota" class="form-control mask-numero cota-alterar" decimal="2" min="0" value="{{ number_format($cota->OUTROS, 2, ',', '.') }}" required readonly value="555"/>
                </div>
            </div>
            <div class="form-group">
                <label>Utilizado:</label>
                <div class="input-group dinheiro">
                    <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                    <input type="text" name="cota" class="form-control mask-numero cota-alterar" decimal="2" min="0" value="{{ number_format($cota->UTIL, 2, ',', '.') }}" required readonly value="555"/>
                </div>
            </div>
            <div class="form-group">
                <label>Utilizado %:</label>
                <div class="input-group dinheiro">
                    <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                    <input type="text" name="cota" class="form-control mask-numero cota-alterar" decimal="2" min="0" value="{{ number_format($cota->PERC_UTIL, 2, ',', '.') }}" required readonly value="555"/>
                </div>
            </div>
            <div class="form-group">
                <label>Saldo:</label>
                <div class="input-group dinheiro">
                    <div class="input-group-addon"><span class="fa fa-usd"></span></div>
                    <input type="text" name="cota" class="form-control mask-numero cota-alterar" decimal="2" min="0" value="{{ number_format($cota->SALDO, 2, ',', '.') }}" required readonly value="555"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group" style="width: 50%;">
                <label>Observação Cota:</label>
                <textarea name="cota_observacao" rows="6" style="width: 100% !important;" class="form-control" readonly>{{ $cota->COTA_OBSERVACAO }}</textarea>
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend>{{ Lang::get('compras/_13030.cota-extra') }}</legend>
        <div class="form-group cota-extra" style="display: none">
            <label>{{ Lang::get('master.historico') }}</label>
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th class="t-text t-medium">{{ Lang::get('master.usuarios') }}</th>
                    <th class="t-numb t-low">{{ Lang::get('master.valor') }}</th>
                    <th class="t-center t-medium">{{ Lang::get('master.datahora') }}</th>
                    <th class="t-text t-extra-large">{{ Lang::get('master.obs') }}</th>
                </tr>
                </thead>
                <tbody class="t-body">
                @foreach( $extras as $extra )
                <tr data-id="{{ $extra->ID }}">
                    <td class="t-text t-medium">{{ $extra->USUARIO_NOME }}</td>
                    <td class="t-numb t-low">R$ {{ number_format($extra->VALOR, 2, ',', '.') }}</td>
                    <td class="t-center t-medium">{{ date_format(date_create($extra->DATAHORA), 'd/m/Y H:i:s') }}</td>
                    <td class="t-text t-extra-large limit-width">{{ $extra->OBSERVACAO }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
    <fieldset>
        <legend>{{ Lang::get('compras/_13030.outros-lancamentos') }}</legend>
        <div class="form-group cota-outros" style="display: none">
            <label>{{ Lang::get('master.historico') }}</label>
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th class="t-text t-medium">{{ Lang::get('master.usuarios') }}</th>
                    <th class="t-numb t-low">{{ Lang::get('master.valor') }}</th>
                    <th class="t-center t-medium">{{ Lang::get('master.datahora') }}</th>
                    <th class="t-text t-extra-large">{{ Lang::get('master.obs') }}</th>
                </tr>
                </thead>
                <tbody class="outros">
                @foreach( $outros as $outro )
                <tr data-id="{{ $outro->ID }}">
                    <td class="t-text t-medium">{{ $outro->USUARIO_NOME }}</td>
                    <td class="t-numb t-low">R$ {{ number_format($outro->VALOR, 2, ',', '.') }}</td>
                    <td class="t-center t-medium">{{ date_format(date_create($outro->DATAHORA), 'd/m/Y H:i:s') }}</td>
                    <td class="t-text t-extra-large limit-width">{{ $outro->OBSERVACAO }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>	
    <fieldset>
        <legend>Lançamentos</legend>
        <div class="form-group cota-itens" style="display: none">
            <label>{{ Lang::get('master.historico') }}</label>
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th class="t-text t-extra-large-max">Descrição</th>
                    <th class="t-center t-low-max">Valor</th>
                    <th class="t-center t-low-max" ttitle="Abatimentos">Abat.</th>
                    <th class="t-center t-low-max" ttitle="Valor - Abatimentos">Subtotal</th>
                    <th class="t-center t-small-max">Nat.</th>
                    <th class="t-center t-low-max">Data</th>
                </tr>
                </thead>
                <tbody class="itens">
                @foreach( $itens as $item )
                <tr data-id="{{ $item->ID }}">
                    <td class="t-text">{{ $item->DESCRICAO }}</td>           
                    <td class="t-numb">R$ {{ number_format($item->VALOR, 2, ',', '.') }}</td>
                    <td class="t-numb">R$ {{ number_format($item->DESCONTO_IMPOSTO, 2, ',', '.') }}</td>
                    <td class="t-numb">R$ {{ number_format($item->VALOR_SUBTOTAL, 2, ',', '.') }}</td>
                    <td class="t-center {{ strripos($item->NATUREZA,'D') === 0 ? 'nat-debito' : 'nat-credit' }}">{{ $item->NATUREZA }}</td>
                    <td class="t-center">{{ date_format(date_create($item->DATA), 'd/m/Y') }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</form>