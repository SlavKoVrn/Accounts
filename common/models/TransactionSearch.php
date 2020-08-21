<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Transaction;

/**
 * TransactionSearch represents the model behind the search form of `common\models\Transaction`.
 */
class TransactionSearch extends Transaction
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'transaction_user_id', 'debet_account_id', 'credit_account_id', 'transaction_summ', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['transaction_number', 'transaction_date', 'transaction_description'], 'safe'],
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
    public function search($params,$account_id)
    {
        $query = Transaction::find()->account($account_id);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>array(
                'defaultOrder'=>['transaction_date' => SORT_DESC],
            ),
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
            'transaction_date' => $this->transaction_date,
            'transaction_user_id' => $this->transaction_user_id,
            'debet_account_id' => $this->debet_account_id,
            'credit_account_id' => $this->credit_account_id,
            'transaction_summ' => $this->transaction_summ,
            'transaction_created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'transaction_number', $this->transaction_number])
            ->andFilterWhere(['like', 'transaction_description', $this->transaction_description]);

        return $dataProvider;
    }

}
