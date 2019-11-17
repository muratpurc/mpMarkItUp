markItUp! Plugin 0.0.1_alpha für Contenido 4.8.x

####################################################################################################
TOC (Table of contents)

- BESCHREIBUNG
- CHANGELOG
- FEATURES
- VORAUSSETZUNGEN
- INSTALLATION
- WICHTIGES ZUM INHALT
- SCHLUSSBEMERKUNG



####################################################################################################
BESCHREIBUNG

markItUp! ist ein auf jQuery basierender universeller Markup-Editor, der mit verschiedenen Markups 
(Auszeichnungssprachen) umgehen kann. Dazu gehören Markdown, Textile, Texy, BBCode, Wiki, usw.. Es ist 
sogar möglich, die Unterstützung für eine eigene Auszeichnungssprache zu implementieren.


Für das Contenido-CMS wurde markItUp! als Plugin entwickelt, um die Möglichkeiten des Editierens im 
Backend über die vorhandenen wysiwyg-Editoren hinaus zu erweitern.

Momentan bietet das Plugin Unterstützung für folgende Markups:
- BBCode
- Markdown
- Textile
- Texy
- Wiki

Da die Entwicklung in einem sehr frühen Stadium ist, kann es vorkommen dass manche Features nicht 
ganz vollständig sind.



####################################################################################################
CHANGELOG


2008-12-26: markItUp! Plugin 0.0.1_alpha for Contenido 4.8.x
    * first alpha release




####################################################################################################
FEATURES

- Erweitert Contenido um weitere CMS-Typen für Auszeichnungssprachen
- Inhalte lassen sich über den markItUp!-Editor bearbeiten
- Bietet Unterstützung für die Auszeichnungsprachen BBCode, Markdown, Textile, Texy, Wiki



####################################################################################################
VORAUSSETZUNGEN

- Alle Voraussetzungen von Contenido 4.8.x gelten auch für das Plugin



####################################################################################################
INSTALLATION

- Dateien aus dem Plugin-Package in die entsprechenden Contenido-Verzeichnisse kopieren.
  Ausnahme sind Dateien im Ordner "modules", diese sind Modulexporte und werden gesondert installiert.

- In die Adresszeile des Browsers http://localhost/contenido/plugins/markitup/install.php 
  eingeben, dann sollte das Anmeldefenster des Backends erscheinen.
  ("http://localhost/" ist eventuell gegen anderen virtual Host oder Domainnamen ersetzen)

- Im Backend anmelden
  TIP: Sollte der Plugininstaller nach der Anmeldung nicht erscheinen, kann die URL zum Installer 
  manuell aufgerufen werden. Der URL muss die aktuell gültige Contenido Session-ID angehängt werden.
  Beispiel: http://localhost/contenido/plugins/markitup/install.php?contenido={my_session_id}
  
- markItUp Plugin installieren 
  HINWEIS: Der Plugininstaller erstellt eine Kopie der Tabelle "{prefix}_plugins_{YYYYMMDD}", falls 
  die Tabelle die Voraussetzungen des Plugins nicht erfüllt. Wenn vorher Plugins installiert wurden, 
  müssen die Einträge von der Kopie der Tabelle manuell in die neue Tabelle übernommen werden.

- Importieren der gewünschten Module aus dem Ordner "modules" im Pluginpackage. Dazu im Backend in
  den Bereich "Style -> Module" wechseln und die gewünschten Module importieren, z. B. die 
  markItUp_Markdown.xml für das Markdown Markup Modul.

- Einem Template das Modul "markItUp_Markdown" in einem Container zuweisen.


Danach können Inhalte der Artikel (die auf das Template basieren) wie üblich im Editierbereich 
angegeben werden.



####################################################################################################
WICHTIGES ZUM INHALT

modules:
--------
Enthält exportierte Module im XML-Format. Diese Exportdateien können im Contenido Backend unter 
"Style -> Module" importiert werden.


contenido/plugins/markitup/*:
-----------------------------
Die Sourcen des Plugins.


contenido/includes/include.CMS_MIU*.php:
----------------------------------------
Die Scripte zum Editieren der neuen CMS-Typen.



####################################################################################################
SCHLUSSBEMERKUNG

Benutzung des Plugins auf eigene Gefahr!

Murat Purc, murat@purc.de
