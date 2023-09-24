<?php

namespace backend\models;

use common\models\apple\Apple;
use common\models\apple\BaseApple;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * AppleSearch represents the model behind the search form of `common\models\apple\Apple`.
 */
class AppleSearch extends BaseApple
{

    public ?array $statusList = [];

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['status', 'eaten_percent'], 'integer'],
            [['color'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Apple::find()->where(['<','status',BaseApple::STATUS_EATEN]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'color' => $this->color,
        ]);

        $this->statusList = BaseApple::getStatuses();

        return $dataProvider;
    }
}
