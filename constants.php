<?php

define('RESOURCE_DIR', BASE_DIR.'/resources');
define('VIEW_DIR', RESOURCE_DIR.'/views');
define('CONFIG_DIR', BASE_DIR.'/config');
define('STORAGE_DIR', BASE_DIR.'/storage');
define('CACHE_DIR', STORAGE_DIR.'/cache');
define('BOOTSTRAP_DIR', BASE_DIR.'/bootstrap');
define('VENDOR_DIR', BASE_DIR.'/vendor');
define('APP_DIR', BASE_DIR.'/app');
define('ENTITY_DIR', APP_DIR.'/Entities');
define('PUBLIC_DIR', BASE_DIR.'/public');
define('UPLOAD_DIR', PUBLIC_DIR.'/uploads');
define('LOG_DIR', STORAGE_DIR.'/logs');

define('DEBUG_MODE', true);
define('SITE_URL', 'http://social.local/');

# define('TWIG_CACHE_DIR', CACHE_DIR.'/twig');
define('TWIG_CACHE_DIR', false);
