@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11000.css') }}" />
@endsection

@section('titulo')
    {{ Lang::get('opex/_25700.titulo') }}
@endsection

@section('conteudo')

<textarea name="class-p-a-oque" class="form-control codigo" rows="5" cols="100" required>{{$CODIGO}}</textarea>
            
@endsection

@section('script')
    <script src="{{ elixir('assets/js/_25700.js') }}"></script>
@endsection
