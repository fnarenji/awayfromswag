<?php

define(DS, DIRECTORY_SEPARATOR);
define(ROOT, __DIR__ . DS);
define(WEBROOT, '/');

define(DEBUG, true);

if(DEBUG) {
    ini_set('display_errors', true);
    ini_set('html_errors', true);
    error_reporting(E_ALL);
}
