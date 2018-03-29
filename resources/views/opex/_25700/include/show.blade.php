
        @if ($popup == 1)
                <ul class="list-inline popup-acoes2">
            @else
                <ul class="list-inline acoes">
            @endif
            <button type="button" class="btn btn-primary popup-show-plano-acao-aleraritem" data-hotkey="f9" data-loading-text="{{ Lang::get('master.alterar') }}"  {{ $permissaoMenu->ALTERAR ? '' : 'disabled' }}><span class="glyphicon glyphicon-edit"></span> {{ Lang::get('master.alterar') }}</button>
            <button type="button" class="btn btn-danger popup-show-plano-acao-excluir" data-hotkey="f12" data-loading-text="{{ Lang::get('master.cancelar') }}"  {{ $permissaoMenu->EXCLUIR ? '' : 'disabled' }}><span class="glyphicon glyphicon-trash"></span> {{ Lang::get('master.excluir') }}</button>
            <button type="button" class="btn btn-default popup-show-plano-acao-cancelar popup-voltar" data-hotkey="esc" data-loading-text="{{ Lang::get('master.cancelar') }}"><span class="glyphicon glyphicon-chevron-left"></span> {{ Lang::get('master.voltar') }}</button>
        </ul>

            <input type="hidden" name="_plano-acao-ccsuto"  class="plano-acao-id"  value="{{ $imputs['id']}}">
            <input type="hidden" name="_plano-acao-ccsuto"  class="plano-acao-ccsuto"  value="{{ $imputs['ccusto']}}">
            <input type="hidden" name="_plano-acao-vinculo" class="plano-acao-vinculo" value="{{ $imputs['vinculo']}}">
            <input type="hidden" name="_plano-acao-descpa"    class="plano-acao-descpa" value="{{$imputs['descpa']}}">
            <input type="hidden" name="_plano-acao-controlen" class="plano-acao-controlen" value="{{$imputs['controlen']}}">
            <input type="hidden" name="_plano-acao-oque"      class="plano-acao-oque" value="{{$imputs['oque']}}">
            
            <input type="hidden" name="_plano-acao-tela"        class="plano-acao-tela"      value="{{$imputs['tela']}}">
            <input type="hidden" name="_plano-acao-subvinculo"  class="plano-acao-subvinculo"      value="{{$imputs['sub_vinc']}}">
            
            <input type="hidden" name="_filtro-mes-inicial"  class="plano-mes-inicial"      value="{{$imputs['mes_i']}}">
            <input type="hidden" name="_filtro-mes-final"  class="plano-mes-final"      value="{{$imputs['mes_f']}}">
            <input type="hidden" name="_filtro-ano-inicial"  class="plano-ano-inicial"      value="{{$imputs['ano_i']}}">
            <input type="hidden" name="_filtro-ano-final"  class="plano-ano-final"      value="{{$imputs['ano_f']}}">
            
        <fieldset class="planoacao">
            <div></div>
                <legend>{{$imputs['descpa']}}</legend>
            <div></div>
            

            @include('financeiro._20030.include.filtrar',
            [
                'campos_imputs'     => [['class-p-a-ccusto','ID',$ccusto]],
                'selecionado'		=> $selecionado,
                'valor'             => $valor1,
                'autofocus'			=> $autofocus,
                'required'			=> 'required'
            ])
            
            @include('opex._25500.include.filtrar',
            [
                'campos_imputs'     => [['class-p-a-indicador','ID',$indicador]],
                'selecionado'		=> $selecionado,
                'valor'             => $valor2,
                'autofocus'			=> $autofocus,
                'required'			=> 'required',
                'filtro_sql'        => ['so_web','ord_por_desc','sql_todos_campos'],
                'readonly'          => $readonly,
                'class'             => 'class-c-a-indicador'
            ])
            
            <div class="form-group">
				<label for="requisicao">Vínculo:</label>
				<input type="text" name="class-p-a-vinculo" class="form-control input-menor class-p-a-vinculo" value="{{ $tabela[0]->VINCULO}}" readonly>
			</div>
            
            <div class="form-group">
				<label for="requisicao">Turno:</label>
				<input type="text" name="class-p-a-turno" class="form-control input-menor class-p-a-turno" value="{{ $tabela[0]->TURNO}}" readonly>
			</div>
                <div></div>
            <label>O que:</label>
            <div class="textarea-grupo" >
                <textarea name="class-p-a-oque" class="form-control obs class-p-a-oque" rows="5" cols="100" required readonly>{{ $tabela[0]->OBSERVACAO }}</textarea>
            </div>
            
            <p></p>
            
            <div class="form-group">
				<label for="requisicao">Quem:</label>
				<input type="text" name="class-p-a-quem" class="form-control input-medio class-p-a-quem" value="{{ $tabela[0]->PLANACAO_QUEM}}" required readonly>
			</div>
            
            <div class="form-group">
				<label for="requisicao">Início:</label>
                <input type="date" name="class-p-a-quandod" class="form-control class-p-a-quandod" value="{{ date("Y-m-d",strtotime($tabela[0]->PLANACAO_QUANDO))}}" required readonly>
			</div>
            
            <div class="form-group">
				<label for="requisicao">Fim:</label>
                <input type="date" name="class-p-a-quandod" class="form-control class-p-a-quandot" value="{{ date("Y-m-d",strtotime($tabela[0]->PLANACAO_QUANDOF))}}" required readonly>
			</div>
            
            <div></div>
            
            <label>Como:</label>
            <div class="textarea-grupo">
                <textarea name="class-p-a-como" class="form-control obs class-p-a-como" rows="5" cols="100" required readonly>{{ $tabela[0]->PLANACAO_COMO}}</textarea>
            </div>

        </fieldset>
        
    </form>