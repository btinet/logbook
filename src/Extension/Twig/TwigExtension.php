<?php


namespace App\Extension\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigExtension extends AbstractExtension
{
    private function getHost(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return $protocol.$_SERVER['HTTP_HOST'];
    }

    public function generateLink($uri): string
    {
        $uri = $this->getHost().'/'.$uri;
        return $uri;
    }

    public function generateRoute($uri, array $mandatory = null,$anchor = null): string
    {
        $uri = $this->getHost().'/'.$uri;
        if ($mandatory) {
            foreach ($mandatory as $name => $value) {
                $uri .= "/$value";
            }
        }
        if($anchor){
            $uri .= "#$anchor";
        }
        return $uri;
    }

    function twig_json_decode($json)
    {
        return json_decode($json, true);
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('json_decode', [$this, 'twig_json_decode']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('link', array($this,'generateLink')),
            new \Twig\TwigFunction('route', array($this,'generateRoute')),
            new \Twig\TwigFunction('json_decode', array($this,'twig_json_decode')),
        ];
    }

}