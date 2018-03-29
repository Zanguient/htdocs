@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/16010.css') }}" />
@endsection

@section('titulo')
    {{ Lang::get('patrimonio/_16010.titulo') }}
@endsection

@section('conteudo')

    <div id="tabs">			
        <fieldset class="tab-container">
            <ul id="tab" class="nav nav-tabs" role="tablist"> 
                <li role="presentation" class="active" id="rtab1">
                    <a href="#tab1" id="talao-produzir-tab" role="tab" data-toggle="tab" aria-controls="tab1" aria-expanded="true">
                        {{ Lang::get('patrimonio/_16010.tab1') }}
                    </a>
                </li> 
                <li role="presentation" id="rtab2">
                    <a href="#tab2" id="talao-produzido-tab" role="tab" data-toggle="tab" aria-controls="tab2" aria-expanded="false">
                        {{ Lang::get('patrimonio/_16010.tab2') }}
                    </a>
                </li>
                <li role="presentation" id="rtab3">
                    <a href="#tab3" id="talao-produzido-tab" role="tab" data-toggle="tab" aria-controls="tab3" aria-expanded="false">
                        {{ Lang::get('patrimonio/_16010.tab3') }}
                    </a>
                </li>
                <li role="presentation" id="rtab4">
                    <a href="#tab4" id="talao-produzido-tab" role="tab" data-toggle="tab" aria-controls="tab4" aria-expanded="false">
                        {{ Lang::get('patrimonio/_16010.tab4') }}
                    </a>
                </li>
                <li role="presentation" id="rtab5">
                    <a href="#tab5" id="talao-produzido-tab" role="tab" data-toggle="tab" aria-controls="tab5" aria-expanded="false">
                        {{ Lang::get('patrimonio/_16010.tab5') }}
                    </a>
                </li>
                <li role="presentation" id="rtab6">
                    <a href="#tab6" id="talao-produzido-tab" role="tab" data-toggle="tab" aria-controls="tab6" aria-expanded="false">
                        {{ Lang::get('patrimonio/_16010.tab6') }}
                    </a>
                </li>
            </ul>
            <div id="tab-content" class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="tab1" aria-labelledby="tab1-tab">
                    1
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab2" aria-labelledby="tab2-tab">
                    2
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab3" aria-labelledby="tab3-tab">
                    3
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab4" aria-labelledby="tab4-tab">
                    4
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab5" aria-labelledby="tab5-tab">
                    5
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab6" aria-labelledby="tab6-tab">
                    6
                </div>
            </div>
        </fieldset>

    </div>


@endsection

@section('script')
    <script src="{{ elixir('assets/js/_16010.js') }}"></script>
@endsection
