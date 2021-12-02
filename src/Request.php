<?php


namespace App;

class Request
{

    /**
     * @var bool
     */
    public bool $isPostRequest;

    /**
     * @var bool
     */
    public bool $isFormSubmitted;

    /**
     * @var string
     */
    public string $csrf_token;

    public string $query;

    public function __construct($csrf_token){
        $this->csrf_token = $csrf_token;
    }

    /**
     * @return bool
     */
    public function isPostRequest(): bool
    {
        $this->isPostRequest = (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST');
        return $this->isPostRequest;
    }

    public function  isFormSubmitted(): bool
    {
        $token = filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS);
        return $this->isFormSubmitted = ($token === $this->csrf_token);
    }

    /**
     * @param string $FormFieldName
     * @return mixed
     */
    public function post(string $FormFieldName)
    {
        $query = filter_input(INPUT_POST, $FormFieldName, FILTER_SANITIZE_STRIPPED);
        return $this->query = $query ?? false;
    }

    /**
     * @param string $FormFieldName
     */
    public function getPostAsArray(string $FormFieldName)
    {
        $query = filter_input_array(INPUT_POST, $FormFieldName, FILTER_DEFAULT);
        return $this->query = $query ?? false;
    }
}