<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

function dump($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}