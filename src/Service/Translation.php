<?php


namespace App\Service;

use App\Session;
use Spyc;

class Translation
{

    public string $locale;
    public array $availableLanguages;
    private Session $session;

    public function __construct(Session $session, $localization = null)
    {
        $this->session = $session;

        $available_languages =  Spyc::YAMLLoad(project_root."/config/translation.yaml");
        $this->availableLanguages = $available_languages;
        $localization = self::getUserPreferredLanguage($available_languages, strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"]));
        if ($this->session->get('userLocale')){
            $localization = $this->session->get('userLocale');
        }
        setlocale(LC_ALL,$localization);
        if (isset($localization)) {
            $this->locale = $localization;
        } else {
            die($localization);
        }
    }

    public function parse(): array
    {
        $localisation = $this->locale;
        $file = project_root."/translations/$localisation.yaml";
        return Spyc::YAMLLoad($file);
    }

    public function getLocale(){
        return $this->locale;
    }

    public function getUserPreferredLanguage($available_languages, $http_accept_language)
    {
        $default_language = $available_languages[0];
        $available_languages = array_flip($available_languages);
        $langs = array();
        preg_match_all('~([\w-]+)(?:[^,\d]+([\d.]+))?~', strtolower($http_accept_language), $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            list($a, $b) = explode('-', $match[1]) + array('', '');
            $value = isset($match[2]) ? (float)$match[2] : 1.0;
            if (isset($available_languages[$match[1]])) {
                $langs[$match[1]] = $value;
                continue;
            }
            if (isset($available_languages[$a])) {
                $langs[$a] = $value - 0.1;
            }
        }
        if ($langs) {
            arsort($langs);
            return key($langs); // We don't need the whole array of choices since we have a match
        } else {
            return $default_language;
        }
    }

}