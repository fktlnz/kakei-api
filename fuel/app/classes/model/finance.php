<?php 
class Model_Finance extends Orm\Model {
    protected static $_connection = 'pdo';
    protected static $_table_name = 'finance';
    protected static $_properties = array(
        'id',
        'income' => array(
            'data_type'  => 'varchar',
            'label'      => '年収',
        ),
        'food' => array(
            'data_type'  => 'varchar',
            'label'      => '食料',
        ),
        'residence' => array(
            'data_type'  => 'varchar',
            'label'      => '住居',
        ),
        'utility' => array(
            'data_type'  => 'varchar',
            'label'      => '光熱・水道',
        ),
        'medical' => array(
            'data_type'  => 'varchar',
            'label'      => '保険医療',
        ),
        'communication' => array(
            'data_type'  => 'varchar',
            'label'      => '交通・通信',
        ),
        'education' => array(
            'data_type'  => 'varchar',
            'label'      => '教育',
        ),
        'entertainment' => array(
            'data_type'  => 'varchar',
            'label'      => '娯楽',
        ),
        'delete_flg' => array(
            'data_type' => 'tinyint', 
            'label'     => 'delete flag',
        ),
        'created_at' => array(
            'data_type' => 'datetime',
            'label'     => '作成日',
        )
        
    );
}