@extends('master')


@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/25800.css') }}" />
    @include('helper.include.css.clock')
@endsection

@section('titulo')
    {{ Lang::get('opex/_25800.edit-titulo') }}
@endsection


@section('conteudo')
   <input type="hidden" class="auto-inicia" value="{{ $auto }}">
   <input type="hidden" class="auto-id" value="{{ $id }}">
   <input type="hidden" class="auto-desc" value="{{ $desc }}">
   <input type="hidden" class="familia" value="{{ $familia }}">
   
   @php if($estab > 0){ echo '<input type="hidden" class="estab" value="'.$estab.'">'; }

   @include('opex._25800.include.padrao')
   @include('opex._25800.include.menu')
   @include('opex._25800.include.modal')
   @include('helper.include.view.progress')
   @include('helper.include.view.progress2')

       @section('popup-body')
       <div class="desc-cepo">
           Atenção! Horário de giro do CEPO<br>
           Cor do horário:<span class="cor-cepo"></span><br>
           <span class="hora-cepo"></span>
       </div>
       @endsection
       
         <div id="gui" style="display: none;"></div>
         
            <div id="canvas-container" class="canvas-container">
                <div class="div-trofeu trofeu3-fogos"></div>
            </div>
   
@endsection
        
@section('script')

    @include('helper.include.js.termometro')
    <script src="{{ elixir('assets/js/_25800.js') }}"></script>
    <script src="/js/dat.gui.min.js"></script>
    <script src="/js/index.js"></script>
    
@append

