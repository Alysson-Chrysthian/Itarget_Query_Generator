<?php

namespace Alyssoncpc\QueryGenerator\Helpers;

class View {
    public static function render($view_name)
    {
        require_once(__DIR__ . '/../pages/' . $view_name . '.php');
    }
}