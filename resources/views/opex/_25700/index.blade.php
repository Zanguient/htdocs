@extends('master')

@section('estilo')
    <link rel="stylesheet" href="{{ elixir('assets/css/11000.css') }}" />
    
@endsection

@section('titulo')
    {{ Lang::get('opex/_25700.titulo') }}
@endsection

@section('conteudo')

<button type="button" class="btn btn-success postprint" data-hotkey="f10" data-loading-text="{{ Lang::get('master.gravando') }}"><span class="glyphicon glyphicon-ok"></span> PostPrint</button>
<button type="button" class="btn btn-danger addgraf" data-hotkey="f11" data-loading-text="{{ Lang::get('master.cancelar') }}"><span class="glyphicon glyphicon-ban-circle"></span> GetPrint</button>

<div></div>
    <label>Codigo:</label>
    <div class="textarea-grupo" >
        <textarea name="class-p-a-oque" class="form-control codigo normal-case" rows="5" cols="100" required>
;HARDWARE OPTIONS
O

;DELETA PROGRAMA DA MEMORIA
FK"ETQPROD"
FS"ETQPROD"
C0,4,N,+1,"CONTADOR"

;VELOCIDADE DE IMPRESSAO
S4
;DENSIDADE
D10

;ORIENTACAO (B-BASE/T-TOPO)
;ZT
ZB

;DASABILITA AVANÇO DA ETIQ APOS IMPRESSAO
;JB
;HABILITA AVANÇO DA ETIQ APOS IMPRESSAO
JF

;ALTURA DA ETIQUETA
;Q614,27
Q520,10
;COMPRIMENTO DA ETIQUETA
;q784
q830

;SET DOUBLE BUFFER MODE
rN
;SET REFERENCE POINT
R9,5

;CARREGA IMAGEM DA MEMORIA
GG10,02,"#IMAGEM"

A720,5,0,1,1,1,N,"EID:159"

A290,180,3,4,2,1,R,"#PEDIDO#"
A345,180,3,3,3,2,R,"#CLIENTE_ETIQUETA#"
B410,10,0,1A,2,2,60,N,"#CODIGO1"

A410,80,0,3,1,1,N,"DATA/HORA:#DATAHORA"
A410,110,0,3,1,1,N,"OPR:#OPERADOR_ID #OPERADOR_NOME"
A410,140,0,3,1,1,N,"#VIA   TAL:#CONTROLE"
A410,170,0,3,1,1,N,"SKU:#SKU"

B400,405,0,1A,2,1,60,N,"#CODIGO2"

A20,200,0,4,1,3,N,"#PRODUTO2"

A365,275,0,2,1,1,R," QUANTIDADE/QUANTITY "
A430,300,0,4,2,4,N,"#QUANTIDADE"
LO360,270,260,5
LO615,270,5,120
LO360,270,5,120
LO360,390,260,5

A22,275,0,2,1,1,R,"    TAMANHO/SIZE     USA   "
A65,310,0,5,2,3,N,"#TAMANHO"
A260,300,0,4,1,3,N,"#TAM_USA"
A240,370,0,2,1,1,R,"   EUA   "
A260,400,0,4,1,3,N,"#TAM_EUR"
LO20,270,330,5
LO345,270,5,200
LO235,270,5,200
LO20,270,5,200
LO20,470,330,5

A625,280,0,3,1,1,N,"CTR:#NUMERO_TALAO"
A625,310,0,3,1,1,N,"REM:#REMESSA"
A625,340,0,3,1,1,N,"GP:#GP_DESCRICAO"



A20,480,0,1,1,1,R," *MARCA DELFA BOJOS*  *BRAND DELFA CUPS* *MADE IN BRAZIL* *FABRIQUE EM BRASIL*"

;FINALIZA GRAVACAO PROGRAMA
FE

;CARREGA PROGRAMA
FR"ETQPROD"
?
#VOLUME_PEDIDO
P1

FK"*"
;GK"*"
        </textarea>
    </div>

    <div id="print_form">
      <button type="button" class="btn btn-lg btn-primary postprint" value="Print">Imprimir</button>
    </div>

@endsection
@section('script')

    <script src="{{ asset('assets/js/BrowserPrint-1.0.2.min.js') }}"></script>
    <script src="{{ asset('assets/js/PrintZebra.js') }}"></script>
  
@endsection
