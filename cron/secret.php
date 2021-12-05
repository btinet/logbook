<?php

namespace cron;

class secret
{
    protected array $secret;

    public function __construct()
    {
        $this->setSecret([
        'DB_TYPE' => 'mysql',
        'DB_HOST' => 'localhost',
        'DB_NAME' => 'logbook_db',
        'DB_USER' => 'wagnermaster',
        'PASS' => 'Camilla@23',
        'EMAIL_SENDER_NAME' => 'Benjamin Wagner',
        'EMAIL_SENDER_ADDRESS' => 'service@wagnerpictures.com'
    ]);
    }

    /**
     * @return array
     */
    public function getSecret(): array
    {
        return $this->secret;
    }

    /**
     * @param array $secret
     */
    public function setSecret(array $secret): void
    {
        $this->secret = $secret;
    }

}
