<?php

namespace app\modules\Backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\Backend\models\Sale;

/**
 * SaleSearch represents the model behind the search form about `app\modules\Backend\models\Product`.
 */
class SaleSearch extends Sale
{
    public $product_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','seller_id', 'discount', 'price', 'product_id', 'created_at'], 'integer'],
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
        $query = Sale::find()->with(['productOfSales'])->joinWith(['productOfSales']);

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
            'created_at' => $this->created_at,
            'seller_id' => $this->seller_id,
            'discount' => $this->discount,
            'price' => $this->price
        ]);
        $query->addOrderBy(['created_at' => SORT_DESC]);


        /*$query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'description', $this->description]);
*/
        return $dataProvider;
    }
}
