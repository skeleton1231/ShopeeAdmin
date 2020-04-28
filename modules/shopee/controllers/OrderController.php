<?php

namespace app\modules\shopee\controllers;

use app\models\SellerAccounts;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;


/**
 * Default controller for the `shopee` module
 */
class OrderController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {

        $seller = SellerAccounts::find()->where(['username' => 'umusthave.my'])->one();

        $account = ArrayHelper::toArray($seller);

        Yii::$app->shopee->setParams($account);

        Yii::$app->shopee->doLogin();

    }

    public function actionAll(){

    }

    public function actionList($account='umusthave.my'){


        $seller = SellerAccounts::find()->where(['username' => $account])->one();

        $account = ArrayHelper::toArray($seller);

        Yii::$app->shopee->setParams($account);

        $res = Yii::$app->shopee->getOrderList();

        print_r($res);
        exit;

    }
}