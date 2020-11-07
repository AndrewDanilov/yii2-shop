<?php
namespace andrewdanilov\shop\backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use andrewdanilov\shop\common\models\Property;

class PropertySearch extends Property
{
	public $name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order'], 'integer'],
            [['type', 'name'], 'safe'],
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
        $query = Property::find()->joinWith(['tagRef'])->groupBy('id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
	        'sort' => [
		        'defaultOrder' => [
			        'order' => SORT_ASC,
		        ],
		        'attributes' => [
			        'id',
			        'name' => [
				        'asc' => ['name' => SORT_ASC],
				        'desc' => ['name' => SORT_DESC],
			        ],
			        'type',
			        'order',
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
            Property::tableName() . '.id' => $this->id,
	        Property::tableName() . '.order' => $this->order,
	        Property::tableName() . '.type' => $this->type,
        ]);

        $query->andFilterWhere(['like', Property::tableName() . '.name', $this->name]);

        return $dataProvider;
    }
}
