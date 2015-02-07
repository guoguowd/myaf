<?php

/**
 * Method  dump
 *
 * @author jiaxuan
 */
function dump() {
    $argument_list = func_get_args();

    echo "<pre>";

    foreach ($argument_list as $variable) {
        if (is_array($variable)) {
            print_r($variable);
        } else {
            var_dump($variable);
        }
    }

    echo "</pre>\n";
}

/**
 * Method  xdump
 *
 * @author jiaxuan
 */
function xdump() {
    $argument_list = func_get_args();

    $called = debug_backtrace();

    echo '<pre>' . PHP_EOL;

    foreach ($argument_list as $variable) {

        echo '<strong>' . $called[0]['file'] . ' (line ' . $called[0]['line'] . ')</strong> ' . PHP_EOL;

        if (is_array($variable)) {
            print_r($variable);
        } else {
            var_dump($variable);
        }

        echo PHP_EOL;
    }

    echo '</pre>' . PHP_EOL;
    exit();
}
