<?php

namespace app\modules\Backend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\Backend\models\Product;

/**
 * ProductSearch represents the model behind the search form about `app\modules\Backend\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */ 
    public function rules()
    {
        return [
            [['id', 'category_id', 'active'], 'integer'],
            [['name', 'description'], 'safe'],
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
        $query = Product::find()->with(['category'])->innerJoin('product_of_purchase pop', 'product.id = pop.product_id');

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        $query->addOrderBy(['name' => SORT_ASC]);
        $query->andFilterWhere(['product_of_purchase.article' => $this->name]);
        
        return $dataProvider;
    }


    public function searchIndex($params)
    {
        $query = Product::find()->with(['category']);

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
            'category_id' => $this->category_id,
            'active' => $this->active 
        ]);

        $query->andFilterWhere([
            'or',
            ['like', 'name', $this->name],
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}