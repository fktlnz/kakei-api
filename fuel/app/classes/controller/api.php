<?php 


use \Model\Db; //Dbモデルをインポート
use \Model\Finance; //Dbモデルをインポート

class Controller_Api extends Controller_Rest
{
    protected $format = 'json';

//========================フロントから下記apiにリクエストが来る=========================//

    /**
     * ユーザー登録処理をする
     * 
     * @param none
     * @return array('res' => 'OK or NG' , 'error' => array(NGの場合にエラーメッセージを格納します))
    **/
    public function post_signup()
    {
        $error = array();
        //バリデーションの結果を保持する
        $json = array(
            'res' => 'NG',
            'message' => '登録に成功しました',
        );
        $model_signupform = Model_Signupform::forge();
        $signupform = Fieldset::forge('signupform');
        //$signupform->add_model($model_signupform)->populate($model_signupform);
        if(Input::method() =='POST'){
            $validate = $signupform->validation();
            if($validate->run()){                
                //パスワードの一致を確認する
                if(Input::post('re_pass') !== Input::post('password')) {
                    $json['message'] = '『パスワード再入力』は『パスワード』と一致していません。';
                    return $this->response($json);
                }

                Log::debug('バリデーションに成功. DBにユーザー情報を格納します');
                $auth = Auth::instance(); //Authインスタンス生成
                try {
                    if($auth->create_user(Input::post('username'), Input::post('password'), Input::post('email'))){                        
                        $json['res']='OK';
                        return $this->response($json);
                    }else{
                        
                    }
                } catch(Exception $e) {
                    $json['message']='このユーザーは登録できません('.$e->getMessage().')';
                    return $this->response($json);
                }
                

                
            }else {
                $errors = $validate->error();
                foreach( $errors as $field => $error )
                {
                    $json['error'][$field] = $error->get_message();
                }
                Log::debug('バリデーションに失敗しました:'.print_r($json, true));

                return $this->response($json);
            }
            
        }
    }

    /**
     * ユーザーを取得する
     * 
     * @param none
     * @return　true / false
    **/
    public function get_users()
    {
        Log::debug('ユーザー一覧を取得する');

        $rst_array = array();
        
        $users_all = Model_Signupform::find('all', array(
            'where' => array(
                array('group', 1) 
            )
        ));
        
        Log::debug('users_all:'.print_r($users_all, true));

        foreach($users_all as $val){
            array_push($rst_array, $val);
        }
        

        return $this->response(array(
            'res' => "OK",
            'msg' => '取得に成功しました',
            'rst' => $rst_array
        ));

    }

    /**
     * ユーザーを認証する
     * 
     * @param none
     * @return　true / false
    **/
    public function post_login()
    {
        $username = Input::json('username');
        $password = Input::json('password');

        $token = array(
            "token" => "admin-token"
        );

        //バリデーションの結果を保持する
        $json = array(
            'code' => 20000,
            'data' => $token,
            'message' => '認証成功'
        );

        $signinform = Fieldset::forge('signinform');

        if(Input::method() =='POST'){
            $validate = $signinform->validation();
            if($validate->run()){
                Log::debug('バリデーションに成功. ログイン処理開始');
                $auth = Auth::instance(); //Authインスタンス生成
                try {
                    if($auth->login($username, $password)){

                        Log::debug('ログイン処理成功した。ユーザーID:'.Auth::get('id'));
                        return $this->response($json);

                    }else{
                        Log::debug('ログイン処理失敗した。');
                        $json['code'] = 40000;
                        $json['message'] = 'ユーザー名またはパスワードが間違っています。';
                        return $this->response($json);
                    }
                } catch(Exception $e) {
                    $json['code'] = 40000;
                    $json['message'] = 'ログインできませんでした';
                    return $this->response($json);
                }

            }else {
                $errors = $validate->error();
                foreach( $errors as $field => $error )
                {
                    $json['error'][$field] = $error->get_message();
                }
                Log::debug('バリデーションに失敗しました:'.print_r($json, true));

                return $this->response($json);
            }
            
        }
    }

    // 年収別の平均支出額を登録する
    public function post_financeinfo()
    {
        $isNew = Input::post('isNew');//新規登録かどうか
        $income = Input::post('income');
        $household = Input::post('household');
        $food = Input::post('food');
        $residence = Input::post('residence');
        $utility = Input::post('utility');
        $medical = Input::post('medical');
        $communication = Input::post('communication');
        $education = Input::post('education');
        $entertainment = Input::post('entertainment');

        Log::debug('$household:'.print_r($household,true));

        if(!empty($income) && isset($household) && !empty($food) && !empty($residence) && !empty($utility) && !empty($medical) && !empty($communication) && !empty($education) && !empty($entertainment)){

            try{

                if($isNew == "true") {
                    //新規登録
                    $data = array();
                    $data['income'] = $income;
                    $data['household'] = $household;
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
                    $rst = Db::update_financeInfo($income, $household, $food, $residence, $utility, $medical, $communication, $education, $entertainment);
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
        $household = Input::get('household');
        Log::debug('$income:'.print_r($income,true));
        Log::debug('$household:'.print_r($household,true));

        $rst_array = array();

        if(empty($income) && empty($household)){
            $financeInfo_all = Model_Finance::find('all', array(
                'where' => array(
                    array('delete_flg', 0) 
                )
            ));
            foreach($financeInfo_all as $val){
                Log::debug('$val:'.print_r($val,true));
                array_push($rst_array, $val);
            }
        }else{

            //店舗情報を取得
            $financeInfo = Db::get_financeInfo($income, $household);
            
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
                array_push($rst_array, $financeInfo_);
            }else{
                return $this->response(array(
                    'res' => "NG",
                    'msg' => '取得失敗',
                    'rst' => $financeInfo
                ));
            }
        }

        return $this->response(array(
            'res' => "OK",
            'msg' => '取得に成功しました',
            'rst' => $rst_array
        ));

    }

    // 年収別の平均支出額を論理削除する
    public function get_deletefinanceinfo()
    {
        $id = Input::get('id');

        if(!empty($id)){

            try{

                $rst = Db::delete_financeInfo($id);
                if($rst){
                    return $this->response(array(
                            'res' => "OK",
                            'message' => '削除しました',
                            'rst' => $rst
                        ));
                }else{
                    return $this->response(array(
                            'res' => "NG",
                            'message' => '削除失敗',
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
                        'message' => 'get error',
                        'rst' => null
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
        $enableType = Input::get('enableType');
        $enableTypeArr = explode(',', $enableType);
        Log::debug('$enableTypeArr:'.print_r($enableTypeArr,true));

        $rst_array = array();

        if(empty($movieId)){
            $orQuery = array();
            $arrTemp = array();
            foreach($enableTypeArr as $key => $val) {
                if((int)$key % 2 == 0){
                    if(empty($arrTemp)){
                        $arrTemp = array('category', $val);
                    }else{
                        $arrTemp = array(
                            array('category', $val),
                            'or' => $arrTemp
                        );
                    }
                }else{
                    $arrTemp = array(
                        array('category', $val),
                        'or' => array(
                            $arrTemp
                        )
                    );
                }
            }
            $movieInfo_all = Model_Movie::find('all', array(
                'where' => array(
                    array('delete_flg', 0),   
                    $arrTemp               
                )
            ));
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


    // YouTube動画情報を論理削除する
    public function get_deletemovieinfo()
    {
        $movieId = Input::get('movieId');

        if(!empty($movieId)){

            try{

                $rst = Db::delete_movieInfo($movieId);
                if($rst){
                    return $this->response(array(
                            'res' => "OK",
                            'message' => '削除しました',
                            'rst' => $rst
                        ));
                }else{
                    return $this->response(array(
                            'res' => "NG",
                            'message' => '削除失敗',
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
                        'message' => 'get error',
                        'rst' => null
                    ));
        }
    }

}