@echo off
set PATH=C:\laragon\bin\nodejs\node-v18;%PATH%
cd /d c:\laragon\sistema-registro\sistema-barberia
call npm run build 1>_build2.txt 2>&1
echo BUILD_EXIT=%errorlevel% 1>>_build2.txt 2>&1
