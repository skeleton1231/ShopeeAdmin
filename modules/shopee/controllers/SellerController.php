<?php

namespace app\modules\shopee\controllers;

use app\models\SellerAccounts;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class SellerController extends Controller{

    public function actionLogin($account){

        $seller = SellerAccounts::find()->where(['username' => $account])->one();

        $account = ArrayHelper::toArray($seller);

        Yii::$app->shopee->setParams($account);

        $response = Yii::$app->shopee->doLogin();

        print_r($response);
    }
}
