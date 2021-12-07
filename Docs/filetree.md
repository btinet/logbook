# Ordnerstruktur

````
./
|__asstes/                          Unkompilierte Style Sheets und Grafiken
|   |__images/  
|   |__styles/
|       |__app.scss     
|    
|__config/                          globale Konfiguration
|   |__env.bak.yaml                 umbenennen in env.yaml
|   |__routes.yaml                  alle Routen
|   |__translation.yaml             alle verfügbaren Sprachen für den Translator
|
|__cron/                            Cron-Job für den E-Mail-Versand
|   |__Database.php
|   |__notice_user.php
|
|__Docs/                            diese Dokumentation
|
|__public/                          Webroot
|   |__images/                      Bild- und Grafikdateien
|   |__.htaccess                    Interpreteranweisungen für den Webserver
|   |__app.js                       globales Javascript
|   |__favicon.png
|   |__favicon.svg
|   |__index.php                    Entrypoint der Website
|
|__sql/
|   |__tables.txt                   SQL-Script für die Erstellung der Tabellen
|
|__src/                             Sourceroot der Anwendung
|   |__Controller/                  Controller-Klassen
|   |   |__AppController.php
|   |   |__SecurityController.php
|   |   |__TagController.php
|   |   |__TaskController.php
|   |
|   |__Entity/                      Entitäten der Datenbanktabellen
|   |   |__Tag.php
|   |   |__Task.php
|   |   |__User.php
|   |
|   |__Extension/                   Erweiterungen für Drittanbieteranwendungen
|   |   |__Twig/
|   |       |__TwigExtension.php    
|   |
|   |__Repository/                  benutzerdefinierte Methoden zum Abruf von Daten
|   |   |__TagRepository.php
|   |   |__TaskRepository.php
|   |   |__UserRepository.php
|   |
|   |__Service/                     Hilfsklassen
|   |   |__EntityManagerService.php
|   |   |__EntityRepositoryService.php
|   |   |__PasswordService.php
|   |   |__TranslationService.php
|   |   |__UserService.php
|   |
|   |__AbstractController.php       Abstrakte Controllerklasse mit allgemeinen Funktionen
|   |__Bootstrap.php                Anwendungskern
|   |__Database.php                 Modelklasse
|   |__Request.php                  Requestklasse
|   |__Session.php                  Sessionklasse
|
|__templates/                       HTML-Templates
|   |__app/
|   |__messages/
|   |__navigation/
|   |__security/
|   |__tag/
|   |__task/
|   |__app.html.twig                2nd-Level-Basistemplate
|   |__authentication.html.twig     
|   |__base.html.twig               1st-Level-Basistemplate
|
|__translations/                    Wörterbücher
|   |__de.yaml
|   |__en.yaml
|
|__.gitignore                       
|__composer.json                    Repositoryinformationen
|__composer.lock                    Lock-Datei
|__README.md                        Index der Dokumentation
````