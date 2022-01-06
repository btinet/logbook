# Controller

1. [Zweck und Funktion](#zweck-und-funktion)


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

Die php-Klasse besteht aus drei Basiselementen:
1. namespace
2. class
3. function

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

Innerhalb der Klassen werden Funktionen definiert, sogenannte **Methoden**.
Diese beinhalten die Kontrollstrukturen. Der BasisController dieser Anwendung
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