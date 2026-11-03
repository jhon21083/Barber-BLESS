@echo off
set PATH=C:\laragon\bin\nodejs\node-v18;%PATH%
cd /d c:\laragon\sistema-registro\sistema-barberia
call npm install --no-audit --no-fund 1>_npmfull.txt 2>&1
echo NPM_EXIT=%errorlevel% 1>>_npmfull.txt 2>&1
