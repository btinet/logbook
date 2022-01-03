# Informatikprojekt E-Phase E1
Digitale Aufgabenverwaltung mit Erinnerungsfunktion.

## Anforderungsdefinition
Im Rahmen des Schulunterrichts im Fach Informatik soll eine Website mit folgenden Anforderungen erstellt werden:

1. Verwendung von mindestens HTML, CSS und Javascript
2. Muss eine Navigation enthalten
3. Mindestens vier Unterseiten
4. Sinnvolle Ordnerstruktur
5. Mindestens ein Algorithmus

## Strukturkonzept
Die Website wurde nach dem MVC-Muster erstellt (Model-View-Controller) und ist weitestgehend objektorientiert.
Derzeit werden noch die
Datenbank-Entitäten als Arrays an den Controller übergeben.

## Dokumentation
1. [Paketinhalt](./Docs/package.md)
2. [Anwendungskern](./Docs/core.md)
3. [Controller](./Docs/controller.md)
4. View
5. Model
6. [Erweiterungen](./Docs/extensions.md)

## Drittanbieter
- [steampixel/SimplePHPRouter](https://github.com/steampixel/simplePHPRouter)
- [mustangostang/spyc](https://github.com/mustangostang/spyc)
- [twigphp/twig](https://github.com/twigphp/Twig)

## Asset Bundler
- webpack

## To-Do
- Funktion für Url-Generator in Controllern und Templates entwickeln
- Erinnerungsnachricht besser formatieren
  - Nutzer mit Namen ansprechen
  - Tags integrieren
  - Datum nennen
- Login-Gate für mobile Seiten ausreichend optimieren
- Overflow der Task-Ansicht für mobile Geräte stoppen (horizontales Scrollen bei overflow)
- Texte auf der Seite in Abhängigkeit der Systemsprache übersetzen lassen
- Fallback-Variable für locale