<?php

/**
 * Project: UVa Women Alumna
 */

define('ALUMNA_DIR', dirname(__FILE__));
define('TEMPLATE_DIR', ALUMNA_DIR . '/templates');
define('DB_CONFIG_FILE', ALUMNA_DIR . '/config/database.ini');

require_once ALUMNA_DIR . '/libs/Mustache.php';
require_once ALUMNA_DIR . '/libs/MustacheLoader.php';
require_once ALUMNA_DIR . '/libs/limonade.php';
require_once ALUMNA_DIR . '/libs/db_manager.php';
