@extends('master')

@section('titulo')
    {{ Lang::get('admin/_11100.titulo') }}
@endsection

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11100.css') }}" />
@endsection

@section('conteudo')

<ul class="hub-ul qv-grid ng-scope" style="margin-top: -50px;">
    @foreach ( $ret as $item )

            <li class="qv-content-li ng-scope qv-item-enabled abrir-qlik"

                data-arquivo="{{ $item->QVF }}"
                data-hub="{{ $item->HUB }}"
                data-pastas="{{ $item->PASTAS }}"
                data-projeto="{{ $item->PROJETO }}"
                data-usuario="{{ $item->USUARIO }}"

            ">
            <div class="qv-content-li-inner">
                <div class="qv-item-border-outer"></div>
                <div class="qv-item-border-inner"></div>
                <div class="qv-content-item">
                    <div qv-hub-thumb="" class="qv-thumb-wrap ng-scope" data-action="openItem">
                        <div class="item-thumbnail-wrapper">
                            <div class="thumb-hover"></div>
                            <img ng-src="{{$url}}/hub/../resources/img/core/static/app.png" draggable="false" class="img img-no-drag qv-img-thumb" src="{{$url}}/hub/../resources/img/core/static/app.png">
                        </div>
                    </div>
                    <div class="qv-details-wrap" data-action="showCard">
                        <div class="qv-details-name">
                            <div class="qv-text">
                                <span class="q-ellipsis ng-binding" title="{{ $item->DESCRICAO }}">{{ $item->DESCRICAO }}</span>
                            </div>
                        </div>
                        <div class="qv-details-info ng-scope">
                            <i class="lui-icon lui-icon--info" q-title-translation="Hub.Tooltip.Details" title="Detalhes"></i>
                        </div>
                    </div>
                </div>
            </div>
        </li>

    @endforeach

</ul>



@include('admin._11100.include.modal-projeto')

@endsection

@section('script')
    <script src="{{ elixir('assets/js/_11100.js') }}"></script>
@endsection
