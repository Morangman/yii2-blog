<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Groups;

/**
 * GroupsSearch represents the model behind the search form about `\app\models\Groups`.
 */
class GroupsSearch extends Groups
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'descr', 
              'created'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Groups::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id
        ]);
        for ($i = 0; $i < count($this->datetime_fields); $i++){
          $dt_f = $this->datetime_fields[$i];
          $val = Yii::$app->getRequest()->getQueryParam('Groupsearch')[$dt_f];
          if (is_array($val) && count($val) >= 2){
            $fv1 = $val[0];
            $fv2 = $val[1];
            if (!empty($fv1) && !empty($fv2)){
              $query->andFilterWhere(['between', $dt_f, 
                  date("Y-m-d H:i", strtotime(str_replace('.','-',$fv1))),
                  date("Y-m-d H:i", strtotime(str_replace('.','-',$fv2)) )
              ]);
            }
          }
          $query->andFilterWhere(['like', $dt_f, $this->$dt_f]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'descr', $this->descr]);

        return $dataProvider;
    }
}
