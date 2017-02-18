<?php
 require_once('config.php');
set_include_path(get_include_path() . PATH_SEPARATOR . 'restler');
spl_autoload_register('spl_autoload');
$r = new Restler();
$r->addAPIClass('tmsapi');
$r->handle();