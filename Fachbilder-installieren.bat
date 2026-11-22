@echo off
setlocal EnableExtensions
cd /d "%~dp0"
set "PHP_EXE=C:\xampp\php\php.exe"
if not exist "%PHP_EXE%" set "PHP_EXE=php"
echo.
echo  easyIT Nachhilfe - Fachbilder installieren V2
 echo  ==============================================
echo.
"%PHP_EXE%" "%~dp0install-fachbilder.php" --check
if errorlevel 1 (
  echo.
  echo FEHLER: Das Installationsprogramm hat keine passenden Fachseiten gefunden.
  echo Bitte entpacke das ZIP direkt in C:\xampp\htdocs\nachhilfe\
  pause
  exit /b 1
)
echo.
choice /C JN /N /M "Jetzt alle erkannten Fachseiten aktualisieren (J/N)? "
if errorlevel 2 exit /b 0
"%PHP_EXE%" "%~dp0install-fachbilder.php" --apply
if errorlevel 1 (
  echo Installation nicht vollstaendig. Bitte die Fehlermeldungen lesen.
  pause
  exit /b 1
)
echo.
echo Erledigt. Test im Browser:
echo http://localhost/nachhilfe/assets/fachbilder/mathematik-01.svg
echo http://localhost/nachhilfe/Fachbilder-pruefen.php
 echo Danach eine Fachseite mit Strg+F5 neu laden.
pause
