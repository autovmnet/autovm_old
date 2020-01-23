<?php

namespace app\models\searchs;


use app\models\User;
use yii\data\ActiveDataProvider;
use yii\base\Model;

class searchUser extends User
{        
    public $email;
    
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'email'], 'safe'],
        ];
    }
    
    public function scenarios()
    {
        return Model::scenarios();
    }
    
    public function search($params)
    {
        $query = User::find();
        
        $dataProvider = new ActiveDataProvider([
              'query' => $query,
              'pagination' => [
                'pageSize' => 10,
              ],
        ]);
        
        if (!($this->load($params)) && $this->validate()) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(['like', 'LOWER(first_name)', strtolower($this->first_name)])
        ->andFilterWhere(['like', 'LOWER(last_name)', strtolower($this->last_name)]);
        
        $email = $this->email;
        
        $query->joinWith(['email2' => function($query) use($email) {
            $query->andFilterWhere(['like', 'LOWER(user_email.email)', strtolower($email)]);
        }]);
                
        return $dataProvider;
    }
}

