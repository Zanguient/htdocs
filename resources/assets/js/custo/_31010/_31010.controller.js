angular
    .module('app')
    .value('gScope', {})
    .controller('Ctrl', Ctrl);
    
    
	Ctrl.$inject = [
        '$scope',
        '$timeout',
        'Modelo',
        'Cor',
        'Tamanho',
        'Ficha',
        'ItenCusto',
        'gScope',
        '$compile',
        '$consulta',
        '$ajax',
        '$q',
        '$rootScope'
    ];

	function Ctrl( 
        $scope, 
        $timeout, 
        Modelo, 
        Cor,
        Tamanho,
        Ficha,
        ItenCusto,
        gScope,
        $compile,
        $consulta,
        $ajax,
        $q,
        $rootScope
    ) {

	var vm = this;

    gScope.scope = $scope;

    vm.ID = 0;
    vm.Descricao = '';

    vm.MARGEM = 100;
    gScope.MARGEM = vm.MARGEM;

    vm.ListaItens = [];
    gScope.ListaItens = vm.ListaItens;

    vm.Item       = {};
    vm.MarckUp    = 0;
    vm.MargemContribuicao = 0;
    vm.Comicao    = 0;
    vm.Total      = {
        Quantidade: 0,
        Custo:0,
        CustoT : 0,
        UnidadeMedida: '',
        Venda : 0,
        Despesa: 0
    };

    vm.FLAG_RECALCULAR = false;

    vm.PERC_FATURAMENTO = {
        VALOR : 0,
        FLAG  : 0
    };

    gScope.PERC_FATURAMENTO = vm.PERC_FATURAMENTO;

    vm.DataInvalida = true;

    gScope.Item    = vm.Item;
    gScope.Total   = vm.Total;
    gScope.MarckUp = vm.MarckUp;

    vm.Lista = {
        MODELO : [],
        COR    : [],
        TAMANHO: [],
        FICHA  : []
    };

    vm.LISTA_ANO  = [];
    vm.LISTA_MES  = [];
    vm.LISTA_ANO2 = [];
    vm.LISTA_MES2 = [];

    vm.DATA = {
        ANO : 0,
        MES : 0,
        ANO2: 0,
        MES2: 0
    };

    vm.Filtro  = new Filtro();
    vm.Modelo  = new Modelo();
    vm.Cor     = new Cor();
    vm.Tamanho = new Tamanho();
    vm.Ficha   = new Ficha();
    vm.Custo   = new ItenCusto();

    vm.Fatores = {
        Frete: {
            Tipo: 'FOB',
            Valor: 0
        },
        ConsiderarPerdas: true,
        MarckUp: 100,
    }

    gScope.Fatores = vm.Fatores;
    gScope.DATA    = vm.DATA;

    vm.Frete = {
        DADOS : [],
        CALCULADO : false,
        PERCENTUAL: 0
    };

    gScope.Frete = vm.Frete;

    vm.FRETES = [];
    vm.FRETES.push({Tipo: 'FOB', Valor: 0});
    vm.FRETES.push({Tipo: 'CIF - Rodoviário', Valor: 200});
    vm.FRETES.push({Tipo: 'CIF - Aéreo', Valor: 500});

    function init(){

        var id = $('._id_simulacao').val();

        vm.consultarIncentivo();

        vm.MontarListaMes();
        vm.MontarListaAno();
        vm.MontarListaMes2();
        vm.MontarListaAno2();

        vm.CalcularMeses();

        if(id > 0){
            vm.ID = id;

            vm.ConsultaSimular.selected.DESCRICAO = '';
            vm.ConsultaSimular.selected.ID        = id;

            vm.Simulacao().then(function(){
                
            });

        }
    }

    function pad(n, width, z) {
       z = z || '0';
       n = n + '';
       return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
    }


    vm.ReplicarTamanhos = function(item){

        var tamanhos = item.ConsultaTamanho.selected.LISTA   + '';
        var replicas = item.ConsultaTamanho.selected.REPLICA + '';

        var res = tamanhos.split(",");
        var rep = replicas.split("#@#");

        angular.forEach(rep, function(iten, key) {
            
            var res = iten.split("#$#");

            var dados = {
                REPLICA   : '',
                LISTA     : res[0],
                ID        : res[1],
                GRADE_ID  : res[2],
                DESCRICAO : res[3],
            };

            vm.Custo.getNew(true);

            var obj = gScope.ListaItens[gScope.ListaItens.length - 1];

            obj.ConsultaModelo.setSelected(  angular.copy(item.ConsultaModelo.selected)  , angular.copy(item.ConsultaModelo.Input.value)  );
            obj.ConsultaCor.setSelected(     angular.copy(item.ConsultaCor.selected)     , angular.copy(item.ConsultaCor.Input.value)     );
            obj.ConsultaTamanho.setSelected( dados , res[3]);

            obj.CALCULADO = 0;
            obj.consultarTempo(1);

        });

        //vm.RemoverItem(item);

    }

    vm.Simulacao = function(){

        return $q(function(resolve,reject){

            var ds = {
                    ID : vm.ConsultaSimular.selected.ID
                };

            $ajax.post('/_31010/Simulacao',ds,{contentType: 'application/json'})
                .then(function(response) {

                    vm.ListaItens = [];
                    gScope.ListaItens = [];

                    vm.add_confime = 1;
                    
                    if(response.PARAMETROS.length > 0){

                        var paran = response.PARAMETROS[0];

                        paran.MERCADO           = paran.MERCADO         == null ? '{}' : paran.MERCADO; 
                        paran.MERCADO_ITENS     = paran.MERCADO_ITENS   == null ? '{}' : paran.MERCADO_ITENS; 
                        paran.TRANSPORTADORA    = paran.TRANSPORTADORA  == null ? '{}' : paran.TRANSPORTADORA; 
                        paran.CLIENTE           = paran.CLIENTE         == null ? '{}' : paran.CLIENTE; 
                        paran.CIDADE            = paran.CIDADE          == null ? '{}' : paran.CIDADE; 
                        paran.FRETE             = paran.FRETES          == null ? '{}' : paran.FRETES; 
                        paran.DATAS             = paran.DATAS           == null ? '{}' : paran.DATAS; 
                        paran.FATORES           = paran.FATORES         == null ? '{}' : paran.FATORES; 
                        paran.ITENS             = paran.ITENS           == null ? '{}' : paran.ITENS; 
                        paran.MARGEM            = paran.MARGEM          == null ? 0    : Number(paran.MARGEM); 
                        paran.ID                = paran.ID              == null ? 0    : Number(paran.ID); 
                        paran.DESCRICAO         = paran.DESCRICAO       == null ? ''   : paran.DESCRICAO; 

                        var MERCADO         = JSON.parse(paran.MERCADO);
                        var MERCADO_ITENS   = JSON.parse(paran.MERCADO_ITENS);
                        var TRANSPORTADORA  = JSON.parse(paran.TRANSPORTADORA);
                        var CLIENTE         = JSON.parse(paran.CLIENTE);
                        var CIDADE          = JSON.parse(paran.CIDADE);
                        var FRETE           = JSON.parse(paran.FRETE);
                        var DATAS           = JSON.parse(paran.DATAS);
                        var FATORES         = JSON.parse(paran.FATORES);
                        var ITENS           = JSON.parse(paran.ITENS);
                        var MARGEM          = paran.MARGEM;
                        var DESCRICAO       = paran.DESCRICAO;

                        vm.ConsultaPadrao.setSelected(MERCADO);
                        vm.Consultatransportadora.setSelected(TRANSPORTADORA);
                        vm.ConsultaCliente.setSelected(CLIENTE);
                        vm.ConsultaCidade.setSelected(CIDADE);

                        vm.Descricao    = DESCRICAO;
                        vm.Frete        = FRETE;
                        vm.Fatores      = FATORES;
                        vm.DATA         = DATAS;
                        vm.PadraoItem   = MERCADO_ITENS;
                        vm.MARGEM       = MARGEM;

                        gScope.Descricao    = vm.Descricao;
                        gScope.Frete        = vm.Frete;
                        gScope.Fatores      = vm.Fatores;
                        gScope.DATA         = vm.DATA;
                        gScope.PadraoItem   = vm.PadraoItem;
                        gScope.MARGEM       = vm.MARGEM;

                        var link = encodeURI(urlhost + '/_31010?SIMULACAO_ID='+paran.ID);
                        window.history.replaceState(document.title, 'Title', link);

                        vm.ConsultaSimular.setSelected({ID: paran.ID, DESCRICAO: paran.DESCRICAO});
                    }

                    if(response.ITENS.length > 0){
                        var itens = response.ITENS[0].VALOR;

                            itens = itens == null ? '{}' : itens;
                            itens = JSON.parse(itens);

                            angular.forEach(itens, function(iten, key) {
                                
                                vm.Custo.getNew(true);
                                vm.ListaItens = gScope.ListaItens;

                                var obj = vm.ListaItens[vm.ListaItens.length - 1];

                                iten.MODELO             = iten.MODELO         == null ? '{}' : iten.MODELO ; 
                                iten.TAMANHO            = iten.TAMANHO        == null ? '{}' : iten.TAMANHO;  
                                iten.COR                = iten.COR            == null ? '{}' : iten.COR; 
                                iten.QUANTIDADE         = iten.QUANTIDADE     == null ? 0    : Number(iten.QUANTIDADE); 
                                iten.PRECO_VENDA        = iten.PRECO_VENDA    == null ? 0    : Number(iten.PRECO_VENDA); 
                                iten.MARGEM             = iten.MARGEM         == null ? 0    : Number(iten.MARGEM) ; 
                                iten.PRODUTO_TROCA      = iten.PRODUTO_TROCA  == null ? ''   : iten.PRODUTO_TROCA; 
                                iten.LST_TROCA          = iten.LST_TROCA      == null ? []   : iten.LST_TROCA ;

                                obj.MUDAR_PRECO         = (iten.MUDAR_PRECO == true);
                                obj.MUDAR_CONTRIBUICAO  = (iten.MUDAR_CONTRIBUICAO == true);

                                obj.Ficha.PRODUTO_TROCA = iten.PRODUTO_TROCA;
                                obj.Ficha.LST_TROCA     = iten.LST_TROCA;
                                obj.Quantidade          = iten.QUANTIDADE;
                                obj.PrecoVenda          = iten.PRECO_VENDA;
                                obj.Contribuicao        = iten.MARGEM;

                                obj.ConsultaModelo.setSelected(iten.MODELO);
                                obj.ConsultaCor.setSelected(iten.COR);
                                obj.ConsultaTamanho.setSelected(iten.TAMANHO);

                                vm.ListaItens[vm.ListaItens.length - 1].CALCULADO = 0;
                            });
                     }

                     vm.RecalcularCusto();

                    resolve(true);
                },function(e){
                    reject(e);
                }
            );

        });    
    }

    vm.PrecoMedioVenda = function(item){

        return $q(function(resolve,reject){

            var ds = {
                    TAMANHO : item.ConsultaTamanho.selected,
                    MODELO  : item.ConsultaModelo.selected,
                    COR     : item.ConsultaCor.selected,
                    DATA    : vm.DATA
                };

            $ajax.post('/_31010/ConsultarPrecoVenda',ds,{contentType: 'application/json'})
                .then(function(response) {
                    item.MUDAR_PRECO = true;
                    item.PrecoVenda  = Number(Number(response).toFixed(2));

                    resolve(true);
                },function(e){
                    reject(e);
                }
            );

        });    
    }

    vm.excluirSimulacao = function(){

        if(vm.ConsultaSimular.selected.ID > 0){
            addConfirme('<h4>Confirmação</h4>',
                    'Excluir simulação "'+vm.ConsultaSimular.selected.ID+'-'+vm.ConsultaSimular.selected.DESCRICAO+'"?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){
                        $rootScope.$apply(function(){

                            var ds = {
                                ID  : vm.ConsultaSimular.selected.ID
                            };

                        $ajax.post('/_31010/excluirSimulacao',ds,{contentType: 'application/json'})
                            .then(function(response) {

                                vm.add_confime = 0;

                                vm.ConsultaSimular.apagar();

                                vm.ConsultaSimular.selected = {
                                    DESCRICAO : '',
                                    ID        : 0
                                };

                                var link = encodeURI(urlhost + '/_31010');
                                window.history.replaceState(document.title, 'Title', link); 

                                vm.InitVariaveis();

                                showSuccess('Simulação Excluida!');

                                resolve(true);
                            },function(e){
                                reject(e);
                            }
                        );                             
                                 
                        });
                    }}]     
            );

        }else{
            showErro('Selecione uma simulação');
        } 
    
    }

    vm.gravarSimulacao = function(){


        return $q(function(resolve,reject){

            if(vm.Descricao != ''){
                var itens = [];

                vm.ConsultaSimular.selected.DESCRICAO = vm.Descricao;

                angular.forEach(vm.ListaItens, function(iten, key) {
                    itens.push({
                        MODELO        : iten.ConsultaModelo.selected,
                        TAMANHO       : iten.ConsultaTamanho.selected, 
                        COR           : iten.ConsultaCor.selected,
                        QUANTIDADE    : iten.Quantidade,
                        PRECO_VENDA   : iten.PrecoVenda,
                        MARGEM        : iten.Contribuicao,
                        PRODUTO_TROCA : iten.Ficha.PRODUTO_TROCA,
                        LST_TROCA     : iten.Ficha.LST_TROCA,
                        MUDAR_PRECO         : iten.MUDAR_PRECO,
                        MUDAR_CONTRIBUICAO  : iten.MUDAR_CONTRIBUICAO  
                    });
                });  

                var ds = {
                        MERCADO         : JSON.stringify(vm.ConsultaPadrao.selected),
                        MERCADO_ITENS   : JSON.stringify(vm.PadraoItem),
                        TRANSPORTADORA  : JSON.stringify(vm.Consultatransportadora.selected),
                        CLINETE         : JSON.stringify(vm.ConsultaCliente.selected),
                        CIDADE          : JSON.stringify(vm.ConsultaCidade.selected),
                        FRETE           : JSON.stringify(vm.Frete),
                        DATAS           : JSON.stringify(vm.DATA),
                        FATORES         : JSON.stringify(vm.Fatores),
                        MARGEM          : vm.MARGEM,
                        DESCRICAO       : vm.ConsultaSimular.selected.DESCRICAO,
                        ID              : vm.ConsultaSimular.selected.ID,
                        ITENS           : JSON.stringify(itens)
                    };

                $ajax.post('/_31010/gravarSimulacao',ds,{contentType: 'application/json'})
                    .then(function(response) {

                        vm.ID = response;

                        vm.add_confime = 1;

                        var link = encodeURI(urlhost + '/_31010?SIMULACAO_ID='+ vm.ID);
                        window.history.replaceState(document.title, 'Title', link);

                        vm.ConsultaSimular.setSelected({ID: vm.ID , DESCRICAO: vm.ConsultaSimular.selected.DESCRICAO});

                        showSuccess('Simulação gravada!');

                        resolve(true);
                    },function(e){
                        reject(e);
                    }
                );
            }else{
                showErro('Descrição da simulação Inválida');
            }
        });    
    }

    vm.RemoverItem = function(item){
        angular.forEach(vm.ListaItens, function(iten, key) {
            if(iten == item){
                vm.ListaItens.splice(key, 1);
                vm.Custo.CalcularTotal();
            }
        });    
    }

    vm.MontarListaMes = function(){
        var data = new Date();
        var dia = data.getDate();
        var mes = data.getMonth() + 1;
        var ano = data.getFullYear();
        var tmp = 13;

        vm.DATA.MES = pad((mes - 1),2);

        if(vm.DATA.MES == '00'){
            vm.DATA.MES = '12';
        }

        for (var i = 0; i < 12; i++) {
            tmp = tmp - 1; 
            if(tmp > 0){
                vm.LISTA_MES.push(pad(tmp,2));
            }
        }
    };


    vm.MontarListaAno = function(){
        var data = new Date();
        var dia = data.getDate();
        var mes = data.getMonth() + 1;
        var ano = data.getFullYear();

        vm.DATA.ANO = pad(ano,4);

        if(vm.DATA.MES == '12'){
           vm.DATA.ANO =  pad((Number(ano) - 1),4);
        }

        for (var i = 0; i < 10; i++) {
            vm.LISTA_ANO.push(pad((ano - i),2));
        }
    };

    vm.MontarListaMes2 = function(){
        var data = new Date();
        var dia = data.getDate();
        var mes = data.getMonth() + 1;
        var ano = data.getFullYear();
        var tmp = 13;

        vm.DATA.MES2 = pad((mes - 1),2);

        if(vm.DATA.MES2 == '00'){
            vm.DATA.MES2 = '12';
        }

        for (var i = 0; i < 12; i++) {
            tmp = tmp - 1; 
            if(tmp > 0){
                vm.LISTA_MES2.push(pad(tmp,2));
            }
        }
    };

    vm.MontarListaAno2 = function(){
        var data = new Date();
        var dia = data.getDate();
        var mes = data.getMonth() + 1;
        var ano = data.getFullYear();

        vm.DATA.ANO2 = pad(ano,4);

        if(vm.DATA.MES2 == '12'){
           vm.DATA.ANO2 =  pad((Number(ano) - 1),4);
        }

        for (var i = 0; i < 10; i++) {
            vm.LISTA_ANO2.push(pad((ano - i),2));
        }
    };

    vm.CalcularMeses= function(){
        var ano1 =  vm.DATA.ANO;
        var ano2 =  vm.DATA.ANO2;

        var mes1 =  vm.DATA.MES;
        var mes2 =  vm.DATA.MES2;

        var ret = 0;

        if(ano1 <= ano2){
            if(ano2 == ano1){
                if(mes2 >= mes1){
                    ret = new Number(mes2) - Number(mes1);
                    vm.DataInvalida = false;
                }else{
                    vm.DataInvalida = true;
                }
            }else{
                ret = (Number(mes2) + 12) - Number(mes1);
                vm.DataInvalida = false;
            }
        }else{
            vm.DataInvalida = true;
        }

        vm.DATA.FATOR = ret + 1;

    };

    vm.keyupMarckUp = function(){
        angular.forEach(vm.ListaItens, function(item, key) {
            if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                item.CALCULADO = 0;
                item.calcularCusto(1);
            }
        }); 
    }

    vm.keyupMargem = function(){
        gScope.MARGEM = vm.MARGEM;

        angular.forEach(vm.ListaItens, function(item, key) {
            if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                item.CALCULADO = 0;
                item.calcularCusto(1);
            }
        }); 
    }

    vm.keyupComicao = function(){
        angular.forEach(vm.ListaItens, function(item, key) {
            if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                item.CALCULADO = 0;
                item.calcularCusto(1);
            }
        }); 
    }

    vm.AdicionarItensCusto = function(){

        if(vm.ListaItens.length == 0){
            vm.FLAG_RECALCULAR = false;
        }

        gScope.ListaItens = vm.ListaItens;

        vm.Custo.getNew();
        //var item = vm.Custo.getNew();
        //vm.ListaItens.push(item);

        //gScope.ListaItens = vm.ListaItens;
        //vm.ListaItens     = gScope.ListaItens;
    }

    $scope.$watch('vm.Fatores.ConsiderarPerdas', function (newValue, oldValue, scope) {
        if(newValue != oldValue){
            angular.forEach(vm.ListaItens, function(item, key) {
                if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                    item.CALCULADO = 0;
                    item.calcularCusto(1);
                }
            }); 
        }
    }, true);

    $scope.$watch('vm.DATA.ANO', function (newValue, oldValue, scope) {
        if(newValue != oldValue){

            vm.CalcularMeses();

            vm.FLAG_RECALCULAR = true;
            vm.Frete.CALCULADO = false;
        }
    }, true);

    $scope.$watch('vm.DATA.MES', function (newValue, oldValue, scope) {
        if(newValue != oldValue){

            vm.CalcularMeses();

            vm.FLAG_RECALCULAR = true;
            vm.Frete.CALCULADO = false;
        }
    }, true);

    $scope.$watch('vm.DATA.ANO2', function (newValue, oldValue, scope) {
        if(newValue != oldValue){

            vm.CalcularMeses();

            vm.FLAG_RECALCULAR = true;
            vm.Frete.CALCULADO = false;
        }
    }, true);

    $scope.$watch('vm.DATA.MES2', function (newValue, oldValue, scope) {
        if(newValue != oldValue){

            vm.CalcularMeses();

            vm.FLAG_RECALCULAR = true;
            vm.Frete.CALCULADO = false;
        }
    }, true);
    
    vm.DetalharCusto = function(item,flag){
        if(item.Ficha.ITENS.length > 0){

            $('.img-loading').css('display','block');

            vm.Item = item;
            gScope.Item = vm.Item;
            vm.MontarGrafico();

            //vm.Item.Ficha.ConsultarConfiguracao();
            
            $('#modal-detalhar').modal();

        }else{
            showErro('Produto sem dados de Custo');
        }
    }

    vm.DetalharNivel = function(item,flag){

        if(flag == 9){
            vm.Ficha.ConsultarAbsorcao(1);
            $('#modal-absorcao').modal();
        }

        if(flag == 8){
            vm.Ficha.ConsultarProprio(1);
            $('#modal-proprio').modal();
        }

        if(flag == 4){
            //vm.Item.Ficha.consultar(1);
            $('#modal-materia').modal();
        }

        if(flag == 7){
            vm.Item.Ficha.MaoDeObra(1);
            $('#modal-maodeobra').modal();
        }

        if(flag == 3){
            vm.Item.Ficha.Despesa(1);
            $('#modal-despesas').modal();
        }
    }

    vm.export1 = function(tabela,nome){
        exportTableToCsv(nome, tabela);
    };

    vm.export2 = function(tabela,nome){
        exportTableToXls(nome, tabela);
    };

    vm.Imprimir = function(div,descricao){
        var user = $('#usuario-descricao').val();
        var filtro = 'Modelo:' + vm.Item.ConsultaModelo.selected.DESCRICAO + '    Cor:' + vm.Item.ConsultaCor.selected.DESCRICAO + '    Tamanho:' + vm.Item.ConsultaTamanho.selected.DESCRICAO;
        printHtml(div, 'Custos Gerenciais - ' + descricao, filtro, user, '1.0.0',1,'');
    }            

    vm.FecharCusto = function(item){
        $('.google-visualization-orgchart-table').remove();
    }

    vm.MontarGrafico = function(){
        
        google.charts.load('current', {packages:["orgchart"]});
        google.charts.setOnLoadCallback(drawChart);

        that._controller   = function(){return $('#main').find('[ng-controller]')};
        var scope = that._controller().scope();

        //$('#chart_div').empty();

        var obj = $('#chart_div');
        var pai = $(obj).closest('.ficha');

        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('string', 'Manager');
            data.addColumn('string', 'ToolTip');

            var btn01 = '';//<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,01)">Detalhar</button>';
            var btn02 = '';//<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,02)">Detalhar</button>';
            var btn03 = '<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,03)">Detalhar</button>';
            var btn04 = '<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,04)">Detalhar</button>';
            var btn05 = '';//'<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,05)">Detalhar</button>';
            var btn06 = '';//'<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,06)">Detalhar</button>';
            var btn07 = '<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,07)">Detalhar</button>';
            var btn08 = '<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,08)">Detalhar</button>';
            var btn09 = '<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,09)">Detalhar</button>';
            var btn10 = '';//'<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,10)">Detalhar</button>';
            var btn11 = '';//'<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,11)">Detalhar</button>';
            var btn12 = '';//<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,12)">Detalhar</button>';
            var btn13 = '';//'<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,11)">Detalhar</button>';
            var btn14 = '';//<button type="button" class="btn btn-primary " ng-click="vm.DetalharNivel(vm.Item,12)">Detalhar</button>';

            // For each orgchart box, provide the name, manager, and tooltip to show.

            var divInicio = '<div style="color: red; font-weight: bold; font-size: 16px;">';
            var spanInicio = '<span style="color: black; font-weight: bold; font-size: 13px;">';
            var roll = [
              [{v:'Gasto'            ,  f:'Gasto'+divInicio+'{{vm.Item.Gasto.Valor | number:5}}<br>'+btn01+'</span></div>'}, '', ''],
              [{v:'Custo'            ,  f:'Custo'+divInicio+'{{vm.Item.Gasto.Custo.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn02+'</span></div>'}, 'Gasto', ''],
              [{v:'Despesa'          ,  f:'Despesa'+divInicio+'{{vm.Item.Gasto.Despesa.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Despesa.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn14+'</span></div>'}, 'Gasto', ''],
              [{v:'Contas'           ,  f:'Contas'+divInicio+'{{vm.Item.Gasto.Despesa.Contas | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Despesa.Contas / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn03+'</span></div>'}, 'Despesa', ''],
              [{v:'Tributos'         ,  f:'Tributos'+divInicio+'{{vm.Item.Gasto.Despesa.Tributos | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Despesa.Tributos / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn13+'</span></div>'}, 'Despesa', ''],
              [{v:'MatariaPrima'     ,  f:'Matéria-prima'+divInicio+'{{vm.Item.Gasto.Custo.MateriaPrima.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.MateriaPrima.Valor/ vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn04+'</span></div>'}, 'Direto', ''],
              [{v:'Direto'           ,  f:'Direto'+divInicio+'{{vm.Item.Gasto.Custo.Direto.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Direto.Valor  / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn05+'</span></div>'}, 'Custo', ''],
              [{v:'Indireto'         ,  f:'Indireto'+divInicio+'{{vm.Item.Gasto.Custo.Indireto.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Indireto.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn06+'</span></div>'}, 'Custo', ''],
              [{v:'MaoObraDireta'    ,  f:'Mão de Obra'+divInicio+'{{vm.Item.Gasto.Custo.Direto.MaoObraDireta.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Direto.MaoObraDireta.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn07+'</span></div>'}, 'Direto', ''],
              [{v:'Proprio'          ,  f:'Próprio'+divInicio+'{{vm.Item.Gasto.Custo.Indireto.Proprio.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Indireto.Proprio.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn08+'</span></div>'}, 'Indireto', ''],
              [{v:'Absorvido'        ,  f:'Absorvido'+divInicio+'{{vm.Item.Gasto.Custo.Indireto.Absorvido.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Indireto.Absorvido.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn09+'</span></div>'}, 'Indireto', ''],
              [{v:'CustoSetup'       ,  f:'Custo Setup'+divInicio+'{{vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoSetup.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoSetup.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn10+'</span></div>'}, 'MaoObraDireta', ''],
              [{v:'CustoOperacional' ,  f:'Custo Operacional'+divInicio+'{{vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional.Valor | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional.Valor / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn11+'</span></div>'}, 'MaoObraDireta', ''],
              [{v:'CustoOeSAbsorvido',  f:'Absorvido'+divInicio+'{{vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional.Valor2 + vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoSetup.Valor2 | number:5}} '+spanInicio+'{{((vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoOperacional.Valor2 + vm.Item.Gasto.Custo.Direto.MaoObraDireta.CustoSetup.Valor2) / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn11+'</span></div>'}, 'MaoObraDireta', '']
            ];

            angular.forEach(vm.Item.Gasto.Despesa.Itens, function(iten, key) {
                
                roll.push([{
                    v: iten.DESCRICAO, 
                    f: iten.DESCRICAO + divInicio + '{{vm.Item.Gasto.Despesa.Itens['+key+'].VALOR | number:5}} '+spanInicio+'{{(vm.Item.Gasto.Despesa.Itens['+key+'].VALOR / vm.Item.Gasto.Valor) * 100 | number:2}}%<br>'+btn12+'</span></div>'},
                    'Tributos', '']
                );               

            });

            data.addRows(roll);

            var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
            chart.draw(data, {allowHtml:true});

            $timeout(function(){
            
                $('.img-loading').css('display','none');
                $(obj).replaceWith( $compile($(obj).html())(scope) );
                $(pai).append('<div style="width: 99%; height: 100%;" id="chart_div"></div>');

                ///$scope.$apply(function () {
                //    $scope.message = "Timeout called!";
                //});

            },200);
        }
    }

    vm.Consulta = new $consulta();
                
    vm.Consultatransportadora                        = vm.Consulta.getNew(true);
    vm.Consultatransportadora.componente             = '.consulta-frete-transportadora';
    vm.Consultatransportadora.model                  = 'vm.Consultatransportadora';
    vm.Consultatransportadora.option.label_descricao = 'Transportadora:';
    vm.Consultatransportadora.option.obj_consulta    = '/_14020/api/transportadora';
    vm.Consultatransportadora.option.tamanho_input   = 'input-maior';
    vm.Consultatransportadora.option.campos_tabela   = [['TRANSPORTADORA_ID', 'Id'],['RAZAOSOCIAL','Razão Social'],['NOMEFANTASIA', 'Nome Fantasia'],['CLASSIFICACAO','CLASSIFICAÇÃO']];
    vm.Consultatransportadora.option.obj_ret         = ['TRANSPORTADORA_ID','RAZAOSOCIAL','CLASSIFICACAO'];
    vm.Consultatransportadora.option.required        = true;
    vm.Consultatransportadora.compile();

    vm.Consultatransportadora.onSelect = function() {
        vm.Frete.CALCULADO = false;
    };

    vm.Consultatransportadora.onClear = function() {
        vm.Frete.CALCULADO = false;
    };  

    vm.ConsultaCliente                        = vm.Consulta.getNew(true);
    vm.ConsultaCliente.componente             = '.consulta-cliente';
    vm.ConsultaCliente.model                  = 'vm.ConsultaCliente';
    vm.ConsultaCliente.option.label_descricao = 'Cliente:';
    vm.ConsultaCliente.option.obj_consulta    = '/_14020/api/cliente';
    vm.ConsultaCliente.option.tamanho_input   = 'input-maior';
    vm.ConsultaCliente.option.tamanho_tabela  = 780;
    vm.ConsultaCliente.option.campos_tabela   = [['ID', 'Id'],['RAZAOSOCIAL','Razão Social'],['NOMEFANTASIA', 'Nome Fantasia'],['UF','UF'],['CIDADE','Cidade']];
    vm.ConsultaCliente.option.obj_ret         = ['ID','RAZAOSOCIAL'];
    vm.ConsultaCliente.option.required        = false;
    vm.ConsultaCliente.compile();

    vm.ConsultaCliente.onSelect = function() {
        vm.Frete.CALCULADO = false;
        vm.ConsultaCidade.setSelected({ID: 0, DESCRICAO: vm.ConsultaCliente.CIDADE, UF: vm.ConsultaCliente.UF, FILTRO: ''}, vm.ConsultaCliente.UF + ' - ' + vm.ConsultaCliente.CIDADE);
    };

    vm.ConsultaCliente.onClear = function() {
        vm.Frete.CALCULADO = false;

        if(vm.ConsultaCidade.item.selected == true){
            vm.ConsultaCidade.apagar(true);
        }
    };  

    vm.ConsultaCidade                        = vm.Consulta.getNew(true);
    vm.ConsultaCidade.componente             = '.consulta-cidade';
    vm.ConsultaCidade.model                  = 'vm.ConsultaCidade';
    vm.ConsultaCidade.option.label_descricao = 'Cidade:';
    vm.ConsultaCidade.option.obj_consulta    = '/_14020/api/cidade';
    vm.ConsultaCidade.option.campos_tabela   = [['UF', 'UF'],['DESCRICAO','Cidade']];
    vm.ConsultaCidade.option.obj_ret         = ['UF','DESCRICAO'];
    vm.ConsultaCidade.option.required        = true;
    vm.ConsultaCidade.compile();

    vm.ConsultaCidade.onSelect = function() {
        vm.Frete.CALCULADO = false;
    };

    vm.ConsultaCidade.onClear = function() {
        vm.Frete.CALCULADO = false;

        if(vm.ConsultaCliente.item.selected == true){
            vm.ConsultaCliente.apagar(true);
        }              
    };

    vm.ConsultaSimular = vm.Consulta.getNew();
    vm.ConsultaSimular.componente                  = '.consultar-simulacao';
    vm.ConsultaSimular.option.class                = 'consultar-simulacao-class';
    vm.ConsultaSimular.model                       = 'vm.ConsultaSimular';
    vm.ConsultaSimular.option.label_descricao      = 'Simulação:';
    vm.ConsultaSimular.option.obj_consulta         = '/_31010/ConsultarSimulacao';
    vm.ConsultaSimular.option.tamanho_input        = 'input-maior';
    vm.ConsultaSimular.option.campos_tabela        = [['ID','ID'],['DESCRICAO','DESCRIÇÃO'],['DATA_HORA','DATA']];
    vm.ConsultaSimular.option.tamanho_tabela       = 690;
    vm.ConsultaSimular.autoload                    = false;
    vm.ConsultaSimular.compile();


    vm.ConsultaSimular.selected = {
        DESCRICAO : '',
        ID        : 0
    };

    vm.ConsultaSimular.onSelect= function(){

        if(vm.ConsultaSimular.selected.ID > 0){

            vm.Descricao = vm.ConsultaSimular.selected.DESCRICAO;
            vm.ID        = vm.ConsultaSimular.selected.ID;

            vm.Simulacao().then(function(){
                
            });
        }
    }

    vm.add_confime = 0;

    vm.ConsultaSimular.onClear = function(){

        if(vm.add_confime > 0){

            vm.add_confime = 0;

            addConfirme('<h4>Confirmação</h4>',
                    'Limpar itens da simulação?',
                    [obtn_sim,obtn_nao],
                    [{ret:1,func:function(){
                        $rootScope.$apply(function(){

                            vm.InitVariaveis();                               
                                 
                        });
                    }}]     
            );
        };

        vm.Descricao    = '';
        vm.ID = 0;
        vm.ConsultaSimular.selected = {
            DESCRICAO : '',
            ID        : 0
        };

        var link = encodeURI(urlhost + '/_31010');
        window.history.replaceState(document.title, 'Title', link); 
    }
    
    vm.InitVariaveis = function(){
        vm.ConsultaPadrao.apagar();
        vm.Consultatransportadora.apagar();
        vm.ConsultaCliente.apagar();
        vm.ConsultaCidade.apagar();
        vm.ListaItens = []; 

        vm.Descricao    = '';
        
        vm.Frete = {
            DADOS : [],
            CALCULADO : false,
            PERCENTUAL: 0
        };

        vm.Fatores = {
            Frete: {
                Tipo: 'FOB',
                Valor: 0
            },
            ConsiderarPerdas: true,
            MarckUp: 100,
        };

        vm.DATA = {
            ANO : 0,
            MES : 0,
            ANO2: 0,
            MES2: 0
        };

        vm.MontarListaMes();
        vm.MontarListaAno();
        vm.MontarListaMes2();
        vm.MontarListaAno2();
        vm.CalcularMeses();

        vm.PadraoItem = {};
        vm.MARGEM     = 100;

        gScope.Descricao    = vm.Descricao;
        gScope.Frete        = vm.Frete;
        gScope.Fatores      = vm.Fatores;
        gScope.DATA         = vm.DATA;
        gScope.PadraoItem   = vm.PadraoItem;
        gScope.MARGEM       = vm.MARGEM;
    }

    vm.ConsultaPadrao = vm.Consulta.getNew(true);
    vm.ConsultaPadrao.componente                  = '.consulta-padrao';
    vm.ConsultaPadrao.option.class                = 'custo-padrao';
    vm.ConsultaPadrao.model                       = 'vm.ConsultaPadrao';
    vm.ConsultaPadrao.option.label_descricao      = 'Mercado:';
    vm.ConsultaPadrao.option.obj_consulta         = '/_31010/custoPadrao';
    vm.ConsultaPadrao.option.tamanho_input        = 'input-maior';
    vm.ConsultaPadrao.option.campos_tabela        = [['ID','ID'],['FAMILIA_DESCRICAO','FAMÍLIA'],['DESCRICAO','DESCRIÇÃO']];
    vm.ConsultaPadrao.option.tamanho_tabela       = 690;
    vm.ConsultaPadrao.autoload                    = false;
    vm.ConsultaPadrao.compile();

    vm.ConsultaProduto = vm.Consulta.getNew(true);
    vm.ConsultaProduto.componente                  = '.consulta-produto';
    vm.ConsultaProduto.option.class                = 'produt-troca';
    vm.ConsultaProduto.model                       = 'vm.ConsultaProduto';
    vm.ConsultaProduto.option.label_descricao      = 'Produto:';
    vm.ConsultaProduto.option.obj_consulta         = '/_31010/consultarProduto';
    vm.ConsultaProduto.option.tamanho_input        = 'input-maior';
    vm.ConsultaProduto.option.campos_tabela        = [['ID','ID'],['DESCRICAO','DESCRIÇÃO']];
    vm.ConsultaProduto.option.tamanho_tabela       = 436;
    vm.ConsultaProduto.autoload                    = false;
    vm.ConsultaProduto.option.paran                = {MERCADO: vm.ConsultaPadrao.item.dados}; 
    vm.ConsultaProduto.compile();

    vm.ConsultaTamanho = vm.Consulta.getNew(true);
    vm.ConsultaTamanho.componente                  = '.consulta-tamanho';
    vm.ConsultaTamanho.option.class                = 'produt-troca';
    vm.ConsultaTamanho.model                       = 'vm.ConsultaTamanho';
    vm.ConsultaTamanho.option.label_descricao      = 'Tamanho:';
    vm.ConsultaTamanho.option.obj_consulta         = '/_31010/ConsultarTamanho2';
    vm.ConsultaTamanho.option.tamanho_input        = 'input-medio';
    vm.ConsultaTamanho.option.campos_tabela        = [['ID','ID'],['DESCRICAO','DESCRIÇÃO']];
    vm.ConsultaTamanho.option.tamanho_tabela       = 436;
    vm.ConsultaTamanho.autoload                    = false;
    vm.ConsultaTamanho.option.paran                = {PRODUTO: {GRADE_CODIGO : 0}}; 
    vm.ConsultaTamanho.compile();

    vm.ConsultaProduto.onSelect= function(){
        vm.Item.Ficha.NEW_PRODUTO =  vm.ConsultaProduto.item.dados;
        vm.ConsultaTamanho.option.paran = {PRODUTO: vm.ConsultaProduto.item.dados   , PADRAO: 1};

        vm.Item.Ficha.NEW_PRODUTO.TAMANHO = 0;

        vm.ConsultaTamanho.filtrar();
    }

    vm.ConsultaTamanho.onSelect= function(){
        vm.Item.Ficha.NEW_PRODUTO.TAMANHO =  vm.ConsultaTamanho.item.dados.ID;
        vm.Item.Ficha.NEW_PRODUTO.DESC_TAMANHO =  vm.ConsultaTamanho.item.dados.DESCRICAO;
        vm.ConsultaTamanho.option.paran   = {PRODUTO: vm.ConsultaProduto.item.dados   , PADRAO: 0};
    }

    vm.ConsultaProduto.onClear = function(){
        vm.Item.Ficha.NEW_PRODUTO =  {};
        vm.ConsultaTamanho.apagar();
        vm.ConsultaTamanho.option.paran = {PRODUTO: {GRADE_CODIGO : 0, MODELO_ID: 0, TAMANHO: 0}, PADRAO: 0};
    }

    vm.ConsultaTamanho.onClear = function(){
        vm.Item.Ficha.NEW_PRODUTO.TAMANHO = 0;
        vm.Item.Ficha.NEW_PRODUTO.DESC_TAMANHO = '0';
    }

    gScope.ConsultaProduto = vm.ConsultaProduto;
    gScope.ConsultaPadrao  = vm.ConsultaPadrao;
    vm.PadraoItem = {};
    gScope.PadraoItem = vm.PadraoItem;

    vm.ListaIncentivo = {};
    vm.consultarIncentivo = function(){

        var ds = {
                FLAG : 0
            };

        $ajax.post('/_31010/consultarIncentivo',ds,{contentType: 'application/json'})
            .then(function(response) {
                vm.ListaIncentivo = response;
                gScope.ListaIncentivo = vm.ListaIncentivo;
            }
        );
    };

    vm.consultarPadraoItens = function(){

        var ds = {
                PADRAO : vm.ConsultaPadrao.item.dados
            };

        $ajax.post('/_31010/custoPadraoItem',ds,{contentType: 'application/json'})
            .then(function(response) {
                vm.PadraoItem = response;

                angular.forEach(vm.PadraoItem, function(item, key) {
                    
                    item.FATOR      = Number(item.FATOR);
                    item.AVOS       = Number(item.AVOS);
                    item.PERCENTUAL = Number(item.PERCENTUAL);
                    item.FRETE      = Number(item.FRETE);
                    item.OLD_FRETE  = Number(item.OLD_FRETE);

                    if(item.USAR_FATOR == 1){
                        vm.CalcularFator(item);
                    }else{
                        item.VALOR      = Number(item.PERCENTUAL);
                    }
                });

                gScope.PadraoItem = vm.PadraoItem; 

                vm.FLAG_RECALCULAR = true;
                vm.Frete.CALCULADO = false; 
            }
        );
    };

    vm.RecalcularCusto = function(){

        $timeout(function() {
            vm.FLAG_RECALCULAR = false;

            angular.forEach(vm.ListaItens, function(item, key) {
                if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                    item.CALCULADO = 0;
                    item.consultarTempo(1);
                }
            });
        });

    }

    vm.recalcularPadrao = function(){
        angular.forEach(vm.ListaItens, function(item, key) {
            if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                item.CALCULADO = 0;
                item.calcularCusto(1);
            }
        });       
    };

    $scope.$watch('gScope.PadraoItem', function (newValue, oldValue, scope) {
        if(newValue != oldValue){
            vm.FLAG_RECALCULAR = true;
            vm.Frete.CALCULADO = false;
        }
    }, true);

    $scope.$watch('vm.Fatores.Incentivo', function (newValue, oldValue, scope) {
        if(newValue != oldValue){
            vm.recalcularPadrao();
        }
    }, true);

    vm.CalcularFator = function(item) {
        item.VALOR = ((Number(item.PERCENTUAL) / Number(item.AVOS)) * Number(item.FATOR));
        vm.recalcularPadrao();
    }

    vm.ConsultaPadrao.onSelect= function(){

        vm.PERC_FATURAMENTO.VALOR = 0;
        vm.PERC_FATURAMENTO.FLAG  = 0;

        vm.Fatores.Incentivo = ''+vm.ConsultaPadrao.selected.PERC_INCENTIVO+'';

        vm.ConsultaProduto.option.paran = {MERCADO: vm.ConsultaPadrao.item.dados}; 

        vm.PadraoItem = {};
        vm.consultarPadraoItens();

        angular.forEach(vm.ListaItens, function(item, key) {
            item.ConsultaModelo.disable(false);
            item.ConsultaCor.disable(false);
            item.ConsultaTamanho.disable(false);

            item.ConsultaModelo.option.paran = {MERCADO: gScope.ConsultaPadrao.item.dados}; 
        });
    };

    vm.ConsultaPadrao.onClear= function(){

        vm.PERC_FATURAMENTO.VALOR = 0;
        vm.PERC_FATURAMENTO.FLAG  = 0;

        vm.PadraoItem = {};   

        angular.forEach(vm.ListaItens, function(item, key) {
            item.ConsultaModelo.disable(true);
            item.ConsultaCor.disable(true);
            item.ConsultaTamanho.disable(true);
        });
    };
    
    vm.DetalharFrete = function(){
        $('#modal-frete').modal();
    }

    vm.LimparFrete = function(){
        vm.Frete.PERCENTUAL = 0;

        angular.forEach(vm.PadraoItem, function(item, key) {
            if(item.FRETE == 1){
                item.VALOR = item.OLD_FRETE;    
            }
        });

        vm.ReprocessarCusto();
    }

    vm.ReprocessarCusto = function(){
        angular.forEach(vm.ListaItens, function(item, key) {
            if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                item.CALCULADO = 0;
                item.calcularCusto();
            }
        });
    }

    vm.CalcularFrete = function() {
        
        var origem    = '';
        var origem_id = '';

        if( vm.ConsultaCliente.item.selected == true ) {
                origem = 'SIMULADOR';
                origem_id = vm.ConsultaCliente.selected.ID;
            } else {
                origem    = 'SIMULADOR_CIDADE';
                origem_id = vm.ConsultaCidade.selected.ID;
        }

        var itens = [];

        angular.forEach(vm.ListaItens, function(item, key) {
            if(!(item.ConsultaModelo.selected == null) && !(item.ConsultaCor.selected == null) && !(item.ConsultaTamanho.selected == null)){
                
                var tamanhos = item.ConsultaTamanho.selected.LISTA   + '';
                var replicas = item.ConsultaTamanho.selected.REPLICA + '';
                
                var res = tamanhos.split(",");
                var rep = replicas.split("#@#");

                if(res.length > 1){
                    angular.forEach(rep, function(iten, key) {
                        
                        var res = iten.split("#$#");

                        itens.push({
                            MODELO_ID       : item.ConsultaModelo.selected.ID,
                            COR_ID          : item.ConsultaCor.selected.ID,
                            TAMANHO         : res[1],
                            QUANTIDADE      : (item.Quantidade / rep.length),
                            VALOR_UNITARIO  : item.PrecoVenda  
                        });

                    });
                }else{
                    itens.push({
                        MODELO_ID       : item.ConsultaModelo.selected.ID,
                        COR_ID          : item.ConsultaCor.selected.ID,
                        TAMANHO         : item.ConsultaTamanho.selected.ID,
                        QUANTIDADE      : item.Quantidade,
                        VALOR_UNITARIO  : item.PrecoVenda  
                    });
                }

            }
        }); 

        var filtro = {
            ORIGEM            : origem,
            ORIGEM_ID         : origem_id,
            TRANSPORTADORA_ID : vm.Consultatransportadora.selected.TRANSPORTADORA_ID,
            ITENS             : itens,
            RETURN            : true
        };

        return $q(function(resolve, reject){
            $ajax.post('/_14020/api/frete/calcular',filtro).then(function(response){
                
                sanitizeJson(response);

                vm.Frete.DADOS = response;

                vm.Frete.CALCULADO = true;

                vm.Frete.ORIGEM    = vm.Frete.DADOS.ORIGEM;
                vm.Frete.ORIGEM_ID = vm.Frete.DADOS.ORIGEM_ID;

                vm.Frete.PERCENTUAL = Number(response.VALOR_FINAL) / Number(response.VALOR_TOTAL);
                vm.Frete.PERCENTUAL = vm.Frete.PERCENTUAL.toFixed(4);

                vm.Frete.DADOS.COMPOSICOES = [
                    {
                        DESCRICAO : 'Dados da Carga',
                        DADOS : response.DADOS_CARGA
                    },
                    {
                        DESCRICAO : 'Composição dos Valores',
                        DADOS : response.DADOS_COMPOSICAO
                    }                            
                ];

                angular.forEach(vm.PadraoItem, function(item, key) {
                    if(item.FRETE == 1){
                        item.VALOR = 0;    
                    }
                });


                vm.ReprocessarCusto();
                
                resolve(response);
            },function(e){
                reject(e);
            });
        });
    }    

    init();

}   
  