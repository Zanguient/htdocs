@rem #################################################################################
@rem #################################################################################
@rem ######## Altor : Francisco Anderson de Sousa Oliveira                      ######
@rem ######## Data ciação : 25/08/2016                                          ######
@rem ######## Ultima alteração : 23/05/2017                                     ######
@rem #################################################################################
@rem #################################################################################

@rem *********** Change log **************
@rem * 23.05.2017 - Adicionado append na view; Mudanca do caminho js para js/id_tela/app.js; Mudanca do gulp
@rem * 14.12.2017 - Adicionado modelo para factory em js
@rem ************************************


echo off

IF not EXIST "tmp" (md tmp)

del /Q tmp\*

cls
set /p TelaID=Digite o id do objeto:
set /p TelaNO=Digite o id do objeto com _:
set /p Grupos=Digite o modulo do objeto com a 1a letra maiuscula:
set /p SGrupos=Digite o modulo do objeto em minusculo:
set /p STitulo=Digite o titulo do objeto:

echo Criando estrutura de %TelaID%/%Grupos%

@rem ######################################-Controller-################################
cd..
IF not EXIST "app\Http\Controllers\%Grupos%" (
	md app\Http\Controllers\%Grupos%
)
cd criar_tela

copy controller.php tmp\%TelaNO%Controller.php
fart.exe -i tmp\%TelaNO%Controller.php "#TelaID#"  "%TelaID%"
fart.exe -i tmp\%TelaNO%Controller.php "#TelaNO#"  "%TelaNO%"
fart.exe -i tmp\%TelaNO%Controller.php "#Grupos#"  "%Grupos%"
fart.exe -i tmp\%TelaNO%Controller.php "#SGrupos#"  "%SGrupos%"
fart.exe -i tmp\%TelaNO%Controller.php "#Titulo#"  "%STitulo%"
move tmp\%TelaNO%Controller.php ..\app\Http\Controllers\%Grupos%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\app\Http\Controllers\%Grupos%\%TelaNO%Controller.php
@rem #################################################################################

@rem #######################################-Routes-##################################
cd..
IF not EXIST "app\Http\Routes\%Grupos%" (
	md app\Http\Routes\%Grupos%
)
cd criar_tela

copy routes.php tmp\%TelaNO%.php
fart.exe -i tmp\%TelaNO%.php "#TelaID#"  "%TelaID%"
fart.exe -i tmp\%TelaNO%.php "#TelaNO#"  "%TelaNO%"
fart.exe -i tmp\%TelaNO%.php "#Grupos#"  "%Grupos%"
fart.exe -i tmp\%TelaNO%.php "#SGrupos#"  "%SGrupos%"
fart.exe -i tmp\%TelaNO%.php "#Titulo#"  "%STitulo%"
move tmp\%TelaNO%.php ..\app\Http\Routes\%Grupos%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\app\Http\Routes\%Grupos%\%TelaNO%.php 
@rem #################################################################################

@rem ########################################-DAO-####################################
cd..
IF not EXIST "app\Models\DAO\%Grupos%" (
	md app\Models\DAO\%Grupos%
)

IF not EXIST "app\Models\DAO\%Grupos%\include" (
	md app\Models\DAO\%Grupos%\include
)

cd criar_tela

copy dao.php tmp\%TelaNO%DAO.php
fart.exe -i tmp\%TelaNO%DAO.php "#TelaID#"  "%TelaID%"
fart.exe -i tmp\%TelaNO%DAO.php "#TelaNO#"  "%TelaNO%"
fart.exe -i tmp\%TelaNO%DAO.php "#Grupos#"  "%Grupos%"
fart.exe -i tmp\%TelaNO%DAO.php "#SGrupos#"  "%SGrupos%"
fart.exe -i tmp\%TelaNO%DAO.php "#Titulo#"  "%STitulo%"
move tmp\%TelaNO%DAO.php ..\app\Models\DAO\%Grupos%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\app\Models\DAO\%Grupos%\%TelaNO%DAO.php
@rem #################################################################################

@rem ########################################-DTO-####################################
cd..
IF not EXIST "app\Models\DTO\%Grupos%" (
	md app\Models\DTO\%Grupos%
)

IF not EXIST "app\Models\DTO\%Grupos%\include" (
	md app\Models\DTO\%Grupos%\include
)

cd criar_tela

copy dto.php tmp\%TelaNO%.php
fart.exe -i tmp\%TelaNO%.php "#TelaID#"  "%TelaID%"
fart.exe -i tmp\%TelaNO%.php "#TelaNO#"  "%TelaNO%"
fart.exe -i tmp\%TelaNO%.php "#Grupos#"  "%Grupos%"
fart.exe -i tmp\%TelaNO%.php "#SGrupos#"  "%SGrupos%"
fart.exe -i tmp\%TelaNO%.php "#Titulo#"  "%STitulo%"
move tmp\%TelaNO%.php ..\app\Models\DTO\%Grupos%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\app\Models\DTO\%Grupos%\%TelaNO%.php
@rem #################################################################################

@rem ########################################-JS-#####################################
cd..
IF not EXIST "resources\assets\js\%SGrupos%" (
	md resources\assets\js\%SGrupos%
)

IF not EXIST "resources\assets\js\%SGrupos%\%TelaNO%" (
	md resources\assets\js\%SGrupos%\%TelaNO%
)


cd criar_tela

copy app.js tmp\%TelaNO%.app.js
fart.exe -i tmp\%TelaNO%.app.js "#TelaID#"  "%TelaID%"
fart.exe -i tmp\%TelaNO%.app.js "#TelaNO#"  "%TelaNO%"
fart.exe -i tmp\%TelaNO%.app.js "#Grupos#"  "%Grupos%"
fart.exe -i tmp\%TelaNO%.app.js "#SGrupos#"  "%SGrupos%"
fart.exe -i tmp\%TelaNO%.app.js "#Titulo#"  "%STitulo%"
move tmp\%TelaNO%.app.js ..\resources\assets\js\%SGrupos%\%TelaNO%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\resources\assets\js\%SGrupos%\%TelaNO%.app.js

copy controller.js tmp\%TelaNO%.controller.js
fart.exe -i tmp\%TelaNO%.controller.js "#TelaID#"  "%TelaID%"
fart.exe -i tmp\%TelaNO%.controller.js "#TelaNO#"  "%TelaNO%"
fart.exe -i tmp\%TelaNO%.controller.js "#Grupos#"  "%Grupos%"
fart.exe -i tmp\%TelaNO%.controller.js "#SGrupos#"  "%SGrupos%"
fart.exe -i tmp\%TelaNO%.controller.js "#Titulo#"  "%STitulo%"
move tmp\%TelaNO%.controller.js ..\resources\assets\js\%SGrupos%\%TelaNO%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\resources\assets\js\%SGrupos%\%TelaNO%.controller.js

copy factory.index.js tmp\%TelaNO%.factory.index.js
fart.exe -i tmp\%TelaNO%.factory.index.js "#TelaID#"  "%TelaID%"
fart.exe -i tmp\%TelaNO%.factory.index.js "#TelaNO#"  "%TelaNO%"
fart.exe -i tmp\%TelaNO%.factory.index.js "#Grupos#"  "%Grupos%"
fart.exe -i tmp\%TelaNO%.factory.index.js "#SGrupos#"  "%SGrupos%"
fart.exe -i tmp\%TelaNO%.factory.index.js "#Titulo#"  "%STitulo%"
move tmp\%TelaNO%.factory.index.js ..\resources\assets\js\%SGrupos%\%TelaNO%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\resources\assets\js\%SGrupos%\%TelaNO%.factory.index.js

@rem #################################################################################

@rem ########################################-sass-#####################################
cd..
IF not EXIST "resources\assets\sass\%SGrupos%" (
	md resources\assets\sass\%SGrupos%
)

cd criar_tela

copy sass.css tmp\%TelaID%.scss
fart.exe -i tmp\%TelaID%.scss "#TelaID#"  "%TelaID%"
fart.exe -i tmp\%TelaID%.scss "#TelaNO#"  "%TelaNO%"
fart.exe -i tmp\%TelaID%.scss "#Grupos#"  "%Grupos%"
fart.exe -i tmp\%TelaID%.scss "#SGrupos#"  "%SGrupos%"
fart.exe -i tmp\%TelaID%.scss "#Titulo#"  "%STitulo%"
move tmp\%TelaID%.scss ..\resources\assets\sass\%SGrupos%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\resources\assets\sass\%SGrupos%\%TelaID%.scss
@rem #################################################################################

@rem ########################################-lang-#####################################
cd..
IF not EXIST "resources\lang\pt-BR\%SGrupos%" (
	md "resources\lang\pt-BR\%SGrupos%"
)

cd criar_tela

copy lang.php tmp\%TelaNO%.php
fart.exe -i tmp\%TelaNO%.php "#TelaID#"  "%TelaID%"
fart.exe -i tmp\%TelaNO%.php "#TelaNO#"  "%TelaNO%"
fart.exe -i tmp\%TelaNO%.php "#Grupos#"  "%Grupos%"
fart.exe -i tmp\%TelaNO%.php "#SGrupos#"  "%SGrupos%"
fart.exe -i tmp\%TelaNO%.php "#Titulo#"  "%STitulo%"
move tmp\%TelaNO%.php "..\resources\lang\pt-BR\%SGrupos%"
"C:\Program Files\TortoiseSVN\bin\svn.exe" add "..\resources\lang\pt-BR\%SGrupos%\%TelaNO%.php"
@rem #################################################################################

@rem ########################################-views-#####################################
cd..
IF not EXIST "resources\views\%SGrupos%" (
	md resources\views\%SGrupos%
)

IF not EXIST "resources\views\%SGrupos%\%TelaNO%" (
	md resources\views\%SGrupos%\%TelaNO%
)

cd criar_tela

copy views.php tmp\index.blade.php
fart.exe -i tmp\index.blade.php "#TelaID#"  "%TelaID%"
fart.exe -i tmp\index.blade.php "#TelaNO#"  "%TelaNO%"
fart.exe -i tmp\index.blade.php "#Grupos#"  "%Grupos%"
fart.exe -i tmp\index.blade.php "#SGrupos#"  "%SGrupos%"
fart.exe -i tmp\index.blade.php "#Titulo#"  "%STitulo%"
move tmp\index.blade.php ..\resources\views\%SGrupos%\%TelaNO%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add "..\resources\views\%SGrupos%\%TelaNO%"
@rem #################################################################################

@rem ########################################-gulp-#####################################
cd..
IF not EXIST "gulp\%Grupos%" (
	md gulp\%Grupos%
)

cd criar_tela

copy gulp.js tmp\%TelaID%.js
fart.exe -i tmp\%TelaID%.js "#TelaID#"  "%TelaID%"
fart.exe -i tmp\%TelaID%.js "#TelaNO#"  "%TelaNO%"
fart.exe -i tmp\%TelaID%.js "#Grupos#"  "%Grupos%"
fart.exe -i tmp\%TelaID%.js "#SGrupos#"  "%SGrupos%"
fart.exe -i tmp\%TelaID%.js "#Titulo#"  "%STitulo%"
move tmp\%TelaID%.js ..\gulp\%Grupos%\
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\gulp\%Grupos%\%TelaID%.js
@rem #################################################################################

fart.exe -i ..\app\Providers\RouteServiceProvider.php "require app_path('Http/Routes/%Grupos%/%TelaNO%.php');"  " "
fart.exe -i ..\app\Providers\RouteServiceProvider.php "#NOVALINHA#"  "require app_path('Http/Routes/%Grupos%/%TelaNO%.php');#NOVALINHA#"

cd ..
cd gulp_modules

set IP=0 :

IF EXIST "gulpfile.js" (
	del /Q gulpfile.js
)

IF EXIST ".\temp " (
	del /Q .\temp 
)

xcopy ..\gulp\*.js .\temp\ /s /y

for /f %%a IN ('dir /b /s .\temp\*.js') do move %%a .\temp\

type Partes\*.a >gulpfile.js
type Partes\*.b >>gulpfile.js
type temp\*.*   >>gulpfile.js
type Partes\*.y >>gulpfile.js

cls
echo Gerando gulp...
cd..
move .\gulp_modules\gulpfile.js .\

IF EXIST ".\gulp_modules\gulpfile.js" (
	del /Q .\gulp_modules\gulpfile.js
)

IF EXIST ".\gulp_modules\temp" (
	del /Q .\gulp_modules\temp\*
)

IF "%IP%" GEQ "1" (
	gulp && gulp watch
) ELSE (
	gulp
)


pause