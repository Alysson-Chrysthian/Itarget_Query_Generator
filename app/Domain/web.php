<?php

require_once(__DIR__ . '/../Controllers/XmlReaderController.php');

use Alyssoncpc\QueryGenerator\Controllers\XmlReaderFormController;
use Alyssoncpc\QueryGenerator\Domain\Router;

Router::get('/', [new XmlReaderFormController, 'render']);
Router::post('/generate-query', [new XmlReaderFormController, 'generateQuery']);