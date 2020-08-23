<?php 

    define('base_path', str_replace(DIRECTORY_SEPARATOR, '/', dirname(__FILE__)) . '/');

    require_once base_path . 'system/core/File.php';

    require_once base_path . 'system/func/Parser.php';

    require_once base_path . 'system/core/Parser.php';

    require_once base_path . 'system/core/Main.php';

    require_once base_path . 'vendor/autoload.php';

    Purge\Main::init();
?>