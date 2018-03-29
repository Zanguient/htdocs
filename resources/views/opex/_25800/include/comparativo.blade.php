<div class="bsc-container">
    <div class="tab-comparativo">
        
        <div class="bsc-coluna6 desc-comparativo" style="width: 100%; height:4%;">
            <div class="chec-comp"><div><input type="checkbox" class="checs" name="perildo" value="1" style="visibility: hidden;"></div><div>PERÍODO:</div></div>
            <div class="chec-comp"><div><input type="checkbox" class="checs" name="checd" {{ $chec0 }}></div><div>DIA</div></div>
            <div class="chec-comp"><div><input type="checkbox" class="checs" name="checs" {{ $chec1 }}></div><div>SEMANA</div></div>
            <div class="chec-comp"><div><input type="checkbox" class="checs" name="checm" {{ $chec2 }}></div><div>MÊS</div></div>
            <div class="chec-comp"><div><input type="checkbox" class="checs" name="chect" {{ $chec3 }}></div><div>SEMESTRE</div></div>
            <div class="chec-comp"><div><input type="checkbox" class="tot-comp" name="chect" {{ $chec4 }}></div><div>TOTALIZADOR</div></div>
            <div class="chec-comp" style="width: 23vw;"><div><input type="checkbox" name="desc" value="1" style="visibility: hidden;">
                </div><div>{{ $dc_dat }}</div>
            </div>
        </div>
       
        @php $contLinhas = 0
        @php $contColl  = 0
        
        @foreach ($linha as $lin)
            @php $contColl = 0
            @foreach ($coll as $col)
                
                
                {{-- cor dos valores --}}
                @php $cor = 'desc-comp'
                @php $fot = '1.3vw'
                
                @if(($contLinhas > 0))
                    @if($contColl > 0)
                        @php $cor = $var[$contColl-1][$contLinhas-1]['cor']
                        @php $fot = $var[$contColl-1][$contLinhas-1]['font'].'vw'
                    @endif
                @else
                    @php $cor = 'desc-fab-comp'
                    @php $fot = '1vw'
                @endif
            
                <div class="bsc-coluna6 comparativo-linha val-tab {{ $cor }}" style="width: {{$col}}%; height: {{$lin}}%; font-size: {{ $fot }}">

                @if(($contLinhas < 1))
                    @if($contColl == 0)
                        {{-- titulo indicadores --}}
                        {{ $ds_in }}
                    @else
                        {{-- titulo colunas --}}
                        {{$desc[$contColl-1]}}
                    @endif
                @else
                    @if($contColl == 0)
                        {{-- desc indicadores --}}
                        {{ $indi[$contLinhas-1] }}
                    @else
                        {{-- valores colunas --}}
                        {{ $var[$contColl-1][$contLinhas-1]['valor'] }}
                    @endif
                @endif
                

                </div>
                @php $contColl++
            @endforeach
            @php $contLinhas++
        @endforeach    
        
    </div>
</div>

