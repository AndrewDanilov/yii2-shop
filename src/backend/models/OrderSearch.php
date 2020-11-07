<?php
namespace andrewdanilov\shop\backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use andrewdanilov\shop\common\models\Order;

/**
 * @method getISODate($attribute)
 * @method getDisplayDate($attribute)
 */
class OrderSearch extends Order
{
	public $addressee;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pay_id', 'delivery_id'], 'integer'],
            [['created_at', 'addressee', 'status'], 'safe'],
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
        $query = Order::find()->groupBy('id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	        'sort' => [
		        'defaultOrder' => [
			        'id' => SORT_DESC,
		        ],
		        'attributes' => [
			        'id',
			        'created_at',
			        'addressee' => [
				        'asc' => ['addressee_name' => SORT_ASC],
				        'desc' => ['addressee_name' => SORT_DESC],
			        ],
			        'pay_id',
			        'delivery_id',
			        'status',
		        ],
	        ],
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
            'pay_id' => $this->pay_id,
            'delivery_id' => $this->delivery_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['between', 'created_at', $this->getISODate('created_at'), $this->getISODate('created_at') . ' 23:59:59']);

        $query->andFilterWhere(['or',
	        ['like', 'addressee_name', $this->addressee],
	        ['like', 'addressee_email', $this->addressee],
	        ['like', 'addressee_phone', $this->addressee],
        ]);

        return $dataProvider;
    }
}
