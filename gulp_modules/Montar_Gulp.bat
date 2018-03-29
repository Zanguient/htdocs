@echo off
set IP=1:

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