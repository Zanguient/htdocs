@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11000.css') }}" />
@endsection

@section('titulo')
    {{ Lang::get('opex/_25700.titulo') }}
@endsection

@section('conteudo')

                <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th class="t-text t-low-max">COR</th>
                        <th class="t-text t-extra-large-max">SALDO</th>
                        <th class="t-text t-low-max">ENDERECAMENTO</th>
                        <th class="t-text t-low-max">VOLUMES</th>
                    </tr>
                </thead>
                <tbody class="itens">
                    @foreach ($dados as $dado)

                        <tr class="popup-show-plano-acao-item">
                            <td class="t-text">{{ $dado->COR}}</td>
                            <td class="t-text">{{ $dado->SALDO}}</td>           
                            <td class="t-text">{{ $dado->ENDERECAMENTO}}</td>
                            <td class="t-text">{{ $dado->VOLUMES}}</td>
                        </tr>

                    @endforeach
                </tbody>
                </table>
@endsection

@section('script')

@endsection
