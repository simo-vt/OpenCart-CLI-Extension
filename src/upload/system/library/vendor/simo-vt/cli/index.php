<?php

// Get this dir
$this_dir = dirname(__FILE__);

// Change directory to allow the script to be called from anywhere, and also to refer to required files more easily.
chdir(dirname(__FILE__));

// Require files
require_once './functions.php';

// Let's check for the CLI mode. We expect an environment variable OCCLI=1
if (getenv('OCCLI') != '1') {
    // Redirect
    header("Location:/");

    // And exit with status code 1
    oc_cli_output("Access denied.", 1);
}

// Determine the type of app, so we can use the admin folder later
$opencart_app = getenv('OCCLI_TYPE') ? getenv('OCCLI_TYPE') 'catalog';

if (empty($argv[1]) || !is_dir($argv[1])) {
    oc_cli_output("Invalid request. Expecting one of: catalog / admin / <existing-custom-admin-name>", 1);
} else {
    $opencart_app = $argv[1];
}

// Version
if (!defined('VERSION')) define('VERSION', oc_cli_find_version()); // Version according to OC version.

// Status constant. Should be set to TRUE.
if (!defined('OPENCART_CLI_MODE')) define('OPENCART_CLI_MODE', TRUE);

// Set server vars
if (!isset($_SERVER['SERVER_PORT'])) {
    $_SERVER['SERVER_PORT'] = 80;
}

$base_dir = oc_cli_find_base_dir();

// If app is "catalog", use the config in the root dir. Otherwise, regard $opencart_app as the OpenCart admin folder.
$base_dir = $opencart_app == 'catalog' ? './' : './' . $opencart_app . '/';

// Configuration
if (is_file($base_dir . 'config.php')) {
    require_once($base_dir . 'config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
    oc_cli_output("OpenCart not installed.", 1);
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

$application_config = 'oc_cli';

// Application
require_once(DIR_SYSTEM . 'framework.php');