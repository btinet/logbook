<?php


namespace App;

class Session {

    private bool $sessionStarted = false;

    public function init()
    {
        if ($this->sessionStarted == false) {
            session_start();
            $this->sessionStarted = true;
        }
    }

    public function set($key, $value)
    {
        return $_SESSION[$_ENV['APP_SECRET'] . $key] = $value;
    }

    /**
     * @param $key
     * @param false $secondKey
     * @return false|mixed
     */
    public function get($key, bool $secondKey = false)
    {
        if ($secondKey == true) {
            if (isset($_SESSION[$_ENV['APP_SECRET'] . $key][$secondKey])) {
                return $_SESSION[$_ENV['APP_SECRET'] . $key][$secondKey];
            }
        } else {
            if (isset($_SESSION[$_ENV['APP_SECRET'] . $key])) {
                return $_SESSION[$_ENV['APP_SECRET'] . $key];
            }
        }
        return false;
    }

    public function display(): array
    {
        return $_SESSION;
    }

    public function clear($key)
    {
        unset($_SESSION[$_ENV['APP_SECRET'] . $key]);
    }

    public function destroy()
    {
        if ($this->sessionStarted == true) {
            session_unset();
            session_destroy();
        }
    }

}