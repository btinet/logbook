# Controller

1. [Zweck und Funktion](#zweck-und-funktion)
2. [Aufbau einer PHP-Klasse](#aufbau-einer-php-klasse)
   1. [Namespace](#namespace)
   2. [Klasse](#klasse)
   3. [Methoden](#methoden)
3. [Abstrakte Controller-Klasse](#abstrakte-controller-klasse)
   1. [Zugriffssteuerung](#zugriffssteuerung)
   2. [Bedingte Weiterleitung](#bedingte-weiterleitung)


## Zweck und Funktion
Der sogenannte Controller ist das Bindeglied zwischen der
View- und der Modelschicht. Er beinhaltet die Geschäftslogik
und steuert, was an die View ausgegeben werden soll.
Außerdem steuert er, was im Model gespeichert oder von dort
abgerufen werden soll.

Die entsprechende Methode (Funktion) des jeweiligen Controllers (Klasse) wird in der [Bootstrap](./core.md#aufruf-des-controllers)-Klasse
aufgerufen. Der Aufbau eines Controllers ist sehr simpel:

```php
#src/Controller/AppController.php
<?php

namespace App\Controller;


class AppController {

    public function index(): string
    {
        return 'Ich bin ein Controller';
    }

}

```
## Aufbau einer PHP-Klasse
Die php-Klasse besteht aus drei Basiselementen:
1. namespace
2. class
3. function

### Namespace
Der **namespace** gibt an, wo sich die .php-Datei befindet. Der Namespace
ist außerdem hilfreich, wenn es mehrere .php-Dateien mit gleichem Namen gibt.

Beispiel:

```php
#Pfad/zu/anderem/Ordner/AppController.php
<?php

namespace Anderer\Namespace\Controller;


class AppController {

    public function index(): string
    {
        return 'Ich bin ein anderer Controller';
    }

}

```

Mithilfe des Namespace weiß das Programm, in welchem Ordner es nach der
angeforderten Datei suchen muss.

### Klasse
Mit **class{}** definieren wir eine Klasse. Alles, was sich innerhalb
der Klammern befindet, gehört zu dieser Klasse. Denn es ist ebenfalls möglich,
mehrere Klassen in einer Datei zusammenzufassen:

Beispiel:

```php
#Pfad/zu/anderem/Ordner/SammelController.php
<?php

namespace Anderer\Namespace\Controller;


class ScreenController {

    public function print(): string
    {
        return 'Ich bin für Bildschirme zuständig.';
    }

}

class PrintController {

    public function print(): string
    {
        return 'Ich bin für Ausdrucke zuständig.';
    }

}

```
### Methoden
Innerhalb der Klassen werden Funktionen definiert, sogenannte **Methoden**.
Diese beinhalten die Kontrollstrukturen. Der Basiscontroller dieser Anwendung
sieht folgendermaßen aus:

```php
#src/Controller/AppController.php
<?php

namespace App\Controller;

use App\AbstractController;
use ReflectionException;

class AppController extends AbstractController
{

    /**
     * @throws ReflectionException
     */
    public function index(): void
    {
        $this->denyUnlessGranted('ROLE_USER');
        $this->redirect(302,'task');
    }


    public function imprint(): string
    {
        return $this->render('app/impressum.html.twig',[
            'flash' => $this->getFlash(),
            'title' => 'TMA - Impressum'
        ]);
    }

    public function tos(): string
    {
        return $this->render('app/terms_of_use.html.twig',[
            'flash' => $this->getFlash(),
            'title' => 'TMA - Nutzungsbedingungen'
        ]);
    }

}

```
## Abstrakte Controller-Klasse
Das Framework bietet eine optionale abstrakte Controller-Klasse an,
um allgemeine Aufgaben bereitzustellen. Dazu zählt zum Beispiel eine
Zugriffssteuerung.

Mehr über die [abstrakte Controller-Klasse](extensions.md).

### Zugriffssteuerung
Wird die Methode **index** aufgerufen, wird zunächst geprüft,
ob eine gültige User-Session existiert. `` $this->denyUnlessGranted('ROLE_USER'); ``

Ist dies nicht der Fall, wird der Benutzer zum Login-Gate weitergeleitet.
Sollte jedoch eine gültige User-Session existieren, wird im zweiten
Schritt geprüft, ob der Benutzer über die angegebene Benutzerrolle
verfügt. Falls ja, wird **true** zurückgegeben, andernfalls wird
der Benutzer mit einer Warnung ausgeloggt.

```php
/**
     * @throws ReflectionException
     */
    public function denyUnlessGranted(string $role = null): bool
    {
        if(!$user = $this->getUser()){
            $this->setFlash(400,'warning');
            $this->redirect(302,'login');
        }

        $userRole = $user['roles'];

        if($role && !in_array($role,json_decode($userRole))){
            $this->setFlash(401,'danger');
            $this->redirect(302,'logout');
        }
        return true;
    }

```
### Bedingte Weiterleitung
Ist der Benutzer eingeloggt und verfügt über die richtige Benutzer-Rolle,
wird er zum **TaskController** weitergeleitet. Dieser hält Methoden zur
Task-Verwaltung bereit.

Die anderen beiden Methoden **imprint** und **tos** geben jeweils eine
statische Seite für das Impressum und die Terms of Service aus.

Der Controller steuert also konkret, was unter welchen Bedingungen ausgeben
oder aber auch gespeichert und weiterverarbeitet wird.