<div class="bsc-container">
    @php $max   = count($imgs)-1
    
    <div class="bsc-coluna6 desc-comparativo" style="width: 100%; height:4%; text-align: center;">
        
        <div class="prev-anuncio">1 de {{ $max+1 }}</div>
        
        <div class="glyphicon glyphicon-arrow-right imganuncio anuncion"></div>
        <div class="glyphicon glyphicon-arrow-left imganuncio anunciop imginativo"></div>
    </div>
    
    
    @php $cont  = 0
    
    
    @foreach ($imgs as $img)
    
        @if(($cont == 0))
            @php $cls = 'imgativa'
        @else
            @php $cls = 'imgnoativa'
        @endif
        
            <img class="anuncio-conteiner {{ $cls }}" src="/assets/temp/anuncios/{{ $img }}" ordem="{{ $cont }}" max="{{ $max }}">
        
        @php $cont++;
    @endforeach
    
</div>

