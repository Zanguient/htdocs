
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