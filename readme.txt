markItUp! Plugin 0.1 für CONTENIDO 4.8.x

####################################################################################################
TOC (Table of contents)

- BESCHREIBUNG
- CHANGELOG
- BEKANNTE PROBLEME
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


Für das CONTENIDO-CMS wurde markItUp! als Plugin entwickelt, um die Möglichkeiten des Editierens im 
Backend über die vorhandenen wysiwyg-Editoren hinaus zu erweitern.

Momentan bietet das Plugin Unterstützung für folgende Markups:
- BBCode
- Markdown
- Textile
- Texy
- Wiki



####################################################################################################
BEKANNTE PROBLEME

Es werden nicht alle Tags in allen Markupsprachen unterstützt, möglich sind einfache Tags 
wie Überschriften, Fett, Kursiv, Unterstrichen, Listen, Links, Bilder, usw.

Empfohlen wird der Einsatz der Markups Markdown, Textile und Text, da die Parser für 
diese Markupsprachen am weitesten Entwickelt sind.



####################################################################################################
CHANGELOG

2011-12-13: markItUp! Plugin 0.1 (for CONTENIDO 4.8.x)
    * new: Cheatcheet files for BBCode, Markdown, Textile and Texy
    * change: Updated to markItUp! 1.1.12
    * change: Updated parser for BBCode, Markdown, Textile and Texy
    * change: Some other improvements

2009-02-12: markItUp! Plugin 0.0.1_beta2 (for CONTENIDO 4.8.x)
    * new: Added preview CSS file
    * change: Modified Texy markup set

2009-01-02: markItUp! Plugin 0.0.1_beta (for CONTENIDO 4.8.x)
    * new: Added markup preview parser
    * bugfix: Corrected Wiki markup set configuration and severeal minor fixes

2008-12-26: markItUp! Plugin 0.0.1_alpha (for CONTENIDO 4.8.x)
    * First alpha release



####################################################################################################
FEATURES

- Erweitert CONTENIDO um weitere CMS-Typen für Auszeichnungssprachen
- Inhalte lassen sich über den markItUp!-Editor bearbeiten
- Bietet Unterstützung für die Auszeichnungsprachen BBCode, Markdown, Textile, Texy, Wiki



####################################################################################################
VORAUSSETZUNGEN

- Alle Voraussetzungen von CONTENIDO 4.8.x gelten auch für das Plugin



####################################################################################################
INSTALLATION

- Dateien aus dem Plugin-Package in die entsprechenden CONTENIDO-Verzeichnisse kopieren.
  Ausnahme sind Dateien im Ordner "modules", diese sind Modulexporte und werden gesondert installiert.

- In die Adresszeile des Browsers http://localhost/contenido/plugins/markitup/install.php 
  eingeben, dann sollte das Anmeldefenster des Backends erscheinen.
  ("http://localhost/" ist eventuell gegen anderen virtual Host oder Domainnamen ersetzen)

- Im Backend anmelden
  TIP: Sollte der Plugininstaller nach der Anmeldung nicht erscheinen, kann die URL zum Installer 
  manuell aufgerufen werden. Der URL muss die aktuell gültige CONTENIDO Session-ID angehängt werden.
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
Enthält exportierte Module im XML-Format. Diese Exportdateien können im CONTENIDO Backend unter 
"Style -> Module" importiert werden.


cms/css/style_markitup.css:
---------------------------
Stylesheet Datei im Mandantenverzeichnis für die Vorschau.


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
