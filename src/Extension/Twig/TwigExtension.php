<?php


namespace App\Extension\Twig;


class TwigExtension extends \Twig\Extension\AbstractExtension
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

    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('link', array($this,'generateLink')),
            new \Twig\TwigFunction('route', array($this,'generateRoute')),
        ];
    }

}