<?php 
class Model_Movie extends Orm\Model {
    protected static $_connection = 'pdo';
    protected static $_table_name = 'movie';
    protected static $_properties = array(
        'id',
        'movie_id' => array(
            'data_type'  => 'varchar',
            'label'      => '動画ID',
        ),
        'category' => array(
            'data_type'  => 'varchar',
            'label'      => 'カテゴリ',
        ),
        'subcategory' => array(
            'data_type'  => 'varchar',
            'label'      => 'サブカテゴリ',
        ),
        'comment' => array(
            'data_type'  => 'varchar',
            'label'      => 'コメント',
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