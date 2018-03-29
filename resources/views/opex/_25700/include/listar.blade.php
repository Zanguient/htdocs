
        @if ($popup == 1)
                <ul class="list-inline popup-acoes2">
            @else
                <ul class="list-inline acoes">
            @endif
            <button type="button" class="btn btn-primary popup-show-plano-acao-incluir" data-hotkey="f6" data-loading-text="{{ Lang::get('master.gravando') }}" {{ $permissaoMenu->INCLUIR ? '' : 'disabled' }}><span class="glyphicon glyphicon-plus"></span> {{ Lang::get('master.incluir') }}</button>
            <button type="button" class="btn btn-default fim-plano-acao popup-voltar" data-hotkey="esc" data-loading-text="{{ Lang::get('master.cancelar') }}"><span class="glyphicon glyphicon-chevron-left"></span> {{ Lang::get('master.voltar') }}</button>
        </ul>
            
        <fieldset class="planoacao">
            <div></div>
                <legend>{{$imputs['descpa']}}</legend>
            <div></div>
            
            <input type="hidden" name="_plano-acao-ccsuto"    class="plano-acao-ccsuto"  value="{{$imputs['ccusto']}} ">
            <input type="hidden" name="_plano-acao-vinculo"   class="plano-acao-vinculo" value="{{$imputs['vinculo']}}">
            <input type="hidden" name="_plano-acao-descpa"    class="plano-acao-descpa" value="{{$imputs['descpa']}}">
            <input type="hidden" name="_plano-acao-controlen" class="plano-acao-controlen" value="{{$imputs['controlen']}}">
            <input type="hidden" name="_plano-acao-oque"      class="plano-acao-oque" value="{{$imputs['oque']}}">
            
            <input type="hidden" name="_plano-acao-tela"        class="plano-acao-tela"      value="{{$imputs['tela']}}">
            <input type="hidden" name="_plano-acao-subvinculo"  class="plano-acao-subvinculo"      value="{{$imputs['sub_vinc']}}">
            
            <input type="hidden" name="_filtro-mes-inicial"  class="plano-mes-inicial"      value="{{$imputs['mes_i']}}">
            <input type="hidden" name="_filtro-mes-final"  class="plano-mes-final"      value="{{$imputs['mes_f']}}">
            <input type="hidden" name="_filtro-ano-inicial"  class="plano-ano-inicial"      value="{{$imputs['ano_i']}}">
            <input type="hidden" name="_filtro-ano-final"  class="plano-ano-final"      value="{{$imputs['ano_f']}}">
            
            @if ($popup == 1)
                <table class="table table-hover table-striped" style="margin-top: 10px;">
            @else
                <table class="table table-hover table-striped">
            @endif
            
                <thead>
                    <tr>
                        <th class="t-text col-ccusto">C.Custo</th>
                        <th class="t-text col-oque">O Que</th>
                        <th class="t-text col-como">Como</th>
                        <th class="t-text col-Quem">Quem</th>
                        <th class="t-text col-quando">Quando</th>
                    </tr>
                </thead>
                <tbody class="itens">
                    @foreach ($dados as $dado)
                        <tr id="{{ $dado->ID }}" link="" class="popup-show-plano-acao-item">
                            <td class="t-text">{{$dado->C_CUSTO}}</td>
                            <td class="t-text">{{$dado->OBSERVACAO}}</td>
                            <td class="t-text">{{$dado->PLANACAO_COMO}}</td>
                            <td class="t-text">{{$dado->PLANACAO_QUEM}}</td>
                            <td class="t-text">{{date('d/m/Y',strtotime($dado->PLANACAO_QUANDO)).' - '.date('d/m/Y',strtotime($dado->PLANACAO_QUANDOF))}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
        </fieldset>
        
    </form>