angular
    .module('app')
    .factory('Consulta', Consulta);
    

	Consulta.$inject = [
        '$ajax',
        '$httpParamSerializer',
        '$rootScope',
        'gScope',
        '$compile'
    ];

function Consulta($ajax, $httpParamSerializer, $rootScope, gScope, $compile) {

    /**
     * Constructor, with class name
     */
    function Consulta(data) {
        if (data) {
            this.setData(data);
        }
    }

    /**
     * Public method, assigned to prototype
     */
    var obj_Consulta = {

        Consulta: function(data) {
            if (data) {
                this.setData(data);
            }
        },
        MontarHtml: function(obj){

            var html = '';
            
            html += '<div class="consulta-container">';
            html += '    <div class="consulta">';
            html += '        <div class="form-group '+obj.getClassForm()+'">';
            html += '           <label for="consulta-descricao">'+obj.option.label_descricao+'</label>';
            html += '           <div class="input-group '+obj.option.class+'">';
            html += '               <input type="search" ng-focus="'+obj.model+'.Input.focus" ng-keydown="'+obj.model+'.InputKeydown($event)" name="consulta_descricao" class="form-control consulta-descricao '+obj.tamanho_Input+' objConsulta '+obj.getClassInput()+'" autocomplete="off" ng-required="'+obj.model+'.option.required" ng-readonly="'+obj.model+'.Input.readonly" ng-disabled="'+obj.model+'.Input.disabled" ng-model="'+obj.model+'.Input.value" />';            
            html += '               <button type="button" ng-click="'+obj.model+'.apagar()" class="input-group-addon btn-filtro btn-apagar-filtro btn-apagar-filtro-consulta search-button" style="display: block !important;" ng-if="'+obj.model+'.btn_apagar_filtro.visivel" ng-disabled="'+obj.model+'.btn_apagar_filtro.disabled"  tabindex="-1" ><span class="fa fa-close"></span></button>';
            html += '               <button type="button" ng-click="'+obj.model+'.filtrar()" class="input-group-addon btn-filtro btn-filtro-consulta search-button '+obj.getClassButton()+'" disabled tabindex="-1"  style="display: block !important;" ng-if="'+obj.model+'.btn_filtro.visivel" ng-disabled="'+obj.model+'.btn_filtro.disabled"><span class="fa fa-search"></span></button>';
            html += '               <div style="width:'+obj.option.tamanho_tabela+'px;" class="pesquisa-res-container lista-consulta-container ">';
            html += '                   <div class="pesquisa-res lista-consulta">';

            html += '                       <table ng-if="'+obj.model+'.tabela.visivel" class="table table-striped table-bordered table-hover selectable '+obj.getClassTabela()+'">';
            html += '                           <thead>';
            html += '                               <tr ng-focus="'+obj.model+'.focus()" ng-blur="'+obj.model+'.blur(item)" >';

            angular.forEach(obj.option.campos_tabela, function(iten, key) {
            html += '                                   <th>'+iten[1]+'</th>';
            });


            html += '                               </tr>';
            html += '                           </thead>';

            var tamanho = obj.option.campos_tabela.length;
            html += '                           <tr ng-if="'+obj.model+'.dados.length == 0" ng-Keydown="'+obj.model+'.selecionarKeydown($event,null)" ng-click="'+obj.model+'.selecionarItem(null)" ng-focus="'+obj.model+'.focus()" ng-blur="'+obj.model+'.blur()" class="selectable" tabindex="0">';
            html += '                                   <td style="text-align:center;" colspan="'+tamanho+'">SEM REGISTROS</td>';
            html += '                           </tr>';


            html += '                           <tr ng-Keydown="'+obj.model+'.selecionarKeydown($event,item)" ng-click="'+obj.model+'.selecionarItem(item)" ng-focus="'+obj.model+'.focus()" ng-blur="'+obj.model+'.blur()" class="selectable" tabindex="0" ng-repeat="item in '+obj.model+'.dados track by $index">';
            
            angular.forEach(obj.option.campos_tabela, function(iten, key) {
            html += '                                   <td>{{item.'+iten[0]+'}}</td>';
            });

            html += '                           </tr>';
            html += '                       </table>';
            html += '                   </div>';
            html += '               </div>';
            html += '           </div>';
            html += '        </div>';
            html += '    </div>';
            html += '</div>';  

            var obj   = $(obj.componente);
            var scope = obj.scope(); 
            obj.html(html);
            $compile(obj.contents())(scope);

        },
        consultar: function(obj){

            var btn_filtro = $(document).find('.'+this.getClassButton());

            function beforeSend() {
                if (btn_filtro !== false){
                $(btn_filtro)
                    .children()
                    .addClass('fa-circle-o-notch');
                }
            }

            function complete(){
                if (btn_filtro !== false) {

                    $(btn_filtro)
                        .children()
                        .removeClass('fa-circle-o-notch');
                }
                requestRunning = 0;
            }

            beforeSend();

            $ajax.post(obj.option.obj_consulta, {OPTIONS : obj.option.filtro_sql, FILTRO : obj.Input.value + ' ' },{progress:false,complete:complete})
                .then(function(response) {

                    obj.dados = response;
                    console.log(obj.dados.length);

                    if(obj.dados.length == 1){
                        obj.selecionarItem(obj.dados[0]);   
                    }else{
                        obj.setFocusTabela();
                    }              
                },
                function(e){
                    //showErro(e);
                }
            );

        },
        setData: function(data) {
            angular.extend(this, data);
        },
        InputKeydown: function($event){
            if($event.key == 'Delete' && this.Input.readonly == true){
                this.apagar();   
            }

            if($event.key == 'Enter' && this.Input.readonly == false){
                this.filtrar();   
            }
        },
        selecionarKeydown: function($event,item){
            if($event.key == 'Enter'){
                this.selecionarItem(item);
            }   

            if($event.key == 'Escape'){
                this.tabela.visivel = false;
                this.setFocusInput();
            }
        },
        vincular:function(){

            var that = this;

            if(this.require != null){
                if(Array.isArray(this.require)){

                    angular.forEach(this.require, function(item, key) {
                        that.option.filtro_sql.push(item.item);
                    });

                    this.require.reverse();

                    angular.forEach(this.require, function(item, key) {

                        item.actionsSelct.push(function(){
                            if(that.validar()){
                                that.filtrar();
                            }   
                        });
                        item.actionsClear.push(function(){
                            that.apagar(); 
                        });
                    });

                }else{

                    this.option.filtro_sql = this.require.item;

                    this.require.actionsSelct.push(function(){
                        that.filtrar();    
                    });
                    this.require.actionsClear.push(function(){
                        that.apagar();    
                    });
                }
            }
        },
        selecionarItem:function(item){
            if(this.dados.length > 0){
                this.tabela.visivel = false;

                this.btn_apagar_filtro.disabled = false;
                this.btn_apagar_filtro.visivel  = true;

                this.btn_filtro.disabled = true;
                this.btn_filtro.visivel  = false;

                this.Input.readonly = true;

                var valor = '';

                angular.forEach(this.option.obj_ret, function(campo, key) {
                    if(valor == ''){
                        valor  = item[campo];
                    }else{
                        valor += ' - ' + item[campo];    
                    }
                });

                this.selected = item;

                this.item.selected = true;
                this.item.dados = item;

                this.Input.value = valor;
                this.setFocusInput();
                this.setDefalt();

                if(this.onSelect != null){
                    this.onSelect();
                }

                if(this.actionsSelct != null){
                    angular.forEach(this.actionsSelct, function(item, key) {
                        if(item != null){
                            item();
                        }
                    });
                }
            }else{
                this.item.selected = false;
                this.item.dados = {};

                this.tabela.visivel = false;
                this.selected = null;
                this.setFocusInput();
            }
        },
        setFocusTabela: function() {
            this.tabela.visivel = true;

            var that = this;

            setTimeout(function(){
                var tabela = $(document).find('.'+that.getClassTabela());
                var tr     = $(tabela).find('tr');
                if(tr.length > 1){
                    $(tr[1]).focus(); 
                }

            },100);
        },
        setFocusInput: function() {
            $(document).find('.'+this.getClassInput()).focus();
        },
        blur:function(){
            var that = this;

            that.timeFechar = setTimeout(function(){
                $rootScope.$apply(function () {
                    that.tabela.visivel = false;
                });
            },100);
        },
        focus:function(){
            clearTimeout(this.timeFechar);
        },     
        compile : function () {
            this.MontarHtml(this);
        },
        validate:function(){

            var ret = true;

            if(this.require != null){
                if(Array.isArray(this.require)){

                    this.require.reverse();

                    angular.forEach(this.require, function(item, key) {
                        if(item.selected == null){
                            item.setErro();
                            item.setFocusInput();
                            ret = false;
                        }
                    });

                }else{
                    if(this.require.selected == null){
                        this.require.setErro();
                        this.require.setFocusInput();
                        ret = false;
                    }
                }
            }

            return ret;

        },
        validar:function(){

            var ret = true;

            if(this.require != null){
                if(Array.isArray(this.require)){

                    this.require.reverse();

                    angular.forEach(this.require, function(item, key) {
                        if(item.selected == null){
                            ret = false;
                        }
                    });

                }else{
                    if(this.require.selected == null){
                        ret = false;
                    }
                }
            }

            return ret;

        },
        apagar : function () {

            this.item.selected = false;
            this.item.dados = {};

            this.tabela.visivel = false;

            this.btn_apagar_filtro.disabled = true;
            this.btn_apagar_filtro.visivel  = false;

            this.btn_filtro.disabled = false;
            this.btn_filtro.visivel  = true;

            this.Input.readonly = false;
            this.Input.value = '';

            this.selected = null;

            if(this.onClear != null){
                this.onClear();
            }

            if(this.actionsClear != null){
                angular.forEach(this.actionsClear, function(item, key) {
                    if(item != null){
                        item();
                    }
                });
            }
        },
        filtrar : function () {

            var validar = true;

            if(this.validarInput != null){
                validar = this.validarInput();
            }

            if(this.validate()){
                if(validar){
                    this.consultar(this);
                }
            }
        },
        setErro:function(msg){
            $(document).find('.'+this.getClassForm()).addClass('has-error');
        },
        setAlert:function(msg){
            $(document).find('.'+this.getClassForm()).addClass('has-error');
        },
        setDefalt:function(){
            $(document).find('.'+this.getClassForm()).removeClass('has-error');
        },
        getClassTabela:function(){
            return this.option.class+'_tabela';
        },
        getClassForm:function(){
            return this.option.class+'_forme';
        },
        getClassInput:function(){
            return this.option.class+'_Input';
        },
        getClassButton:function(){
            return this.option.class+'_button';
        },
        actionsSelct  : [],
        actionsClear  : [],
        onSelect      : null,
        onClear       : null,
        require       : null,
        validarInput  : null,
        timeFechar    : null,
        selected      : null,
        item          : {selected: false, dados: {}},   
        model         : '',
        componente    : '',
        dados         : [],
        tabela:{
            disabled  : true,
            visivel   : false,    
        },
        btn_apagar_filtro: { 
            disabled  : true,
            visivel   : false,
        },
        btn_filtro: { 
            disabled  : false,
            visivel   : true,
        },
        Input: {
            disabled  : false,
            readonly  : false,
            focus     : false,
            value     : ''
        },
        option : {
            label_descricao   : 'DEFAULT:',
            obj_consulta      : 'Ppcp/include/_22030-gp',
            obj_ret           : ['ID','DESCRICAO'],
            campos_sql        : ['ID','DESCRICAO'],
            campos_Inputs     : [['_id','ID'],['_descricao','DESCRICAO']],
            filtro_sql        : [['STATUS','1'],['ORDER','DESCRICAO,ID']],
            campos_tabela     : [['ID','ID'],['DESCRICAO','DESCRIÇÃO']],
            tamanho_Input     : 'input-medio',
            tamanho_tabela    : 300,
            required          : true,
            class             : 'consulta_gp_grup',
            required          : true,
            autofocus         : false,
            selecionado       : false,
        }
    };

    /**
     * Public method, assigned to prototype
     */
    Consulta.prototype = {
        Consulta: function(data) {
            if (data) {
                this.setData(data);
            }
        },
        getNew: function() {
            return angular.copy(obj_Consulta);
        }
    }

    /**
     * Return the constructor function
     */
    return Consulta;
};