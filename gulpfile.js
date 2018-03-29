process.env.DISABLE_NOTIFIER = true;

var gulp = require("gulp");
//var bower = require("gulp-bower");
var elixir = require("laravel-elixir");
require('laravel-elixir-livereload');
var elixirTypscript = require('elixir-typescript');
/*
gulp.task('bower', function () {
    return bower();
});*/

var vendors = '../../assets/vendors/';

var paths = {
    'jquery': vendors + 'jquery/dist',
    'jqueryUi': vendors + 'jquery-ui',
    'moment': vendors + 'moment',
    'bootstrap': vendors + 'bootstrap/dist',
    'fontawesome': vendors + 'font-awesome',
    'eonasdanBootstrapDatetimepicker': vendors + 'eonasdan-bootstrap-datetimepicker/build',
    'tether' : vendors + 'tether/dist'
};


var vercao = [];

elixir.extend('addarray', function(item) {
	item.forEach(function(obj){
			vercao.push(obj);
	});
});


elixir(function(mix) {
	
	var cssPathPublic	= 'public/assets/css';
	var jsPathPublic	= 'public/assets/js';
	
	mix
	/*
    .copy('node_modules/@angular', 'public/@angular')
    .copy('node_modules/rxjs', 'public/rxjs')
    .copy('node_modules/systemjs', 'public/systemjs')
    .copy('node_modules/es6-promise', 'public/es6-promise')
    .copy('node_modules/es6-shim', 'public/es6-shim')
    .copy('node_modules/zone.js', 'public/zone.js')
    .copy('node_modules/satellizer', 'public/satellizer')
    .copy('node_modules/platform', 'public/platform')
    .copy('node_modules/reflect-metadata', 'public/reflect-metadata')

    .typescript(
        '//*.ts',
        'public/js',
        {
            "target": "es5",
            "module": "system",
            "moduleResolution": "node",
            "sourceMap": true,
            "emitDecoratorMetadata": true,
            "experimentalDecorators": true,
            "removeComments": false,
            "noImplicitAny": false
        }
    )
	*/








		.copy('node_modules/bootstrap-sass/assets/fonts', 'public/build/assets/fonts')
		.copy('resources/assets/fonts', 'public/build/assets/fonts')		
		.copy('resources/assets/js/helper/plugin/jquery.shCircleLoader.js', jsPathPublic)
		.copy('resources/assets/js/helper/plugin/jquery.shCircleLoader-min.js', jsPathPublic)
		.copy('resources/assets/js/helper/plugin/jquery-ui.min.js', jsPathPublic)
		.copy('resources/assets/js/helper/plugin/jquery.ui.touch-punch.min.js', jsPathPublic)
		.copy('resources/assets/js/helper/plugin/jquery-dateFormat.min.js', jsPathPublic)
		.copy('resources/assets/sass/helper/nvd3.scss', cssPathPublic)
		.sass('admin/11000.scss', cssPathPublic)
		.scripts('admin/_11000.js' , jsPathPublic)
		.addarray([cssPathPublic+'/11000.css',jsPathPublic+'/_11000.js'])
		.sass('admin/11001.scss', cssPathPublic)
		.scripts('admin/_11001.js' , jsPathPublic)
		.addarray([cssPathPublic+'/11001.css',jsPathPublic+'/_11001.js'])
		.sass('admin/11005.scss', cssPathPublic)
		.scripts([
			'admin/_11005/_11005.app.js',
			'admin/_11005/_11005.factory.parametro-detalhe.js',
			'admin/_11005/_11005.factory.parametro.js',
			'admin/_11005/_11005.controller.js'			
		], jsPathPublic+'/_11005.js')
		.addarray([cssPathPublic+'/11005.css',jsPathPublic+'/_11005.js'])
		.sass('admin/11010.scss', cssPathPublic)
		.scripts('admin/_11010.js' , jsPathPublic)
		.addarray([cssPathPublic+'/11010.css',jsPathPublic+'/_11010.js'])
		.scripts('admin/include/_11020-listar.js' , jsPathPublic)
		.addarray([jsPathPublic+'/_11020-listar.js'])
		.sass('admin/11040.scss', cssPathPublic)
		.scripts('admin/_11040.js' , jsPathPublic)
		.addarray([cssPathPublic+'/11040.css',jsPathPublic+'/_11040.js'])
		.sass('admin/11060.scss', cssPathPublic)
		.scripts('admin/_11060.js' , jsPathPublic)
		.addarray([cssPathPublic+'/11060.css',jsPathPublic+'/_11060.js'])
		.sass('admin/11070.scss', cssPathPublic)
		.scripts('admin/_11070.js' , jsPathPublic)
		.addarray([cssPathPublic+'/11070.css',jsPathPublic+'/_11070.js'])
		.sass('admin/11080.scss', cssPathPublic)
		.scripts('admin/_11080.js' , jsPathPublic)
		.addarray([cssPathPublic+'/11080.css',jsPathPublic+'/_11080.js'])
		.sass('admin/11090.scss', cssPathPublic)
		.scripts('admin/_11090.js' , jsPathPublic)
		.addarray([cssPathPublic+'/11090.css',jsPathPublic+'/_11090.js'])
		.sass('admin/11100.scss', cssPathPublic)
		.scripts('admin/_11100.js' , jsPathPublic)
		.addarray([cssPathPublic+'/11100.css',jsPathPublic+'/_11100.js'])
		.sass('admin/11110.scss', cssPathPublic)
		.scripts([
			'admin/_11110/app.js'
		], jsPathPublic+'/_11110.js')
		.addarray([cssPathPublic+'/11110.css',jsPathPublic+'/_11110.js'])	
		.sass('admin/11140.scss', cssPathPublic)
		.scripts([
			'admin/_11140/_11140.app.js',
			'admin/_11140/_11140.factory.create.js',
			'admin/_11140/_11140.factory.index.js',
			'admin/_11140/_11140.controller.js'
		], jsPathPublic+'/_11140.app.js')
		.addarray([cssPathPublic+'/11140.css',jsPathPublic+'/_11140.app.js'])	
		.sass('admin/11150.scss', cssPathPublic)
		.scripts([
			'admin/_11150/_11150.app.js',
			'admin/_11150/_11150.script.js',
			'admin/_11140/_11140.factory.create.js',
			'helper/plugin/angular/ng-file-upload/ng-file-upload.min.js',
			'admin/_11150/_11150.arquivos.js',
			'admin/_11150/_11150.controller.js'
		], jsPathPublic+'/_11150.app.js')
		.addarray([cssPathPublic+'/11150.css',jsPathPublic+'/_11150.app.js'])
		.sass('admin/11180.scss', cssPathPublic)
		.scripts([
			'admin/_11180/app.js'
		], jsPathPublic+'/_11180.js')
		.addarray([cssPathPublic+'/11180.css',jsPathPublic+'/_11180.js'])
	.sass('admin/11190.scss', cssPathPublic)
	.scripts([
		'admin/_11190/app.js'
	], jsPathPublic+'/_11190.js')
	.addarray([cssPathPublic+'/11190.css',jsPathPublic+'/_11190.js'])
	
	.scripts([
		'admin/_11190/app.notificacoes.js'
	], jsPathPublic+'/app.notificacoes.js')
	.addarray([jsPathPublic+'/app.notificacoes.js'])
		.sass('admin/11200.scss', cssPathPublic)
		.scripts([
			'admin/_11200/_11200.app.js',
			'admin/_11200/_11200.factory.gp.js',
			'admin/_11200/_11200.factory.operador.js',
			'admin/_11200/_11200.factory.talao.js',
			'admin/_11200/_11200.factory.filtro.js',
			'admin/_11200/_11200.controller.js'			
		], jsPathPublic+'/_11200.js')
		.addarray([cssPathPublic+'/11200.css',jsPathPublic+'/_11200.js'])
		.sass('admin/11210.scss', cssPathPublic)
		.scripts([
			'admin/_11210/_11210.app.js',
			'admin/_11210/_11210.controller.js',
			'admin/_11210/_11210.factory.index.js',
			'admin/_11210/_11210.factory.indexItens.js',
			'admin/_11210/_11210.factory.indexMenus.js',
			'admin/_11210/_11210.factory.indexGrupos.js'
		], jsPathPublic+'/_11210.js')
		.addarray([cssPathPublic+'/11210.css',jsPathPublic+'/_11210.js'])
		.sass('admin/11220.scss', cssPathPublic)
		.scripts([
			'admin/_11220/_11220.app.js',
			'admin/_11220/_11220.factory.filtro.js',
			'admin/_11220/_11220.controller.js'			
		], jsPathPublic+'/_11220.js')
		.addarray([cssPathPublic+'/11220.css',jsPathPublic+'/_11220.js'])
		.sass('auth/auth.scss', cssPathPublic)
		.addarray([cssPathPublic+'/auth.css'])
		.scripts([
			'helper/plugin/jquery.dataTables.min.js',
			'helper/lib/data-table.js'
		], jsPathPublic+'/data-table.js')
		.addarray([jsPathPublic+'/data-table.js'])		.sass('helper/erro.scss', cssPathPublic)
		.addarray([cssPathPublic+'/erro.css'])
		.sass(  'helper/app.scss', cssPathPublic)
		.sass(  'helper/index.scss', cssPathPublic)
		.scripts('helper/index.js', jsPathPublic)
		.addarray([jsPathPublic+'/index.js',cssPathPublic+'/index.css'])
		.scripts([
			'helper/plugin/jquery.min.js',
			'helper/plugin/jquery-ui.min.js',
			'../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js',
			'auth/login.js'
		], jsPathPublic+'/login.js')
		.scripts([
			'helper/plugin/jquery.mask.min.js',
			'helper/plugin/jquery.maskMoney.js',
			'helper/lib/mask.js'
		], jsPathPublic+'/mask.js')
		.addarray([jsPathPublic+'/mask.js'])
		.scripts([
			'helper/plugin/jquery.min.js',
			'helper/plugin/jquery-ui.min.js',
			
			
			'helper/plugin/angular/angular.min.js',
			'helper/plugin/angular/angular-resource.min.js',
			'helper/plugin/angular/angular-locale_pt-br.js',
			'helper/plugin/angular/functions.js',
			'helper/plugin/angular/angular-sanitize.js',

			'../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js',
			'helper/plugin/bootstrap-switch.min.js',

			'helper/lib/helper.js',
			'helper/plugin/jquery.hotkeys.js',
			'helper/plugin/jquery.tabbable.min.js',
			'helper/plugin/clipboard.min.js',
			'helper/plugin/moment.min.js',
			'helper/plugin/moment-pt-br.js',
			'helper/plugin/moment-duration-format.js',
			'helper/include/relogio.js',
			'helper/lib/jquery-no-conflict.js',
			'helper/lib/variable.js',
			'helper/lib/socket.js',
			'helper/lib/ajax.js',
			'helper/lib/header.js',
			'helper/lib/alert.js',
			'helper/lib/bootstrap.js',
			'helper/lib/hotkey.js',
			'helper/lib/input.js',
			'helper/lib/popup.js',
			'helper/lib/screen.js'
			
		], jsPathPublic+'/master.js')
		.sass( 'helper/master.scss', cssPathPublic)
		.addarray([cssPathPublic+'/master.css',jsPathPublic+'/master.js'])		.scripts([
			'menu/menu.js'
		], jsPathPublic+'/menu.js')
		.sass('menu/menu.scss', cssPathPublic)
		.addarray([cssPathPublic+'/menu.css',jsPathPublic+'/menu.js'])
		.scripts('helper/include/autenticar.js', jsPathPublic)
		.scripts('helper/include/consulta.js', jsPathPublic)
		.scripts('helper/include/consulta-tabs.js', jsPathPublic)
		.scripts('helper/include/historico.js', jsPathPublic)
		.scripts('helper/include/relogio.js', jsPathPublic)
		.scripts('helper/include/turno-filtro.js', jsPathPublic)
		.addarray([	
			jsPathPublic+'/autenticar.js',
			jsPathPublic+'/consulta.js',
			jsPathPublic+'/consulta-tabs.js',
			jsPathPublic+'/date.js',
			jsPathPublic+'/delete-confirm.js',
			jsPathPublic+'/file.js',
			jsPathPublic+'/form.js',
			jsPathPublic+'/form-action.js',
			jsPathPublic+'/formatter.js',
			jsPathPublic+'/historico.js',
			jsPathPublic+'/input.js',
			jsPathPublic+'/input-dinamic.js',
			jsPathPublic+'/login.js',
			jsPathPublic+'/pdf.js',
			jsPathPublic+'/relogio.js',
			jsPathPublic+'/table.js',
			jsPathPublic+'/termometro.js',
			jsPathPublic+'/turno-filtro.js',
			jsPathPublic+'/direct-print.js'			
		])
	.scripts([
		'helper/plugin/jquery.min.js',
		'helper/plugin/moment.min.js',
		'../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js',
		'helper/lib/bootstrap.js',
		'helper/lib/ajax.js',
		'helper/lib/alert.js',
		'auth/password.js'
	], jsPathPublic+'/password.js')
	.addarray([jsPathPublic+'/password.js'])
		.copy('resources/assets/sass/helper/lib/codemirror.css', cssPathPublic)
		.sass('helper/jquery-ui.min.scss', cssPathPublic)
		.sass('helper/jquery.clock.scss', cssPathPublic)
		.copy('resources/assets/js/helper/lib/codemirror.js', jsPathPublic)
		.scripts('helper/lib/BrowserPrint-1.0.2.min.js', jsPathPublic)
		.scripts('helper/lib/PrintZebra.js', jsPathPublic)
		.scripts('helper/lib/date.js', jsPathPublic)
		.scripts('helper/lib/delete-confirm.js', jsPathPublic)
		.scripts('helper/lib/file.js', jsPathPublic)
		.scripts('helper/plugin/google/charts/loader.js', jsPathPublic)
		.scripts('helper/lib/form.js', jsPathPublic)
		.scripts('helper/lib/form-action.js', jsPathPublic)
		.scripts('helper/lib/formatter.js', jsPathPublic)
		.scripts('helper/lib/input.js', jsPathPublic)
		.scripts('helper/lib/input-dinamic.js', jsPathPublic)
		.scripts('helper/lib/pdf.js', jsPathPublic)
		.scripts('helper/lib/termometro.js', jsPathPublic)
		.scripts('helper/lib/direct-print.js', jsPathPublic)
		.scripts('helper/lib/jquery.clock.js', jsPathPublic)
		.scripts('helper/plugin/ckeditor.js', jsPathPublic)		
		.addarray([
			jsPathPublic+'/jquery.clock.js',
			cssPathPublic+'/jquery.clock.css',
			jsPathPublic+'/codemirror.js',
			cssPathPublic+'/codemirror.css'
		])
	.scripts([
		'helper/plugin/jquery.min.js',
		'helper/plugin/moment.min.js',
		'../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js',
		'helper/lib/bootstrap.js',
		'helper/lib/ajax.js',
		'helper/lib/alert.js',
		'helper/lib/form-action.js'
	], jsPathPublic+'/reset.js')
	.addarray([jsPathPublic+'/reset.js'])
		.scripts([
			'helper/plugin/jquery.min.js',
			'../../../node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js',
			'auth/resetarSenha.js'
		], jsPathPublic+'/resetarSenha.js')
		.addarray([jsPathPublic+'/resetarSenha.js'])
		.scripts([
			'helper/plugin/jquery.dataTables.min.js',
			'helper/plugin/dataTables.bootstrap.min.js',
			'helper/lib/table.js'
		], jsPathPublic+'/table.js')
		.addarray([jsPathPublic+'/table.js'])
		.version(vercao);
})