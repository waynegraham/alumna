<?php

/**
 * Project: UVa Women Alumna
 */

define('ALUMNA_DIR', dirname(__FILE__));
define('TEMPLATE_DIR', ALUMNA_DIR . '/templates');

require_once ALUMNA_DIR . '/libs/Mustache.php';
require_once ALUMNA_DIR . '/libs/MustacheLoader.php';

$templates = new MustacheLoader(TEMPLATE_DIR);
$partials  = array(
    'header' => $templates['header'],
    'footer' => $templates['footer']
);
$mustache  = new Mustache(null, null, $partials);

