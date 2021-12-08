# Projektinhalt

1. [Ordnerstruktur](#ordnerstruktur)
2. [Assets](#assets)
3. [Config](#config)
4. [Public Webroot](#public-webroot)
5. [Source](#source)
6. [Templates](#templates)
7. [Translations](#translations)

## Ordnerstruktur
Das Projekt teilt sich unter folgender Ordnerstruktur in logische Bereiche auf, die anschließend
näher erläutert werden.
````
/
|__asstes/
|   |__images/  
|   |__styles/
|       |__app.scss     
|    
|__config/
|   |__env.bak.yaml
|   |__routes.yaml
|   |__translation.yaml
|
|__cron/
|   |__Database.php
|   |__notice_user.php
|
|__Docs/
|
|__public/
|   |__images/
|   |__.htaccess
|   |__app.js
|   |__favicon.png
|   |__favicon.svg
|   |__index.php
|
|__sql/
|   |__tables.txt
|
|__src/
|   |__Controller/
|   |   |__AppController.php
|   |   |__SecurityController.php
|   |   |__TagController.php
|   |   |__TaskController.php
|   |
|   |__Entity/
|   |   |__Tag.php
|   |   |__Task.php
|   |   |__User.php
|   |
|   |__Extension/
|   |   |__Twig/
|   |       |__TwigExtension.php    
|   |
|   |__Repository/
|   |   |__TagRepository.php
|   |   |__TaskRepository.php
|   |   |__UserRepository.php
|   |
|   |__Service/
|   |   |__EntityManagerService.php
|   |   |__EntityRepositoryService.php
|   |   |__PasswordService.php
|   |   |__TranslationService.php
|   |   |__UserService.php
|   |
|   |__AbstractController.php
|   |__Bootstrap.php
|   |__Database.php
|   |__Request.php
|   |__Session.php
|
|__templates/
|   |__app/
|   |__messages/
|   |__navigation/
|   |__security/
|   |__tag/
|   |__task/
|   |__app.html.twig
|   |__authentication.html.twig     
|   |__base.html.twig
|
|__translations/
|   |__de.yaml
|   |__en.yaml
|
|__.gitignore                       
|__composer.json
|__composer.lock
|__README.md
````
## Assets
Stylesheets werden mithilfe von SASS zu CSS kompiliert. Die kompilierten Stylesheets werden im
Ordner **/public/styles** gespeichert. Sie können aber auch in jedem anderen Ordner unterhalb von **/public**
gespeichert werden.

Mehr zu den Assets findest du [hier](StyleAssets/assets.md).

## config
Hier werden globale Einstellungen vorgenommen, die das gesamte Projekt betreffen.

### Environment
Hier werden Entwicklungsmodus, Zugangsdaten, Benutzerklassen und mehr eingestellt.
````yaml
#env.yaml
APP_ENV:    development
APP_SECRET: 8a64534074c7c7e226b0c56f8af67bf4

DB_TYPE:    mysql
DB_HOST:    localhost
DB_NAME:    logbook_db
DB_USER:    root
PASS:

USER_ENTITY: App\Entity\User

EMAIL_SENDER_ADDRESS: name@email.kom
EMAIL_SENDER_NAME: Max Mustermann

````
Key|Wert|Typ|Beschreibung|
|---|---|---|---|
|APP_ENV|development|_string_|caching deaktiviert, Ausgabe aller Fehler|
| | production|_string_|caching aktiviert, Unterdrückung der Fehlerausgabe|
|APP_SECRET| |_string_|wird Session-Werten vorangestellt|
|DB_TYPE|z.B. 'mysql'|_string_|gibt Datenbank-Treiber an|
|DB_HOST| |_string_|Host der Datenbank|
|DB_NAME| |_string_|Name der Datenbank|
|DB_USER| |_string_|Datenbankbenutzer|
|PASS| |_string_|Benutzerpasswort|
|USER_ENTITY| |_string_|vollständiger Name der User-Klasse|
|EMAIL_SENDER_ADDRESS| |_string_|Adresse des Absenders|
|EMAIL_SENDER_NAME| |_string_|Name des Absenders|

### Routes
Hier werden die Routen der App festgelegt und welche Controller-Methode aufgerufen werden soll.
````yaml
#routes.yaml
route_name:
  expression: '/path/to/route/([0-9]*)'
  value:
  controller: 'App\Controller\AppController'
  method: 'index'
  request: 'get'

````
Key|Wert|Typ|Beschreibung|
|---|---|---|---|
|route_name|array()|_array_|Key muss eindeutig sein|
|expression|/path/to/route/([a-z-0-9]*)|_string_|Request-URI der Route, momentan nur eine Variable erlaubt|
|value|true / false|_bool_|muss true sein, wenn **expression** eine Variable enthält|
|controller| |_string_|vollständiger Name der Controller-Klasse|
|method| |_string_|Name der Controller-Methode|
|request|get, post,delete ['get','post','delete'] |_string / array_|erlaubte Request-Methode(n)|

### Translation
Gib an, welche Lokalisationen du anbieten möchtest.
````yaml
#translation.yaml
0: en
1: de
2: fr
3: es

````
Gib für jede verfügbare Sprache das entsprechende ICC-Landeskürzel(2 Zeichen) an.
Für jede Angabe muss unter ``/translations`` eine Datei mit [ICC].yaml vorhanden sein,
für Deutschland (de) beispielsweise also die Datei _de.yaml_.
 
## Public Webroot
Der Webroot ist für den Anwender zugänglich und enthält daher neben der üblichen index.php
auch weitere Elemente wie Bilder, kompilierte Stylesheets, Fonts und Javascripts.

Für die Basisfunktion reichen jedoch folgende zwei Dateien aus:

1. [.htaccess](../public/.htaccess)
2. [index.php](../public/index.php)

### Webserver/htaccess
Damit das Routing ordnungsgemäß funktioniert, muss dem Webserver mitgeteilt werden,
dass nur die _index.php_ aufgerufen werden soll, solange die angeforderte Datei oder
das Verzeichnis nicht existiert. Alle Anfragen sollen über diese Indexdatei geleitet
werden.

````apacheconf
#.htaccess

DirectoryIndex index.php

# enable apache rewrite engine
RewriteEngine on

# set your rewrite base
# Edit this in your init method too if your script lives in a subfolder
RewriteBase /

# Deliver the folder or file directly if it exists on the server
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Push every request to index.php
RewriteRule ^(.*)$ index.php [QSA]

````

Die _index.php_ tut nichts anderes, als den Anwendungskern zu laden und alle Anfragen
zwischengespeichert dahin weiterzuleiten. Nachdem die letzte Ausgabe zurückgegeben wurde,
wird der Speicher entleert und ausgegeben (_ob_start_ und _ob_flush_).

````php
#index.php

<?php

use App\Bootstrap;
require_once dirname(__DIR__).'/vendor/autoload.php';

ob_start();

define('project_root', dirname(__DIR__));
define('host', $_SERVER['HTTP_HOST']);

$app = new Bootstrap();
$app->addRoutes();
$app->addNotFound();
$app->init();

ob_flush();

````

## Source
Im Ordner _/src_ liegen all die Projektdateien, die den Charakter deiner Anwendung
ausmachen. Dazu zählen im Besonderen die Controller- und Modellstrukturen.
Diese werden im entsprechenden Unterordner gespeichert.

Weitere Information findest du [hier](../README.md) 

## Templates
Die View-Schicht wird durch das HTML-Template-System abgebildet und gewährleistet damit
die strikte Trennung nach dem MVC-Muster zwischen Datenschicht, Kontrollschicht
und Ausgabeschicht.

Weitere Information findest du [hier](../README.md)

## Translations
Entsprechend deiner Einstellungen in der [translation.yaml](../config/translation.yaml)
musst du hier für jede Sprache ein eigenes Wörterbuch anlegen. Über welche Vokabeln
dein Wörterbuch verfügt, kannst du selbst entscheiden. Folgende Vokabeln müssen jedoch
vorhanden sein:

Wort|Bedeutung|verwendet von|
|---|---|---|
|1601| Passwort ist keine Zeichenkette | App\Service\PasswordService |
|1602| Passwörter stimmen nicht überein | App\Service\PasswordService |
|1603| Passwort ist nicht lang genug | App\Service\PasswordService |
|1604| Passwort verwendet unerlaubte Zeichen | App\Service\PasswordService |
|16051|  Passwort enthält zu viele Leerzeichen | App\Service\PasswordService |
|16052|  Passwort enthält keine Großbuchstaben | App\Service\PasswordService |
|16053|  Passwort enthält keine Kleinbuchstaben | App\Service\PasswordService |
|16054|  Passwort enthält keine Sonderzeichen | App\Service\PasswordService |
|16055|  Passwort enthält keine Ziffern | App\Service\PasswordService |
|21011|  Benutzername existiert bereits | App\Service\UserService |
|210111| Benutzer nicht gefunden | App\Service\UserService |
|210112| Benutzer und Passwort stimmen nicht überein | App\Service\UserService |
|21012| E-Mail-Adresse existiert bereits | App\Service\UserService |
|21022|  E-Mail-Adresse ist nicht gültig | App\Service\UserService |
|21031|  Benutzername ist keine Zeichenkette | App\Service\UserService |
|21034|  Vorname ist keine Zeichenkette | App\Service\UserService |
|21035|  Nachname ist keine Zeichenkette | App\Service\UserService |

Weitere Information über die Verwendung von Translations findest du [hier](../README.md)
