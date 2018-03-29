
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
		.addarray([cssPathPublic+'/master.css',jsPathPublic+'/master.js'])