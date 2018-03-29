@rem #################################################################################
@rem #################################################################################
@rem ######## Altor : Francisco Anderson de Sousa Oliveira                      ######
@rem ######## Data ciação : 25/08/2016                                          ######
@rem ######## Ultima alteração : 25/08/2016                                     ######
@rem #################################################################################
@rem #################################################################################

echo off

IF not EXIST "tmp" (md tmp)

del /Q tmp\*

cls
set /p TelaID=Digite o id do objeto:
set /p Grupos=Digite o modulo do objeto com a 1a letra maiuscula:
set /p SGrupos=Digite o modulo do objeto em minusculo:

echo Limpando estrutura de %TelaID%/%Grupos% - _%TelaID%/%SGrupos%

cd..
@rem ######################################-Controller-################################
echo Controller...
IF EXIST "app\Http\Controllers\%Grupos%" (
	del .\app\Http\Controllers\%Grupos%\_%TelaID%Controller.php   /s /q
)
@rem #################################################################################

@rem #######################################-Routes-##################################
echo Routes...
IF EXIST "app\Http\Routes\%Grupos%" (
	del .\app\Http\Routes\%Grupos%\_%TelaID%.php  /s /q
)
@rem #################################################################################

@rem ########################################-DAO-####################################
echo DAO...
IF EXIST "app\Models\DAO\%Grupos%" (
	del .\app\Models\DAO\%Grupos%\_%TelaID%DAO.php  /s /q
)
@rem #################################################################################

@rem ########################################-DTO-####################################
echo DTO...
IF EXIST "app\Models\DTO\%Grupos%" (
	del .\app\Models\DTO\%Grupos%\_%TelaID%.php  /s /q
)
@rem #################################################################################

@rem ########################################-JS-#####################################
echo JS...
IF EXIST "resources\assets\js\%SGrupos%\_%TelaID%" (
	rd .\resources\assets\js\%SGrupos%\_%TelaID%  /s /q
)
@rem #################################################################################

@rem ########################################-sass-###################################
echo sass...
IF EXIST "resources\assets\sass\%SGrupos%" (
	del .\resources\assets\sass\%SGrupos%\%TelaID%.scss  /s /q
)
@rem #################################################################################

@rem ########################################-lang-###################################
echo lang...
IF EXIST "resources\lang\pt-BR\%SGrupos%" (
	del .\resources\lang\pt-BR\%SGrupos%\_%TelaID%.php  /s /q
)
@rem #################################################################################

@rem ########################################-views-##################################
echo views...
IF EXIST "resources\views\%SGrupos%\_%TelaID%" (
	rd .\resources\views\%SGrupos%\_%TelaID%  /s /q
)
@rem #################################################################################

@rem ########################################-gulp-#####################################
echo gulp...
IF EXIST "gulp\%Grupos%" (
	del gulp\%Grupos%\%TelaID%.js  /s /q
)
@rem #################################################################################

cd criar_tela
fart.exe -i ..\app\Providers\RouteServiceProvider.php "require app_path('Http/Routes/%Grupos%/_%TelaID%.php');"  " "


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



echo Fim...


pause