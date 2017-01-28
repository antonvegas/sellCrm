<?php

namespace app\modules\Backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\Backend\models\Stock;

/**
 * StockSearch represents the model behind the search form about `app\modules\Backend\models\Stock`.
 */
class StockSearch extends Stock
{

    public $categoryId;
    public $productName;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_id', 'count'], 'integer'],
            [['article', 'categoryId', 'productName'], 'safe'],
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
        $query = Stock::find()->select([
            'stock.*',
            'SUM(stock.count) AS totalOfCount'
        ])->groupBy('stock.product_id');

        // add conditions that should always apply here
        $this->load($params);  

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        $dataProvider->setSort([
            'attributes' => [
                'productName' => [
                    'asc' => ['name' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'categoryId' => [
                    'asc' => ['category_id' => SORT_ASC],
                    'desc' => ['category_id' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'totalOfCount',
                'priceIn',
                'priceOut'

            ]
        ]);

        if (!$this->validate()) {

            $query->joinWith(['product']);
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'product_id' => $this->product_id
        ]);

        $query->andFilterWhere(['like', 'article', $this->article]);

        $query->joinWith(['product' => function ($q) {
            $q->where('product.name LIKE "%' . $this->productName . '%"');

            $q->andFilterWhere([
                'category_id' => $this->categoryId,
            ]);
        }]);

        return $dataProvider;
    }
}
