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
Die Website wurde nach dem MVC-Muster erstellt (Model-View-Controller) und ist weitestgehend objektorientiert. Derzeit werden noch die
Datenbank-Entitäten als Arrays an den Controller übergeben.

## Dokumentation
1. Ordnerstruktur
2. Anwendungskern
   1. Einstiegspunkt
   2. Routing
3. Controller
   1. Plain
   2. Abstrakte Controller-Klasse
      1. Aufbau
      2. Systemfunktionen
4. View
   1. Direkte Ausgabe
   2. Twig Rendering Engine
5. Model
   1. Datenbank
   2. Entitäten
      1. speichern
      2. aktualisieren
      3. löschen
      4. leeren
   3. Repositories
      1. Allgemeine Abfragen
      2. spezielle Abfragen
6. Erweiterungen
   1. Übersetzungsmodul
   2. Benutzer-Service
   3. Passwort-Service
7. [E-Mail-Benachrichtigung](./Docs/notice.md)

## Drittanbieter
- Steampixel/SimpleRouter
- SPYC/YAML-Loader
- Symfony/Twig

## Entfernt
- KNP-Time-Bundle (keine Kompatibilität)

## To-Do
- "not found"-Fehlerseiten implementieren
- Erinnerungsnachricht besser formatieren
  - Nutzer mit Namen ansprechen
  - Tags integrieren
  - Datum nennen
- Login-Gate für mobile Seiten ausreichend optimieren
- Overflow der Task-Ansicht für mobile Geräte stoppen (horizontales Scrollen bei overflow)
- Englischwörterbuch
- Texte auf der Seite in Abhängigkeit der Systemsprache übersetzen lassen
