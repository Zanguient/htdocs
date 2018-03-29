
		.sass('estoque/15060.scss', cssPathPublic)
		.scripts('estoque/_15060/app.js' , jsPathPublic)
		.scripts([ 
			'estoque/_15060/app.js'
		], jsPathPublic+'/_15060.js')
		.addarray([cssPathPublic+'/15060.css',jsPathPublic+'/_15060.js'])
		.copy('resources/assets/js/helper/plugin/angular/angular-datatables.js', jsPathPublic)