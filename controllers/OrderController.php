<?php

namespace app\controllers;

use app\models\SellerAccounts;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Yii;
use yii\helpers\ArrayHelper;
use Guzzle\Http\Exception\ClientErrorResponseException;



class OrderController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionToship($type = 'NULL')
    {


        $sellers = SellerAccounts::find()->where(['type' => $type])->all();

        $accounts = ArrayHelper::toArray($sellers);

        set_time_limit(0);
//        $accounts =Yii::$app->redis->get('accounts');
//        $accounts = json_decode($accounts, true);
        $datas = [];

        foreach ($accounts as $account) {

//            $name = 'puguma.th';
//            $site = 'th';
            $name = $account['username'];
            $site = $account['site'];

            $auth = Yii::$app->redis->get($name);
            $auth = json_decode($auth, true);

            $erroraccounts = [];

            if ($auth['COOKIE']) {

                $client = new \GuzzleHttp\Client(['headers' => ['Cookie' => $auth['COOKIE']]]);

                //print_r($auth);

                $uri = 'https://seller.' . $site . '.shopee.cn/api/v3/order/get_order_list/?SPC_CDS=' . $auth["SPC_CDS"] . '&SPC_CDS_VER=2&limit=40&offset=0&order_by_payment_date=desc&is_massship=false&list_type=toship&shipping_center_status=pickup_pending';
                //echo $uri;
                //exit;
                try{
                    $response = $client->request('GET', $uri,['http_errors' => false]);

                    if($response->getStatusCode() != 200){

                        continue;
                    }

                    $response = json_decode($response->getBody(), true);

                    if ($response['data']['orders']) {

                        $orders = $response['data']['orders'];

                        foreach ($orders as $order) {

                            if ($order['shipping_traceno'] == '') {

                                $items = $order['order_items'];

                                foreach ($items as $item) {

                                    if(!$item['bundle_deal_product']){

                                        $data = [];
                                        $data['sn'] = $order['order_sn'];
                                        $product = $item['product'];
                                        $data['image'] = $product['images'][0];
                                        //download image
                                        $img = \Yii::$app->basePath . "/web/images/{$product['images'][0]}.jpeg";
                                        if (!file_exists($img)) {
                                            $stream = @file_get_contents('https://s-cf-' . $site . '.shopeesz.com/file/' . $product['images'][0]);
                                            if($stream){
                                                file_put_contents($img, $stream);

                                            }
                                        }

                                        $data['model'] = $item['item_model']['name'];
                                        $data['price'] = $product['price'];
                                        $data['currency'] = $product['currency'];
                                        $data['amount'] = $item['amount'];
                                        $data['name'] = $product['name'];
                                        $data['shop'] = $name;
                                        $data['url'] = "https://shopee.co.{$site}/product/{$order['shop_id']}/{$product['item_id']}/";
                                        $datas[] = $data;

                                    }
                                    else{

                                        foreach ($item['bundle_deal_product'] as $k => $bundle){

                                            $data = [];
                                            $data['sn'] = $order['order_sn'];
                                            $data['image'] = $bundle['images'][0];
                                            //download image
                                            $img = \Yii::$app->basePath . "/web/images/{$bundle['images'][0]}.jpeg";
                                            $imgurl = 'https://s-cf-' . $site . '.shopeesz.com/file/' . $bundle['images'][0];

                                            if (!file_exists($img)) {
                                                $stream = @file_get_contents($imgurl);
                                                if($stream){
                                                    file_put_contents($img, $stream);
                                                }

                                            }

                                            $data['model'] = $item['bundle_deal_model'][$k]['name'];
                                            $data['price'] = $bundle['price'];
                                            $data['currency'] = $bundle['currency'];
                                            $data['amount'] = $item['item_list'][$k]['amount'];
                                            $data['name'] = $bundle['name'];
                                            $data['shop'] = $name;
                                            $data['url'] = "https://shopee.co.{$site}/product/{$order['shop_id']}/{$bundle['item_id']}/";
                                            $datas[] = $data;

                                        }
                                    }


                                }

                            }

                        }
                    }
                }
                catch(ClientErrorResponseException  $e){

                    throw new BadRequestHttpException($e->getMessage());
                }
                //$response = @$client->request('GET', $uri);

                //echo $response->getStatusCode();


            } else {
                $erroraccounts[] = $account;
            }
        }


        $res['data'] = $datas;
        $res['error'] = $erroraccounts;

        $res = json_encode($res);
        $key = 'orders:' . $type . ':' . date("Ymd");
        Yii::$app->redis->set($key, $res);

        echo $res;
    }

    public function actionExcel()
    {

        $cates = ['Toys', 'Luxury', 'Shoes', 'Sex', 'Electronic'];

        foreach ($cates as $type) {

            $key = 'orders:' . $type . ':' . date("Ymd");

            $name = 'orders-' . $type . '-' . date("Ymd");

            $filename = \Yii::$app->basePath . "/web/xlsx/$name.xlsx";

            @unlink($filename);

            $res = json_decode(Yii::$app->redis->get($key), true);

            $orders = $res['data'];

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            $columns = ['订单号', '图片', '规格', '价钱', '货币', '数量', '名称', '店铺','图片url'];

            $sheet = $spreadsheet->getActiveSheet();

            $sheet
                ->fromArray(
                    $columns,  // The data to set
                    NULL,        // Array values with this value will not be set
                    'A1'         // Top left coordinate of the worksheet range where
                //    we want to set these values (default is A1)
                );



            $sheet->getColumnDimension('B')->setWidth(20);

            $sheet->
            fromArray(
                $orders,  // The data to set
                NULL,        // Array values with this value will not be set
                'A2'         // Top left coordinate of the worksheet range where
            //    we want to set these values (default is A1)
            );

            foreach ($orders as $k => $order) {

                $cn = $k + 2;

                $pos = 'B' . $cn;

                $img = \Yii::$app->basePath . "/web/images/{$order['image']}.jpeg";

                if(file_exists($img)){

                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

                    $drawing->setPath($img);

                    $drawing->setCoordinates($pos);

                    $drawing->setResizeProportional(true);

                    $drawing->setWidth(20);

                    $sheet->getRowDimension($cn)->setRowHeight(100);

                    $drawing->setHeight(100);

                    $drawing->setWorksheet($sheet);
                }
            }

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

            @$writer->save($filename);
        }


        //        // redirect output to client browser
//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Disposition: attachment;filename="'.time().'.xlsx"');
//        header('Cache-Control: max-age=1');
//
//        $writer->save('php://output');

    }

}
