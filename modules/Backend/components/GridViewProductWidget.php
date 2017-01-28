<?php

namespace app\modules\Backend\components;

use yii\base\Widget;
use app\modules\Backend\models\Product;
use yii\data\ActiveDataProvider;
use app\modules\Backend\models\search\ProductSearch;

class GridViewProductWidget extends Widget
{
    public $searchFilter;
    public $dateProvider;

    public function init()
    {
        parent::init();
        $this->searchFilter = new ProductSearch();
        $this->dateProvider = new ActiveDataProvider(['query' => Product::find()]);
    }

    public function run()
    { 
        return $this->render('/product/grid', [
            'searchModel' =>  $this->searchFilter,
            'dataProvider' =>  $this->dateProvider,
        ]);

    }
}