<?php

namespace app\modules\shopee\controllers;


use Yii;
use app\models\SellerAccounts;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

Class ImageController extends Controller {


    public function actionTest(){

        $file = Yii::$app->basePath . '/data/image/test_id/06eb6feab9f52f88bdce5fe02ed7814a.jpg';

        $seller = SellerAccounts::find()->where(['username' => 'nche9242.id'])->one();
        $seller = ArrayHelper::toArray($seller);

        Yii::$app->shopee->setParams($seller);

       // $contents = file_get_contents($file);
       // $base64   = 'data:image/jpeg;base64,'. base64_encode($contents);

        Yii::$app->shopee->uploadImage($file);

    }
}
