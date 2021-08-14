<?php 

namespace Model;

class Db extends \Model
{
    //年収別、平均支出額を取得する
    public static function get_financeInfo($income=null, $household=null)
    {
        try{           
            \Log::debug('年収別、平均支出額を取得する');

            return $query = \DB::select('food', 'residence', 'utility', 'medical', 'communication', 'education', 'entertainment')->from('finance')->where(array(
                'income' => $income,
                'household' => $household,
                'delete_flg' => 0
            ))->execute()->as_array();
            
        }catch(Exception $e) {
            \Log::debug('ERROR!!!');
            \Log::debug($e);
            return false;
        }
    }

    //年収別、平均支出額を取得する
    public static function get_movieInfo($movieId=null)
    {
        try{
            \Log::debug('年収別、平均支出額を取得する');

            return $query = \DB::select('category', 'subcategory', 'comment')->from('movie')->where(array(
                'movie_id' => $movieId,
                'delete_flg' => 0
            ))->execute()->as_array();
            
        }catch(Exception $e) {
            \Log::debug('ERROR!!!');
            \Log::debug($e);
            return false;
        }
    }

    //
    public static function update_financeInfo($income, $household, $food, $residence, $utility, $medical, $communication, $education, $entertainment)
    {
        try{
            return $query = \DB::update('finance')->set(array(
                'food' => $food,
                'household' => $household,
                'residence' => $residence,
                'utility' => $utility,
                'medical' => $medical,
                'communication' => $communication,
                'education' => $education,
                'entertainment' => $entertainment,
            ))->where(array(
                'income' => $income,
                'delete_flg' => 0
            ))->execute();
        }catch(Exception $e){
            return null;
        }
    }

    //YouTube動画情報を更新する
    public static function update_movieInfo($movieId, $category, $subcategory, $comment)
    {
        try{
            return $query = \DB::update('movie')->set(array(
                'category' => $category,
                'subcategory' => $subcategory,
                'comment' => $comment
            ))->where(array(
                'movie_id' => $movieId,
                'delete_flg' => 0
            ))->execute();
        }catch(Exception $e){
            return null;
        }
    }

    //YouTube動画情報を論理削除する
    public static function delete_movieInfo($movieId)
    {
        try{
            return $query = \DB::update('movie')->set(array(
                'delete_flg' => 1
            ))->where(array(
                'movie_id' => $movieId
            ))->execute();
        }catch(Exception $e){
            return null;
        }
    }

    //年収別、平均支出額を論理削除する
    public static function delete_financeInfo($id)
    {
        try{
            return $query = \DB::update('finance')->set(array(
                'delete_flg' => 1
            ))->where(array(
                'id' => $id
            ))->execute();
        }catch(Exception $e){
            return null;
        }
    }

}