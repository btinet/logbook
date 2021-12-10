# Anwendungskern

1. [Aufbau der Bootstrap-Klasse](#aufbau-der-bootstrap-klasse)
2. [Routing](#routing)
3. [Aufruf des Controllers](#aufruf-des-controllers)

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

## Routing

````php
//[...]

public function addRoutes()
    {
        foreach ($this->routes as $route)
        {
                $this->routing->add($route['expression'], function () use ($route) {
                    $arguments = func_get_args();
                    try {
                        return $this->runControllerMethod($route['controller'], $route['method'], $arguments);
                    } catch (Exception $e) {
                        return 'Exception abgefangen: '. $e->getMessage() . "\n";
                    }
                }, $route['request']);
        }
    }

//[...]

````

Die in der **config/routes.yaml** angegebenen Routen werden eingelesen und immer dann aufgerufen,
wenn der Ausdruck der Request-URI zur jeweiligen Route passt.

## Aufruf des Controllers

````yaml
#routes.yaml

app_index:
  expression: '/'
  controller: 'App\Controller\AppController'
  method:     'index'
  request:    'get'

````

Der Ausdruck der Route **app_index** lautet ``'/'`` und entspricht damit der Request-URI
``http://www.host.de/``.

Folglich wird der Controller ``App\Controller\AppController`` mit der Methode ``index`` (sofern
die Request-Methode _GET_ war) aufgerufen.

Erfahre mehr über die [Verwendung von Controllern](../README.md).