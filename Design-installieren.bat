@echo off
setlocal EnableExtensions
cd /d "%~dp0"
set "PHP_EXE=C:\xampp\php\php.exe"
if not exist "%PHP_EXE%" set "PHP_EXE=php"
echo.
echo   easyIT Nachhilfe - Dunkelblau auf allen Seiten
 echo  =================================================
echo.
"%PHP_EXE%" "%~dp0easyit-design-installieren.php" --check
if errorlevel 1 (
  echo.
  echo Keine geeigneten Seiten gefunden. Ist das ZIP direkt im nachhilfe-Ordner entpackt?
  pause
  exit /b 1
)
echo.
choice /C JN /N /M "Design jetzt auf allen erkannten Seiten aktualisieren (J/N)? "
if errorlevel 2 exit /b 0
"%PHP_EXE%" "%~dp0easyit-design-installieren.php" --apply
if errorlevel 1 (
  echo Installation nicht vollstaendig. Bitte Fehlerliste pruefen.
  pause
  exit /b 1
)
echo.
echo Fertig! Im Browser Seite mit Strg+F5 neu laden.
echo Zum Zuruecksetzen: C:\xampp\php\php.exe easyit-design-installieren.php --restore
pause
