<div class="tab-topo {{ 'img-bsc-'.$dados['cor_imagem'] }}">
    <div class="valor-bsc-1">{{ $dados['nota_original'] }}</div>

    <div class="valor-bsc-2">{{ $dados['nova_original']  }}</div>

</div>

<div class="tab-botom">
    @foreach ($dados['frases'] as $frase)
        <div class="linhas-tab {{ 'cor-tab-'.$frase['cor'] }}" style="height: calc({{$dados['perc_linha']}}% - 0px)">{{ $frase['descricao'] }}</div>
    @endforeach    
</div>
