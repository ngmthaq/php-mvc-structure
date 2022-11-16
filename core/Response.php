<?php

namespace Core;

final class Response
{
    public const STATUS_SUCCESS = 200;
    public const STATUS_BAD_REQUEST = 400;
    public const STATUS_UNAUTHORIZED = 401;
    public const STATUS_FORBIDDEN = 403;
    public const STATUS_NOT_FOUND = 404;
    public const STATUS_METHOD_NOT_ALLOWED = 405;
    public const STATUS_FAILED_VALIDATION = 422;
    public const STATUS_INTERNAL_SERVER_ERROR = 500;
    public const STATUS_SERVICE_UNAVAILABLE = 503;

    final public function json(mixed $data, int $status = self::STATUS_SUCCESS, array $headers = []): void
    {
        $json = json_encode($data);
        if ($json === false) {
            $json = json_encode(["error" => json_last_error_msg()]);
            if ($json === false) {
                $json = '{"error":"unknown"}';
            }
            http_response_code(self::STATUS_INTERNAL_SERVER_ERROR);
        } else {
            http_response_code($status);
        }

        foreach ($headers as $header) {
            header($header);
        }

        header('Content-Type: application/json; charset=utf-8');
        echo $json;
    }

    final public function redirect(string $path): void
    {
        header('Location: ' . $path);
    }

    final public function flash(string $key, string $value): Response
    {
        $_SESSION[$key] = $value;

        return $this;
    }
}
