# OXID-DebugBar

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


## Geplante Funktionen
- Farb-Schemen
- DebugBar erweiterbar durch Dritt-Module
- Anzeige auf IP beschränken
- Fehlende Übersetzungen auflisten
- ...


# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2018-02-02
### Added
- Erste Version der OXID-DebugBar

### Changed
- Readme angepasst

