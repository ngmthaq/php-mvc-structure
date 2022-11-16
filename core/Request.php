<?php

namespace Core;

final class Request
{
    private array $queryData;
    private array $inputData;
    private array $headersData;
    private array $cookieData;
    private array $sessionData;
    private array $serverData;

    public function __construct()
    {
        $this->getInput();
        $this->getQuery();
        $this->getHeaders();
        $this->getCookie();
        $this->getSession();
        $this->getServer();
    }

    final public function query(string $key = "*"): mixed
    {
        if ($key === "*") return $this->queryData;
        if (array_key_exists($key, $this->queryData)) return $this->queryData[$key];
        return null;
    }

    final public function input(string $key = "*"): mixed
    {
        if ($key === "*") return $this->inputData;
        if (array_key_exists($key, $this->inputData)) return $this->inputData[$key];
        return null;
    }

    final public function headers(string $key = "*"): mixed
    {
        if ($key === "*") return $this->headersData;
        if (array_key_exists($key, $this->headersData)) return $this->headersData[$key];
        return null;
    }

    final public function cookie(string $key = "*"): mixed
    {
        if ($key === "*") return $this->cookieData;
        if (array_key_exists($key, $this->cookieData)) return $this->cookieData[$key];
        return null;
    }

    final public function session(string $key = "*"): mixed
    {
        if ($key === "*") return $this->sessionData;
        if (array_key_exists($key, $this->sessionData)) return $this->sessionData[$key];
        return null;
    }

    final public function server(string $key = "*"): mixed
    {
        if ($key === "*") return $this->serverData;
        if (array_key_exists($key, $this->serverData)) return $this->serverData[$key];
        return null;
    }

    private function getQuery()
    {
        $query = [];

        foreach ($_GET as $key => $value) {
            if (is_string($value)) {
                $query[$key] = trim($value);
            }
        }

        $this->queryData = $query;
    }

    private function getInput()
    {
        $input = [];

        foreach ($_POST as $key => $value) {
            if (is_string($value)) {
                $input[$key] = trim($value);
            }
        }

        $this->inputData = $input;
    }

    private function getCookie()
    {
        $cookie = [];

        foreach ($_COOKIE as $key => $value) {
            if (is_string($value)) {
                $cookie[$key] = trim($value);
            }
        }

        $this->cookieData = $cookie;
    }

    private function getSession()
    {
        $session = [];

        foreach ($_SESSION as $key => $value) {
            if (is_string($value)) {
                $session[$key] = trim($value);
            }
        }

        $this->sessionData = $session;
    }

    private function getServer()
    {
        $server = [];

        foreach ($_SERVER as $key => $value) {
            if (is_string($value)) {
                $server[$key] = trim($value);
            }
        }

        $this->serverData = $server;
    }

    private function getHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }

            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }

        $this->headersData = $headers;
    }
}
