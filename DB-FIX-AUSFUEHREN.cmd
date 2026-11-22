@echo off
setlocal
cd /d "%~dp0"
set "PHP_EXE=%~dp0..\..\php\php.exe"
if not exist "%PHP_EXE%" set "PHP_EXE=%~dp0..\..\..\php\php.exe"
if not exist "%PHP_EXE%" set "PHP_EXE=php"
"%PHP_EXE%" tools\fix-impressum-db.php
if errorlevel 1 goto end
"%PHP_EXE%" tools\pruefe-impressum-db.php
:end
echo.
pause
