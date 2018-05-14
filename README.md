# OXID-DebugBar [![Build Status](https://travis-ci.org/FlorianPalme/OXID-DebugBar.svg?branch=master)](https://travis-ci.org/FlorianPalme/OXID-DebugBar)

Implementiert eine DebugBar in den OXID Shop.

## Installation

```
composer require florianpalme/oxid-debugbar
```


## Tabs


### HTTP
- Zeigt im Tab den Response-Code sowie die angefragte OXID-Klasse & Funktion an (cl & fnc)

#### Request
- Auflistung aller $_GET-Parameter
- Auflistung aller $_POST-Parameter
- Anzeige der Request-Header
- Anzeige des Request-Contents
- Auflistung der Server-Parameter

#### Response
- Zeigt die gesendeten Response-Header an

#### Cookies
- Zeigt Cookies des Requests mit Wert an
- Auflistung der Cookies, welche durch den Response gesetzt wurden

#### Sessions
- Auflistung aller $_SESSION-Parameter


### Performance
- Anzeige der Ausführungszeit sowie den maximalen Speicherverbrauch
- Anzeige der Profiler-Ergebnise der startProfile-Funktionen von OXID


### Konfiguration
- Anzeige der OXID-Edition und -Version
- Schnelle Prüfung auf Produktivmodus
- Kurzer Überblick über die PHP-Konfiguration: PHP-Version, Architektur, Zeitzone, Status von OPCache, APCu und Xdebug
- Ausgabe der Parameter der config.inc.php (exkl. Datenbank-Daten)


### Module
- Auflistung aller aktiven Module


## Tabs durch Dritt-Module
Um eigene Tabs hinzuzufügen, muss die eigene metadata.php um das Array ```debugbar``` erweitert werden.
Der Tab wird durch eine ID sowie die Klassen-Definition bestimmt, zum Beispiel:
```php
/**
 * Modul-Informationen
 */
$aModule = [
    'id'			=>	'fpcronjobmanager',
    ...
    /** Debugbar Erweiterungen */
    'debugbar' => [
        'fpcronjobmanager_cronjobs' => 'FlorianPalme\OXIDCronjobManager\Core\DebugBar\Elements\Cronjobs',
    ],
];
```

Die Klasse muss das Interface ```\FlorianPalme\DebugBar\Core\DebugBar\Elements\Element``` integrieren.
Beispiele für die Tab-Integration findest du im [Repository](https://github.com/FlorianPalme/OXID-DebugBar/tree/master/Core/DebugBar/Elements).


# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [1.3.0] - unreleased
### Added
- Generierte DebugBar's werden nun abgespeichert, so dass Sie später erneut angesehen werden können
- Auswahlmöglichkeit des angezeigten Bericht's im Frontend

### Changed
- Generierung der DebugBar an die letztmögliche Stelle in der OXID-Ausführung

## [1.2.0] - 2018-03-18
### Added
- Unittests
- Travis-Integration
- #3 Anzeigen-Begrenzung auf IP integriert

### Changed
- Auslesen der Config-Einstellungen in Core\Config ausgelagert


## [1.1.0] - 2018-02-20
### Added
- Theme-Auswahl
- 4 DebugBar-Themes
- Möglichkeit zur Integration von Tabs durch Dritt-Module
- Renderer um eine Methode zur Erstellung einer Tabelle auf Basis ```\OxidEsales\Eshop\Core\Model\ListModel``` hinzugefügt

### Changed
- Table-Wrapper im Renderer in eigene Methode ausgelagert


## [1.0.1] - 2018-02-02
### Changed
- Update Composer-Name und Lizenz


## [1.0.0] - 2018-02-02
### Added
- Erste Version der OXID-DebugBar

### Changed
- Readme angepasst


[1.2.0]: https://github.com/FlorianPalme/OXID-DebugBar/releases/tag/1.2.0
[1.1.0]: https://github.com/FlorianPalme/OXID-DebugBar/releases/tag/1.1.0
[1.0.1]: https://github.com/FlorianPalme/OXID-DebugBar/releases/tag/1.0.1
[1.0.0]: https://github.com/FlorianPalme/OXID-DebugBar/releases/tag/1.0.0
