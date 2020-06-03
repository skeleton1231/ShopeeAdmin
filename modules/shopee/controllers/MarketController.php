<?php

namespace app\modules\shopee\controllers;

use Yii;
use app\models\SellerAccounts;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Default controller for the `shopee` module
 */
class MarketController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionList(){

        $seller = SellerAccounts::find()->where(['username' => 'nche9242.id'])->one();

        $account = ArrayHelper::toArray($seller);

        Yii::$app->shopee->setParams($account);

        $sku = 'A2017110114531608903/I202004051033389960275610';

        Yii::$app->shopee->keyword = $sku;

        $rs = Yii::$app->shopee->getPromotionIdBySku();

        if($rs['message'] == 'success'){
            $discount_id=$rs['data']['hits'][0]['promotionid'];

            $rs = Yii::$app->shopee->getItemBySkuInPromo($discount_id);
            //print_r($rs);

            if($rs['message'] == 'success'){

                $itemid = $rs['data']['hits'][0]['itemid'];
            }

            Yii::$app->shopee->deletePromoBySku($discount_id,$itemid);
        }



    }
}
