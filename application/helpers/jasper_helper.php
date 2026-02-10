<?php

function jasper_init() {
        spl_autoload_register(function($class) {
                    $location = "jas/" . $class . '.php';
                    if (!is_readable($location))
                        return;
                    require_once $location;
        });
}


?>