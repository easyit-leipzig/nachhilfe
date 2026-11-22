easyIT Nachhilfe – Dunkelblaues Design auf ALLEN Seiten
=======================================================
Version: 1.0.0 / 22.11.2026

PROBLEM
Auf einigen Unterseiten erscheinen noch weiße oder beinahe weiße Karten,
Formularfelder und Buttons. Dadurch sind Texte auf dem dunkelblauen
Seitenlayout teilweise sehr schlecht lesbar (z. B. notenrechner.php).

LÖSUNG
Dieses Paket ergänzt die Webseite um eine globale CSS- und JS-Korrektur.
Die Einbindung erfolgt möglichst an den gemeinsamen PHP-Headern; dadurch
wirken die Regeln automatisch auf alle Seiten, die den Header verwenden.
Auch eigenständige PHP/HTML-Seiten mit einem echten </head> werden erfasst.

Die Anpassungen umfassen:
  * helle Infokarten => dunkelblaue Karten mit lesbarer Schrift
  * Eingabefelder, Auswahlfelder, Textbereiche => dunkelblau / weiße Schrift
  * Formular-Labels und Hinweise => kontrastreich
  * helle Buttons => passende dunkelblaue Buttons
  * helle Entfernen-Schaltflächen (x) => dunkelrot
  * dynamisch hinzugefügte Inhalte => ebenfalls korrigiert
  * bestehende Logos, Bilder, Charts, Navigation und türkisfarbene Buttons
    werden nicht automatisch umgestaltet

ABLAGE
  assets/css/easyit-dark-unify.css
  assets/js/easyit-dark-unify.js
  includes/easyit-theme-head.php
  easyit-design-installieren.php
  Design-installieren.bat

INSTALLATION (XAMPP, Windows)
1. Die ZIP-Datei vollständig nach
     C:\xampp\htdocs\nachhilfe\
   entpacken und vorhandene Dateien bei Nachfrage überschreiben.
2. 'Design-installieren.bat' per Doppelklick ausführen.
3. Die Liste der gefundenen Seiten ansehen, dann mit J bestätigen.
4. Im Browser die Seite neu aufrufen und Strg+F5 drücken:
     http://localhost/nachhilfe/notenrechner.php
5. Auch andere Fach-, Lernhilfe-, Kontakt- und Formularseiten prüfen.

ALTERNATIV IN DER WINDOWS-KONSOLE
  C:\xampp\php\php.exe easyit-design-installieren.php --check
  C:\xampp\php\php.exe easyit-design-installieren.php --apply
  C:\xampp\php\php.exe easyit-design-installieren.php --status
  C:\xampp\php\php.exe easyit-design-installieren.php --restore

BACKUPS / RÜCKGÄNGIG MACHEN
Die Originale werden vor dem ersten Umschreiben gesichert unter
  private/_easyit_design_backups/
Die Sicherungen dürfen nicht öffentlich aufrufbar sein; eine .htaccess
wird angelegt. --restore macht die Einbindung rückgängig, sofern die
betreffende Seite seit der Installation nicht erneut manuell verändert
wurde. Andere Änderungen werden zum Schutz nicht überschrieben.

TECHNIK / PFADVERHALTEN
Der Theme-Helper ermittelt den URL-Pfad aus DOCUMENT_ROOT und dem
Website-Stammordner. Damit funktioniert die Einbindung sowohl unter
localhost/nachhilfe/ als auch unter einer eigenen Domain im Webroot.
Der Installer ersetzt keine bestehenden Seiten, sondern fügt vor dem
HTML-Ende des HEAD eine einmalige Einbindung ein. Ein gemeinsamer Header
wird nur ein einziges Mal geändert.

WICHTIG / GRENZEN
- Die vorliegende ZIP-Datei enthält NICHT die aktuelle vollständige
  Webseite. Die Seiten selbst werden erst beim lokalen Ausführen des
  Installers modifiziert.
- PHP-Seiten, deren <head> ausschließlich per echo/Template-Engine erzeugt
  wird und die keinen klassischen HTML-Kopf oder keinen gemeinsam
  eingebundenen Kopfteil haben, werden vom Installer nicht verändert.
  Der Installer listet erkannte Dateien auf. Für Sonderseiten ist eine
  manuelle Anpassung am Template erforderlich.
- Die Korrektur bearbeitet nur den Seitenstil; es werden keine Funktionen,
  Datenbankabfragen oder Berechnungen des Notenrechners geändert.
- Die HTML-Vorschau ist nur eine DEMOSEITE des Designs, nicht die echte
  Notenrechner-Seite.

WENN WEISS WEITERHIN ERSCHEINT
1. Strg+F5 drücken.
2. Im Browser-Entwicklerwerkzeug (F12) prüfen, ob
   assets/css/easyit-dark-unify.css und assets/js/easyit-dark-unify.js
   erfolgreich mit HTTP 200 geladen werden.
3. Prüfen, ob der gemeinsame Header (z. B. includes/header.php) durch
   den Installer erfasst wurde.
4. Zur gezielten vollständigen Korrektur die AKTUELLE nachhilfe.zip
   in ChatGPT hochladen; dann können Seiten-/CSS-Klassen direkt angepasst
   werden statt nur einen globalen Zusatz-Patch zu nutzen.
