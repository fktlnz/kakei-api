<?php 


use \Model\Db; //Dbモデルをインポート
use \Model\Finance; //Dbモデルをインポート

class Controller_Api extends Controller_Rest
{
    protected $format = 'json';

//========================フロントから下記apiにリクエストが来る=========================//


    // 年収別の平均支出額を登録する
    public function post_financeinfo()
    {
        $isNew = Input::post('isNew');//新規登録かどうか
        $income = Input::post('income');
        $food = Input::post('food');
        $residence = Input::post('residence');
        $utility = Input::post('utility');
        $medical = Input::post('medical');
        $communication = Input::post('communication');
        $education = Input::post('education');
        $entertainment = Input::post('entertainment');

        if(!empty($income) && !empty($food) && !empty($residence) && !empty($utility) && !empty($medical) && !empty($communication) && !empty($education) && !empty($entertainment)){

            try{

                if($isNew == "true") {
                    //新規登録
                    $data = array();
                    $data['income'] = $income;
                    $data['food'] = $food;
                    $data['residence'] = $residence;
                    $data['utility'] = $utility;
                    $data['medical'] = $medical;
                    $data['communication'] = $communication;
                    $data['education'] = $education;
                    $data['entertainment'] = $entertainment;
                    $data['delete_flg'] = 0;
                    $data['created_at'] = date('Y:m:d h:i:s');
                    $post = Model_Finance::forge();
                    $post->set($data);
                    $rst = $post->save();
                    
                }else{
                    //編集
                    $rst = Db::update_financeInfo($income, $food, $residence, $utility, $medical, $communication, $education, $entertainment);
                }

                if($rst){
                    $add = ($isNew=="true") ? "登録" : "更新";
                    return $this->response(array(
                            'res' => "OK",
                            'message' => '年収' . $income . '万円の平均支出額を'. $add . 'しました',
                            'rst' => $rst
                        ));
                }else{
                    return $this->response(array(
                            'res' => "NG",
                            'message' => '登録失敗',
                            'rst' => $rst
                        ));    
                }

            }catch(Exception $e) {
                return $this->response(array(
                        'res' => "NG",
                        'message' => $e->getMessage(),
                        'rst' => null
                    ));
            }
            
        }else{
            return $this->response(array(
                        'res' => "NG",
                        'message' => '未入力の項目があります',
                        'rst' => null
                    ));
        }
    }

    /**
     * 年収別の平均支出額を取得する
     * 
     * @param none
     * @return　true / false
    **/
    public function get_financeinfo()
    {
        Log::debug('年収別の平均支出額を取得する');
        //ユーザーIDを取得する
        $income = Input::get('income');
        Log::debug('$income:'.print_r($income,true));
        //店舗情報を取得
        $financeInfo = Db::get_financeInfo($income);
        
        //$financeInfo = Model_Finance::find('all');

        Log::debug('$financeInfo:'.print_r($financeInfo,true));
        if($financeInfo != false){
            $financeInfo_ = array(
                "income" => $income,
                "food" => $financeInfo[0]["food"],
                "residence" => $financeInfo[0]["residence"],
                "utility" => $financeInfo[0]["utility"],
                "medical" => $financeInfo[0]["medical"],
                "communication" => $financeInfo[0]["communication"],
                "education" => $financeInfo[0]["education"],
                "entertainment" => $financeInfo[0]["entertainment"],
            );
            return $this->response(array(
                'res' => "OK",
                'msg' => '取得に成功しました',
                'rst' => $financeInfo_
            ));

        }else{
            return $this->response(array(
                'res' => "NG",
                'msg' => '取得失敗',
                'rst' => $financeInfo
            ));
        }
    }

    // YouTube動画情報を登録する
    public function post_movieinfo()
    {
        $isNew = Input::post('isNew');//新規登録かどうか
        $movieId = Input::post('id');
        $category = Input::post('category');
        $subcategory = Input::post('subcategory');
        $comment = Input::post('comment');

        if(!empty($movieId) && !empty($category) && !empty($subcategory) && !empty($comment)){

            try{

                if($isNew == "true") {
                    //新規登録
                    $data = array();
                    $data['movie_id'] = $movieId;
                    $data['category'] = $category;
                    $data['subcategory'] = $subcategory;
                    $data['comment'] = $comment;
                    $data['delete_flg'] = 0;
                    $data['created_at'] = date('Y:m:d h:i:s');
                    $post = Model_Movie::forge();
                    $post->set($data);
                    $rst = $post->save();

                }else{
                    //編集
                    $rst = Db::update_movieInfo($movieId, $category, $subcategory, $comment);
                }

                if($rst){
                    $add = ($isNew=="true") ? "登録" : "更新";
                    return $this->response(array(
                            'res' => "OK",
                            'message' => $add . 'しました',
                            'rst' => $rst
                        ));
                }else{
                    return $this->response(array(
                            'res' => "NG",
                            'message' => '登録失敗',
                            'rst' => $rst
                        ));    
                }

            }catch(Exception $e) {
                return $this->response(array(
                        'res' => "NG",
                        'message' => $e->getMessage(),
                        'rst' => null
                    ));
            }
            
        }else{
            return $this->response(array(
                        'res' => "NG",
                        'message' => '未入力の項目があります',
                        'rst' => null
                    ));
        }
    }

    /**
     * YouTube動画情報をを取得する
     * 
     * @param none
     * @return　true / false
    **/
    public function get_movieinfo()
    {
        Log::debug('YouTube動画情報をを取得する');
        //ユーザーIDを取得する
        $movieId = Input::get('movieId');
        Log::debug('$movieId:'.print_r($movieId,true));

        $rst_array = array();

        if(empty($movieId)){
            $movieInfo_all = Model_Movie::find('all');
            foreach($movieInfo_all as $val){
                Log::debug('$val:'.print_r($val,true));
                array_push($rst_array, $val);
            }
        }else{
            //店舗情報を取得
            $movieInfo = Db::get_movieInfo($movieId);
            Log::debug('$movieInfo:'.print_r($movieInfo,true));
            if($movieInfo != false){

                $movieInfo_ = array(
                    "id" => $movieId,
                    "category" => $movieInfo[0]["category"],
                    "subcategory" => $movieInfo[0]["subcategory"],
                    "comment" => $movieInfo[0]["comment"]
                );
                array_push($rst_array, $movieInfo_);

            }else{
                return $this->response(array(
                    'res' => "NG",
                    'msg' => '取得失敗',
                    'rst' => $movieInfo
                ));
            }
        }

        return $this->response(array(
            'res' => "OK",
            'msg' => '取得に成功しました',
            'rst' => $rst_array,
            'ids' => array_column($movieInfo_all, 'movie_id')
        ));

    }

}