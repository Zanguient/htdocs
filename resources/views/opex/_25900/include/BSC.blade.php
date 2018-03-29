<div class="bsc-container">
    <div class="bsc-coluna0 bsc-linha">
        <div class="desc-go-bsc">{{ $desc.' -> data '.$data_consulta.' tratada em '.$data_exec }}</div>
        <div class="bsc-coluna00 bsc-linha tituli-lateral-bsc"><span>PROCESSOS INTERNOS</span></div>
        <div class="bsc-coluna1 bsc-linha tituli-bsc"><div>Indicadores</div></div>
        <div class="bsc-coluna2 bsc-linha tituli-bsc"><div>Meta</div></div>
        <div class="bsc-coluna3 bsc-linha tituli-bsc"><div>Peso</div></div>
        <div class="bsc-coluna4 bsc-linha tituli-bsc"><div>Semana</div></div>
        <div class="bsc-coluna5 bsc-linha tituli-bsc"><div>Mês</div></div>
        <div class="bsc-coluna6 bsc-linha tituli-bsc"><div>Semestre</div></div>
        
        @foreach ($indicadores as $indicador)
            <div class="bsc-coluna1 bsc-linha desc-bsc">{{ $indicador['DESC'] }}</div>
            <div class="bsc-coluna2 bsc-linha">
                <div class="bsc-meta a">{{ $indicador['DEF1'] }}</div>
                <div class="bsc-meta b">{{ $indicador['DEF2'] }}</div>
                <div class="bsc-meta c">{{ $indicador['DEF3'] }}</div>
            </div>
            <div class="bsc-coluna3 bsc-linha val-bsc">{{ $indicador['PESO'] }}</div>
            <div class="bsc-coluna4 bsc-linha val-bsc {{ $indicador['COR1'] }}">{{ $indicador['VALOR1'] }}<div class="more-info bol-ativo" ><div class="bol">i</div></div><div class="desc-nota info-inativo"><pre>{{ $indicador['P_NOTS'] }}</pre></div></div>
            <div class="bsc-coluna5 bsc-linha val-bsc {{ $indicador['COR2'] }}">{{ $indicador['VALOR2'] }}<div class="more-info bol-ativo" ><div class="bol">i</div></div><div class="desc-nota info-inativo"><pre>{{ $indicador['P_NOTM'] }}</pre></div></div>
            <div class="bsc-coluna6 bsc-linha val-bsc {{ $indicador['COR3'] }}">{{ $indicador['VALOR3'] }}<div class="more-info bol-ativo" ><div class="bol">i</div></div><div class="desc-nota info-inativo"><pre>{{ $indicador['P_NOTT'] }}</pre></div></div>
        @endforeach  
    
    </div>
    
    <div class="bsc-coluna7 bsc-linha">
        
        <div class="tab-topo {{ $corimgterm }}">
        <div class="valor-bsc-1">{{ $MEDIA1 }}</div>
        
         {{-- @include('helper.include.view.termometro',['nota'=>$PERC]) --}}
        
        <div class="valor-bsc-2">{{ $MEDIA2 }}
            <div class=""><div class="more-info more-info-bsc bol-ativo" ><div class="bol bol-bsc">i</div></div><div class="desc-nota desc-nota-bsc info-inativo"><pre>{{ $DCST }}</pre></div></div>
        </div>
        
        </div>
        
        <div class="tab-botom">
            @foreach ($termometro as $t)
                <div class="linhas-tab {{ $t['COR1'] }}">{{ $t['DESC'] }}</div>
            @endforeach    
        </div>    
        
    </div>
      
</div>

<script>
    setImgRodape('{{ $corimgterm }}');
</script>

