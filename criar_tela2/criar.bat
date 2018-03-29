@rem #################################################################################
@rem #################################################################################
@rem ######## Altor : Francisco Anderson de Sousa Oliveira                      ######
@rem ######## Data ciação : 25/08/2016                                          ######
@rem ######## Ultima alteração : 07/02/2018                                     ######
@rem #################################################################################
@rem #################################################################################

@rem *********** Change log **************
@rem * 23.05.2017 - Adicionado append na view; Mudanca do caminho js para js/id_tela/app.js; Mudanca do gulp
@rem * 14.12.2017 - Adicionado modelo para factory em js
@rem * 07.02.2018 - Adicionado modelo para tela de cadastro
@rem ************************************


echo off

IF not EXIST "tmp" (md tmp)

del /Q tmp\*

cls
set /p TelaID=Digite o id do objeto:
set /p Grupos=Digite o modulo do objeto com a 1a letra maiuscula:
set /p SGrupos=Digite o modulo do objeto em minusculo:
set /p STitulo=Digite o titulo do objeto:
set /p TABELA_PAI=Digite onome da tabela pai:
set /p TABELA_FILHA=Digite onome da tabela filha:

echo Criando estrutura de %TelaID%/%Grupos%

@rem ######################################-Controller-################################
cd..
IF not EXIST "app\Http\Controllers\%Grupos%" (
	md app\Http\Controllers\%Grupos%
)
cd criar_tela2

copy controller.php tmp\_%TelaID%Controller.php
fart.exe -i tmp\_%TelaID%Controller.php "#TelaID#"  "%TelaID%"
fart.exe -i tmp\_%TelaID%Controller.php "#TelaNO#"  "_%TelaID%"
fart.exe -i tmp\_%TelaID%Controller.php "#Grupos#"  "%Grupos%"
fart.exe -i tmp\_%TelaID%Controller.php "#SGrupos#" "%SGrupos%"
fart.exe -i tmp\_%TelaID%Controller.php "#Titulo#"  "%STitulo%"
move tmp\_%TelaID%Controller.php ..\app\Http\Controllers\%Grupos%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\app\Http\Controllers\%Grupos%\_%TelaID%Controller.php
@rem #################################################################################

@rem #######################################-Routes-##################################
cd..
IF not EXIST "app\Http\Routes\%Grupos%" (
	md app\Http\Routes\%Grupos%
)
cd criar_tela2

copy routes.php tmp\_%TelaID%.php
fart.exe -i tmp\_%TelaID%.php "#TelaID#"  "%TelaID%"
fart.exe -i tmp\_%TelaID%.php "#TelaNO#"  "_%TelaID%"
fart.exe -i tmp\_%TelaID%.php "#Grupos#"  "%Grupos%"
fart.exe -i tmp\_%TelaID%.php "#SGrupos#" "%SGrupos%"
fart.exe -i tmp\_%TelaID%.php "#Titulo#"  "%STitulo%"
fart.exe -i tmp\_%TelaID%.php "#TABELA_PAI#"   "%TABELA_PAI%"
fart.exe -i tmp\_%TelaID%.php "#TABELA_FILHA#" "%TABELA_FILHA%"
move tmp\_%TelaID%.php ..\app\Http\Routes\%Grupos%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\app\Http\Routes\%Grupos%\_%TelaID%.php 
@rem #################################################################################

@rem ########################################-DAO-####################################
cd..
IF not EXIST "app\Models\DAO\%Grupos%" (
	md app\Models\DAO\%Grupos%
)

IF not EXIST "app\Models\DAO\%Grupos%\include" (
	md app\Models\DAO\%Grupos%\include
)

cd criar_tela2

copy dao.php tmp\_%TelaID%DAO.php
fart.exe -i tmp\_%TelaID%DAO.php "#TelaID#"  "%TelaID%"
fart.exe -i tmp\_%TelaID%DAO.php "#TelaNO#"  "_%TelaID%"
fart.exe -i tmp\_%TelaID%DAO.php "#Grupos#"  "%Grupos%"
fart.exe -i tmp\_%TelaID%DAO.php "#SGrupos#" "%SGrupos%"
fart.exe -i tmp\_%TelaID%DAO.php "#Titulo#"  "%STitulo%"
fart.exe -i tmp\_%TelaID%DAO.php "#TABELA_PAI#"   "%TABELA_PAI%"
fart.exe -i tmp\_%TelaID%DAO.php "#TABELA_FILHA#" "%TABELA_FILHA%"

move tmp\_%TelaID%DAO.php ..\app\Models\DAO\%Grupos%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\app\Models\DAO\%Grupos%\_%TelaID%DAO.php
@rem #################################################################################

@rem ########################################-DTO-####################################
cd..
IF not EXIST "app\Models\DTO\%Grupos%" (
	md app\Models\DTO\%Grupos%
)

IF not EXIST "app\Models\DTO\%Grupos%\include" (
	md app\Models\DTO\%Grupos%\include
)

cd criar_tela2

copy dto.php tmp\_%TelaID%.php
fart.exe -i tmp\_%TelaID%.php "#TelaID#"  "%TelaID%"
fart.exe -i tmp\_%TelaID%.php "#TelaNO#"  "_%TelaID%"
fart.exe -i tmp\_%TelaID%.php "#Grupos#"  "%Grupos%"
fart.exe -i tmp\_%TelaID%.php "#SGrupos#" "%SGrupos%"
fart.exe -i tmp\_%TelaID%.php "#Titulo#"  "%STitulo%"
fart.exe -i tmp\_%TelaID%.php "#TABELA_PAI#"   "%TABELA_PAI%"
fart.exe -i tmp\_%TelaID%.php "#TABELA_FILHA#" "%TABELA_FILHA%"
move tmp\_%TelaID%.php ..\app\Models\DTO\%Grupos%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\app\Models\DTO\%Grupos%\_%TelaID%.php
@rem #################################################################################

@rem ########################################-JS-#####################################
cd..
IF not EXIST "resources\assets\js\%SGrupos%" (
	md resources\assets\js\%SGrupos%
)

IF not EXIST "resources\assets\js\%SGrupos%\_%TelaID%" (
	md resources\assets\js\%SGrupos%\_%TelaID%
)


cd criar_tela2

copy app.js tmp\_%TelaID%.app.js
fart.exe -i tmp\_%TelaID%.app.js "#TelaID#"  "%TelaID%"
fart.exe -i tmp\_%TelaID%.app.js "#TelaNO#"  "_%TelaID%"
fart.exe -i tmp\_%TelaID%.app.js "#Grupos#"  "%Grupos%"
fart.exe -i tmp\_%TelaID%.app.js "#SGrupos#" "%SGrupos%"
fart.exe -i tmp\_%TelaID%.app.js "#Titulo#"  "%STitulo%"
fart.exe -i tmp\_%TelaID%.app.js "#TABELA_PAI#"  "%TABELA_PAI%"
fart.exe -i tmp\_%TelaID%.app.js "#TABELA_FILHA#"  "%TABELA_FILHA%"
move tmp\_%TelaID%.app.js ..\resources\assets\js\%SGrupos%\_%TelaID%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\resources\assets\js\%SGrupos%\_%TelaID%.app.js

copy controller.js tmp\_%TelaID%.controller.js
fart.exe -i tmp\_%TelaID%.controller.js "#TelaID#"  "%TelaID%"
fart.exe -i tmp\_%TelaID%.controller.js "#TelaNO#"  "_%TelaID%"
fart.exe -i tmp\_%TelaID%.controller.js "#Grupos#"  "%Grupos%"
fart.exe -i tmp\_%TelaID%.controller.js "#SGrupos#" "%SGrupos%"
fart.exe -i tmp\_%TelaID%.controller.js "#Titulo#"  "%STitulo%"
fart.exe -i tmp\_%TelaID%.controller.js "#TABELA_PAI#"   "%TABELA_PAI%"
fart.exe -i tmp\_%TelaID%.controller.js "#TABELA_FILHA#" "%TABELA_FILHA%"
move tmp\_%TelaID%.controller.js ..\resources\assets\js\%SGrupos%\_%TelaID%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\resources\assets\js\%SGrupos%\_%TelaID%.controller.js

copy factory.index.js tmp\_%TelaID%.factory.index.js
fart.exe -i tmp\_%TelaID%.factory.index.js "#TelaID#"  "%TelaID%"
fart.exe -i tmp\_%TelaID%.factory.index.js "#TelaNO#"  "_%TelaID%"
fart.exe -i tmp\_%TelaID%.factory.index.js "#Grupos#"  "%Grupos%"
fart.exe -i tmp\_%TelaID%.factory.index.js "#SGrupos#" "%SGrupos%"
fart.exe -i tmp\_%TelaID%.factory.index.js "#Titulo#"  "%STitulo%"
fart.exe -i tmp\_%TelaID%.factory.index.js "#TABELA_PAI#"   "%TABELA_PAI%"
fart.exe -i tmp\_%TelaID%.factory.index.js "#TABELA_FILHA#" "%TABELA_FILHA%"
move tmp\_%TelaID%.factory.index.js ..\resources\assets\js\%SGrupos%\_%TelaID%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\resources\assets\js\%SGrupos%\_%TelaID%.factory.index.js

copy factory.indexItens.js tmp\_%TelaID%.factory.indexItens.js
fart.exe -i tmp\_%TelaID%.factory.indexItens.js "#TelaID#"  "%TelaID%"
fart.exe -i tmp\_%TelaID%.factory.indexItens.js "#TelaNO#"  "_%TelaID%"
fart.exe -i tmp\_%TelaID%.factory.indexItens.js "#Grupos#"  "%Grupos%"
fart.exe -i tmp\_%TelaID%.factory.indexItens.js "#SGrupos#" "%SGrupos%"
fart.exe -i tmp\_%TelaID%.factory.indexItens.js "#Titulo#"  "%STitulo%"
fart.exe -i tmp\_%TelaID%.factory.indexItens.js "#TABELA_PAI#"   "%TABELA_PAI%"
fart.exe -i tmp\_%TelaID%.factory.indexItens.js "#TABELA_FILHA#" "%TABELA_FILHA%"
move tmp\_%TelaID%.factory.indexItens.js ..\resources\assets\js\%SGrupos%\_%TelaID%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\resources\assets\js\%SGrupos%\_%TelaID%.factory.indexItens.js

@rem #################################################################################

@rem ########################################-sass-#####################################
cd..
IF not EXIST "resources\assets\sass\%SGrupos%" (
	md resources\assets\sass\%SGrupos%
)

cd criar_tela2

copy sass.css tmp\%TelaID%.scss
fart.exe -i tmp\%TelaID%.scss "#TelaID#"  "%TelaID%"
fart.exe -i tmp\%TelaID%.scss "#TelaNO#"  "_%TelaID%"
fart.exe -i tmp\%TelaID%.scss "#Grupos#"  "%Grupos%"
fart.exe -i tmp\%TelaID%.scss "#SGrupos#" "%SGrupos%"
fart.exe -i tmp\%TelaID%.scss "#Titulo#"  "%STitulo%"
fart.exe -i tmp\%TelaID%.scss "#TABELA_PAI#"   "%TABELA_PAI%"
fart.exe -i tmp\%TelaID%.scss "#TABELA_FILHA#" "%TABELA_FILHA%"
move tmp\%TelaID%.scss ..\resources\assets\sass\%SGrupos%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\resources\assets\sass\%SGrupos%\%TelaID%.scss
@rem #################################################################################

@rem ########################################-lang-#####################################
cd..
IF not EXIST "resources\lang\pt-BR\%SGrupos%" (
	md "resources\lang\pt-BR\%SGrupos%"
)

cd criar_tela2

copy lang.php tmp\_%TelaID%.php
fart.exe -i tmp\_%TelaID%.php "#TelaID#"  "%TelaID%"
fart.exe -i tmp\_%TelaID%.php "#TelaNO#"  "_%TelaID%"
fart.exe -i tmp\_%TelaID%.php "#Grupos#"  "%Grupos%"
fart.exe -i tmp\_%TelaID%.php "#SGrupos#" "%SGrupos%"
fart.exe -i tmp\_%TelaID%.php "#Titulo#"  "%STitulo%"
fart.exe -i tmp\_%TelaID%.php "#TABELA_PAI#"   "%TABELA_PAI%"
fart.exe -i tmp\_%TelaID%.php "#TABELA_FILHA#" "%TABELA_FILHA%"
move tmp\_%TelaID%.php "..\resources\lang\pt-BR\%SGrupos%"
"C:\Program Files\TortoiseSVN\bin\svn.exe" add "..\resources\lang\pt-BR\%SGrupos%\_%TelaID%.php"
@rem #################################################################################

@rem ########################################-views-#####################################
cd..
IF not EXIST "resources\views\%SGrupos%" (
	md resources\views\%SGrupos%
)

IF not EXIST "resources\views\%SGrupos%\_%TelaID%" (
	md resources\views\%SGrupos%\_%TelaID%
)

cd criar_tela2

copy views.php tmp\index.blade.php
fart.exe -i tmp\index.blade.php "#TelaID#"  "%TelaID%"
fart.exe -i tmp\index.blade.php "#TelaNO#"  "_%TelaID%"
fart.exe -i tmp\index.blade.php "#Grupos#"  "%Grupos%"
fart.exe -i tmp\index.blade.php "#SGrupos#"  "%SGrupos%"
fart.exe -i tmp\index.blade.php "#Titulo#"  "%STitulo%"
fart.exe -i tmp\index.blade.php "#TABELA_PAI#"  "%TABELA_PAI%"
fart.exe -i tmp\index.blade.php "#TABELA_FILHA#"  "%TABELA_FILHA%"
move tmp\index.blade.php ..\resources\views\%SGrupos%\_%TelaID%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add "..\resources\views\%SGrupos%\_%TelaID%"

copy modal1.php tmp\modal_incluir.blade.php
fart.exe -i tmp\modal_incluir.blade.php "#TelaID#"  "%TelaID%"
fart.exe -i tmp\modal_incluir.blade.php "#TelaNO#"  "_%TelaID%"
fart.exe -i tmp\modal_incluir.blade.php "#Grupos#"  "%Grupos%"
fart.exe -i tmp\modal_incluir.blade.php "#SGrupos#" "%SGrupos%"
fart.exe -i tmp\modal_incluir.blade.php "#Titulo#"  "%STitulo%"
fart.exe -i tmp\modal_incluir.blade.php "#TABELA_PAI#"   "%TABELA_PAI%"
fart.exe -i tmp\modal_incluir.blade.php "#TABELA_FILHA#" "%TABELA_FILHA%"
move tmp\modal_incluir.blade.php ..\resources\views\%SGrupos%\_%TelaID%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add "..\resources\views\%SGrupos%\_%TelaID%"

copy modal2.php tmp\modal_incluir_itens.blade.php
fart.exe -i tmp\modal_incluir_itens.blade.php "#TelaID#"  "%TelaID%"
fart.exe -i tmp\modal_incluir_itens.blade.php "#TelaNO#"  "_%TelaID%"
fart.exe -i tmp\modal_incluir_itens.blade.php "#Grupos#"  "%Grupos%"
fart.exe -i tmp\modal_incluir_itens.blade.php "#SGrupos#" "%SGrupos%"
fart.exe -i tmp\modal_incluir_itens.blade.php "#Titulo#"  "%STitulo%"
fart.exe -i tmp\modal_incluir_itens.blade.php "#TABELA_PAI#"   "%TABELA_PAI%"
fart.exe -i tmp\modal_incluir_itens.blade.php "#TABELA_FILHA#" "%TABELA_FILHA%"
move tmp\modal_incluir_itens.blade.php ..\resources\views\%SGrupos%\_%TelaID%
"C:\Program Files\TortoiseSVN\bin\svn.exe" add "..\resources\views\%SGrupos%\_%TelaID%"
@rem #################################################################################

@rem ########################################-gulp-#####################################
cd..
IF not EXIST "gulp\%Grupos%" (
	md gulp\%Grupos%
)

cd criar_tela2

copy gulp.js tmp\%TelaID%.js
fart.exe -i tmp\%TelaID%.js "#TelaID#"  "%TelaID%"
fart.exe -i tmp\%TelaID%.js "#TelaNO#"  "_%TelaID%"
fart.exe -i tmp\%TelaID%.js "#Grupos#"  "%Grupos%"
fart.exe -i tmp\%TelaID%.js "#SGrupos#" "%SGrupos%"
fart.exe -i tmp\%TelaID%.js "#Titulo#"  "%STitulo%"
fart.exe -i tmp\%TelaID%.js "#TABELA_PAI#"   "%TABELA_PAI%"
fart.exe -i tmp\%TelaID%.js "#TABELA_FILHA#" "%TABELA_FILHA%"
move tmp\%TelaID%.js ..\gulp\%Grupos%\
"C:\Program Files\TortoiseSVN\bin\svn.exe" add ..\gulp\%Grupos%\%TelaID%.js
@rem #################################################################################

fart.exe -i ..\app\Providers\RouteServiceProvider.php "require app_path('Http/Routes/%Grupos%/_%TelaID%.php');"  " "
fart.exe -i ..\app\Providers\RouteServiceProvider.php "#NOVALINHA#"  "require app_path('Http/Routes/%Grupos%/_%TelaID%.php');#NOVALINHA#"

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