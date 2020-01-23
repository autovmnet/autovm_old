<?php

namespace app\models\searchs;


use yii\data\ActiveDataProvider;
use yii\base\Model;
use app\models\Vps;

class searchVps extends Vps
{
    public $ip;
    public $email;
    
    public function rules()
    {
        return [
            [['ip', 'email'], 'safe'],
        ];
    }
    
    public function scenarios()
    {
        return Model::scenarios();
    }
    
    public function search($params)
    {
        $query = Vps::find();
        
        $query->joinWith(['ip', 'email']);
        
        $dataProvider = new ActiveDataProvider([
              'query' => $query,
              'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
              'pagination' => [
                'pageSize' => 10,
              ],
        ]);

        //$dataProvider->setSort(['attributes' => ['id', 'server_id']]);
        
        if (!($this->load($params)) && $this->validate()) {
            return $dataProvider;
        }
        
        $query->andFilterWhere(['like', 'ip.ip', trim($this->ip)])
        ->andFilterWhere(['like', 'user_email.email', trim($this->email)]);
        
        return $dataProvider;
    }
}