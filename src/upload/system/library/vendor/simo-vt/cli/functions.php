<?php

// Function definitions

if (!function_exists('oc_cli_output')) {
    function oc_cli_output($message, $exit_status = NULL) {
        fwrite(STDOUT, $message . PHP_EOL);

        if ($exit_status !== NULL) {
            exit($exit_status);
        }
    }
}

if (!function_exists('oc_cli_dirname_recursive')) {
    function oc_cli_dirname_recursive($dir, $level = 1) {
        do {
            $dir = dirname($dir);
        } while (0 <= $level -= 1);

        return $dir;
    }
}

if (!function_exists('oc_cli_find_base_dir')) {
    function oc_cli_find_base_dir() {
        return oc_cli_dirname_recursive(realpath(__FILE__), 5);
    }
}

if (!function_exists('oc_cli_find_version')) {
    function oc_cli_find_version() {
        $current_dir = oc_cli_dirname_recursive(realpath(__FILE__), 5);

        $index_contents = file_get_contents($current_dir . '/index.php');

        $matches = array();

        preg_match('~define\s*\(\s*\'VERSION\'\s*,\s*\'(.*?)\'\s*\)\s*;~', $index_contents, $matches);

        if (!empty($matches[1])) {
            return $matches[1];
        } else {
            oc_cli_output("Cannot find OpenCart version.", 1);
        }
    }
}