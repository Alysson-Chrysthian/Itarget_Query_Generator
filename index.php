<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Alyssoncpc\QueryGenerator\Domain\Router;

require_once(__DIR__ . '/app/Domain/web.php');

$errors = [];

try {
    $data = Router::checkUri();

    if (isset($data['errors']))
        $errors = $data['errors'];
    if (isset($data['message']))
        $message = $data['message'];
} catch (Exception $exception) {
    http_response_code($exception->getCode());
}
