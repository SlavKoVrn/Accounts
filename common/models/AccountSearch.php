<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Account;

/**
 * AccountSearch represents the model behind the search form of `common\models\Account`.
 */
class AccountSearch extends Account
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'account_user_id', 'balance_summ', 'account_status', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['account_number', 'balance_date', 'account_description'], 'safe'],
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
        $query = Account::find()->owner()->notDeleted();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'account_user_id' => $this->account_user_id,
            'balance_date' => $this->balance_date,
            'balance_summ' => $this->balance_summ,
            'account_status' => $this->account_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'account_number', $this->account_number])
            ->andFilterWhere(['like', 'account_description', $this->account_description]);

        return $dataProvider;
    }
}
