<?php

namespace Alyssoncpc\QueryGenerator\Exceptions;

use Exception;

class NotFoundException extends Exception {
    public function __construct()
    {
        parent::__construct('404 - Not Found', 404);
    }
}