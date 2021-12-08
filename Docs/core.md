# Anwendungskern

1. [Aufbau der Bootstrap-Klasse](#aufbau-der-bootstrap-klasse)
2. Routing
3. Aufruf des Controllers

## Aufbau der Bootstrap-Klasse
Die index.php der Website initiiert eine neue Bootstrap-Klasse und lädt mit der
Hilfsklasse **SPYC/YAML** die zuvor definierten Routen in ein Array. Das Array wird später
von der ebenfalls initiierten Routing-Klasse verwendet, um die Routen anzumelden.

Zusätzlich werden die Environment-Variablen in der globalen Variable  ``$_ENV`` gespeichert.
Andere Klassen können so sehr leicht auf die benötigten Informationen zugreifen.

````php
#Bootstrap.php
<?php

namespace App;

//[...]

class Bootstrap
{
    private Route $routing;
    private array $routes;

    public function __construct()
    {
        $this->routing = new Route();
        $this->routes = Spyc::YAMLLoad(project_root.'/config/routes.yaml');
        $env = Spyc::YAMLLoad(project_root.'/config/env.yaml');
        foreach ($env as $key => $value)
        {
            $_ENV[$key] = $value;
        }
    }
    
//[...]

}

````

Die Bootstrap-Klasse hängt von folgenden Variablen ab:

| Variable | Typ | Wert | Bemerkung |
|---|---|---|---|
|project_root|Konstante|dirname(\__DIR__)|**/public** muss eine Ebene unter dem Projekt-Root liegen|
|host|Konstante|$_SERVER['HTTP_HOST']||

Die **autoload.php** von Composer muss als erstes per ``require_once()`` geladen werden.