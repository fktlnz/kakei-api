<?php
/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.2
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2019 Fuel Development Team
 * @link       https://fuelphp.com
 */

/**
 * -----------------------------------------------------------------------------
 *  Global database settings
 * -----------------------------------------------------------------------------
 *
 *  Set database configurations here to override environment specific
 *  configurations
 *
 */

return array(

    'active' => 'pdo',

    'pdo' => array(
        'type'           => 'pdo',
        'connection'     => array(
            //'dsn'        => 'mysql:host=3.141.12.83;dbname=kakei',
             'dsn'        => 'mysql:host=127.0.0.1;dbname=kakei', //テスト環境
            //'username'       => 'root',
            //'password'       => 'Yhe76xgt*',
            'username'       => 'root',
            'password'       => 'root',
            'persistent'     => false,
            'compress'       => false,
        ),
        'identifier' => '`',
        'table_prefix' => '',
        'charset'      => 'utf8',
        'caching'      => false,
        'profiling'    => true,
    ),
    'mysqli' => array(
        'type'           => 'mysqli',
        'connection'     => array(
            //'hostname' => 'mysql1201b.xserver.jp',
            'hostname' => '127.0.0.1',
            // 'database' => 'tnc001_mddb',
            // 'username'       => 'tnc001_mddb',
            // 'password'       => '7p3oYDysy',
            'database' => 'kakei',
            'username'       => 'root',
            'password'       => 'root',
            'persistent'     => false,
            'compress'       => false,
        ),
        'table_prefix' => '',
        'charset'      => 'utf8',
        'caching'      => false,
        'profiling'    => true,
    ),

);
