
<form class="form-inline">

    <div class="form-group">
        <label for="descricao">C. Custo:</label>
        <input type="text" class="form-control" readonly="" value="{{ $ggf->CCUSTO }} - {{ $ggf->CCUSTO_DESCRICAO }}">
    </div>

    <div class="form-group">
        <label for="descricao">Período:</label>
        <input type="text" class="form-control" readonly="" value="{{ $ggf->MES_DESCRICAO }}/{{ $ggf->ANO }}">
    </div>
    
    <div class="form-group">
        <label for="descricao" title="Total utilizado">Total Utiliz.:</label>
        <input type="text" class="form-control input-medio-min text-right" readonly="" value="R$ {{ number_format($ggf->VALOR_UTILIZADO, 2, ',', '.') }}">
    </div>
    <div class="table-ec" style="height: calc(100vh - 210px);">
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th>Familia</th>
                    <th class="text-right">Cota</th>
                    <th class="text-right" title="Valor utilizado">Util.</th>
                    <th class="text-right" title="Percentual: Valor utilizado / Cota">Perc.</th>
                    <th class="text-right" title="Cota - Valor utilizado">Saldo</th>
                    <th class="text-right"> </th>
                </tr>
            </thead>
            <tbody>
                @foreach( $ggfs as $item )
                <tr>
                    <td>{{ $item->FAMILIA_ID }} - {{ $item->FAMILIA_DESCRICAO }}</td>
                    <td class="text-right">R$ {{ number_format($item->VALOR_COTA, 2, ',', '.') }}</td>
                    <td class="text-right">R$ {{ number_format($item->VALOR_UTILIZADO, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->PERCENTUAL_UTILIZADO, 2, ',', '.') }}%</td>
                    <td class="text-right">R$ {{ number_format($item->SALDO, 2, ',', '.') }}</td>
                    <td class="text-right" style="width: 85px; padding: 3px 4px 3px 4px;">
                        <button type="button" class="btn btn-primary btn-sm show-modal-ggf" data-item="ggf" data-ccusto="{{ $item->CCUSTO }}" data-mes="{{ $item->MES }}" data-ano="{{ $item->ANO }}" data-familia_id="{{ $item->FAMILIA_ID }}">
                            <span class="glyphicon glyphicon-info-sign"></span> Detalhar
                        </button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</form>