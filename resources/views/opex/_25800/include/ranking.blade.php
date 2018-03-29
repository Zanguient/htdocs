<div class="bsc-container">
    <div class="tab-comparativo">
        
        <div class="bsc-coluna6 desc-comparativo" style="width: 100%;height:4%;text-align: center;font-size: 1.2vw;">
            {{ $desc }}
        </div>
       
        <div class="bsc-ranking">
            <div class="titulo"  style="color: yellow; ">SEMANA - RANKING POR DESEMPENHO</div>
            <div class="corpo" >
                @php $cont = 1
                @foreach ($semana as $valor)
                    @php if($cont % 2 == 0){$zeb = 'zebrado';}else{$zeb = '';}
                    
                    <div class="item  {{ $zeb.' teste' }}"><div class="ordem" style="{{ $css }}">{{ $cont }}º</div><div class="desc" style="{{ $css }}">{{ $valor['fab'] }}</div><div class="perc" style="{{ $css }}">{{ $valor['valor'] }}</div></div>
                    @php $cont++
                @endforeach 
            </div>
            <div class="rodape" >MÉDIA: {{ $media1 }}</div>
        </div>
        
        <div class="bsc-ranking" >
            <div class="titulo" >MÊS - RANKING POR DESEMPENHO</div>
            <div class="corpo" >
                @php $cont = 1
                @foreach ($mes_1 as $valor)
                    @php if($cont % 2 == 0){$zeb = 'zebrado';}else{$zeb = '';}
                    
                    <div class="item   {{ $zeb.' teste' }}" ><div class="ordem" style="{{ $css }}">{{ $cont }}º</div><div class="desc" style="{{ $css }}">{{ $valor['fab'] }}</div><div class="perc" style="{{ $css }}">{{ $valor['valor'] }}</div></div>
                    @php $cont++
                @endforeach 
            </div>
            <div class="rodape" >MÉDIA: {{ $media2 }}</div>
        </div>
        
        <div class="bsc-ranking" >
            <div class="titulo" >MÊS - RANKING POR EVOLUÇÃO</div>
            <div class="corpo" >
                @php $cont = 1
                @foreach ($mes_2 as $valor)
                    @php if($cont % 2 == 0){$zeb = 'zebrado';}else{$zeb = '';}
                    
                    <div class="item   {{ $zeb.' teste' }}" ><div class="ordem" style="{{ $css }}">{{ $cont }}º</div><div class="desc" style="{{ $css }}">{{ $valor['fab'] }}</div><div class="perc" style="{{ $css }}">{{ $valor['valor'] }}</div></div>
                    @php $cont++
                @endforeach 
            </div>
            <div class="rodape" >MÉDIA: {{ $media3 }}</div>
        </div>
        
    </div>
</div>

