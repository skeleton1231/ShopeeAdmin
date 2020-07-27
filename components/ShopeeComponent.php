<?php

namespace app\components;

use Yii;
use yii\base\Component;
use GuzzleHttp\Client;
use yii\db\Exception;


class ShopeeComponent extends Component
{

    public $seller;
    public $domain;
    public $URI;
    public $URL;
    public $httpClient;

    //querystring
    public $queryStr;
    public $COOKIE;
    public $SPC_CDS;
    public $SPC_CDS_VER = 2;
    public $limit = 40;
    public $offset = 0;
    public $total = 0;
    public $flip_direction = 'ahead';
    public $page_sentinel = '0,0';
    public $order_by_create_date = 'desc';
    public $is_massship = false;
    public $keyword;
    public $discount_id;

    public $sites = [
        'my', 'ph', 'sg', 'th', 'vn', 'id'
    ];


    public $desc = 'Welcome to We are a professional overseas purchasing shop, mainly engaged in: Versace, LV, GUCCI, Valentino, Philipp Plein, Fendi, Hermes and others.

All the pictures in this shop are taken in kind by ourselves. The goods you receive will be exactly the same as those in the pictures. 

All products will be shipped from oversea, so please kindly understand the shipping time. please believe that we make every effort to your delivery, our goods delivery over the mountains and rivers to your hands, please give us a five-star rating , thank you very much.

【Note】:
*Shoes packed with standard box and come with receipt!
*If you have any problems, please contact us in time.
*If you are satisfied with your shoes, please give us five-star praise.';


//    public $product_clomuns = [
//
//        'model'=> [
//            //sku,name,price,stock
//            ['J','K','L','M'],
//            ['N','O','P','Q'],
//            ['R','S','T','U'],
//            ['V','W','X','Y'],
//            ['Z','AA','AB','AC'],
//            ['AD','AE','AF','AG'],
//            ['AH','AI','AJ','AK'],
//            ['AL','AM','AN','AO'],
//            ['AP','AQ','AR','AS'],
//            ['AT','AU','AV','AW'],
//            ['AX','AY','AZ','BA'],
//            ['BB','BC','BD','BE'],
//            ['BF','BG','BH','BI'],
//            ['BJ','BK','BL','BM'],
//            ['BN','BO','BP','BQ'],
//            ['BR','BS','BT','BU'],
//            ['BV','BW','BX','BY'],
//            ['BZ','CA','CB','CC'],
//            ['CD','CE','CF','CG'],
//            ['CH','CI','CJ','CK'],
//        ],
//
//        'image'=> ['CL','CM','CN','CO','CP','CQ','CR','CS','CT'],
//        'name' => 'B',
//        'price'=>'D',
//        'stock'=>'E',
//        'desc'=>'C',
//        'category'=>'A',
//        'weight'=>'F',
//        'days'=>'G',
//        'parent_sku'=>'H'
//    ];
//    public $product_columns = [
//        'ps_category_list_id',
//        'ps_product_name',
//        'ps_product_description',
//        'ps_price',
//        'ps_stock',
//        'ps_product_weight',
//        'ps_days_to_ship',
//        'ps_sku_ref_no_parent',
//        'ps_mass_upload_variation_help',
//        'ps_variation %d ps_variation_sku',
//        'ps_variation 1 ps_variation_name',
//        'ps_variation 1 ps_variation_price',
//        'ps_variation 1 ps_variation_stock',
//        'ps_variation 2 ps_variation_sku',
//        'ps_variation 2 ps_variation_name',
//        'ps_variation 2 ps_variation_price',
//        'ps_variation 2 ps_variation_stock',
//        'ps_variation 3 ps_variation_sku',
//        'ps_variation 3 ps_variation_name',
//        'ps_variation 3 ps_variation_price',
//        'ps_variation 3 ps_variation_stock	ps_variation 4 ps_variation_sku	ps_variation 4 ps_variation_name	ps_variation 4 ps_variation_price	ps_variation 4 ps_variation_stock	ps_variation 5 ps_variation_sku	ps_variation 5 ps_variation_name	ps_variation 5 ps_variation_price	ps_variation 5 ps_variation_stock	ps_variation 6 ps_variation_sku	ps_variation 6 ps_variation_name	ps_variation 6 ps_variation_price	ps_variation 6 ps_variation_stock	ps_variation 7 ps_variation_sku	ps_variation 7 ps_variation_name	ps_variation 7 ps_variation_price	ps_variation 7 ps_variation_stock	ps_variation 8 ps_variation_sku	ps_variation 8 ps_variation_name	ps_variation 8 ps_variation_price	ps_variation 8 ps_variation_stock	ps_variation 9 ps_variation_sku	ps_variation 9 ps_variation_name	ps_variation 9 ps_variation_price	ps_variation 9 ps_variation_stock	ps_variation 10 ps_variation_sku	ps_variation 10 ps_variation_name	ps_variation 10 ps_variation_price	ps_variation 10 ps_variation_stock	ps_variation 11 ps_variation_sku	ps_variation 11 ps_variation_name	ps_variation 11 ps_variation_price	ps_variation 11 ps_variation_stock	ps_variation 12 ps_variation_sku	ps_variation 12 ps_variation_name	ps_variation 12 ps_variation_price	ps_variation 12 ps_variation_stock	ps_variation 13 ps_variation_sku	ps_variation 13 ps_variation_name	ps_variation 13 ps_variation_price	ps_variation 13 ps_variation_stock	ps_variation 14 ps_variation_sku	ps_variation 14 ps_variation_name	ps_variation 14 ps_variation_price	ps_variation 14 ps_variation_stock	ps_variation 15 ps_variation_sku	ps_variation 15 ps_variation_name	ps_variation 15 ps_variation_price	ps_variation 15 ps_variation_stock	ps_variation 16 ps_variation_sku	ps_variation 16 ps_variation_name	ps_variation 16 ps_variation_price	ps_variation 16 ps_variation_stock	ps_variation 17 ps_variation_sku	ps_variation 17 ps_variation_name	ps_variation 17 ps_variation_price	ps_variation 17 ps_variation_stock	ps_variation 18 ps_variation_sku	ps_variation 18 ps_variation_name	ps_variation 18 ps_variation_price	ps_variation 18 ps_variation_stock	ps_variation 19 ps_variation_sku	ps_variation 19 ps_variation_name	ps_variation 19 ps_variation_price	ps_variation 19 ps_variation_stock	ps_variation 20 ps_variation_sku	ps_variation 20 ps_variation_name	ps_variation 20 ps_variation_price	ps_variation 20 ps_variation_stock	ps_img_1	ps_img_2	ps_img_3	ps_img_4	ps_img_5	ps_img_6	ps_img_7	ps_img_8	ps_img_9	ps_mass_upload_shipment_help	channel 48002 switch
//
//    ];
    public $category_id = [

        //男装上身
        'Jackets' => ['my' => 6788, 'ph' => 18758, 'sg' => 11709, 'th' => 8807, 'vn' => 11412],
        'Sweater' => ['my' => 6791, 'ph' => 21417, 'sg' => 20561, 'th' => 8806, 'vn' => 8944],
        'Down Jackets' => ['my' => 6788, 'ph' => 18766, 'sg' => 11710, 'th' => 8807, 'vn' => 8953],
        'Vest' => ['my' => 21176, 'ph' => 18757, 'sg' => 19437, 'th' => 8807, 'vn' => 8951],
        'Shirts' => ['my' => 6778, 'ph' => 15483, 'sg' => 19423, 'th' => 8794, 'vn' => 11327],
        'T-Shirts' => ['my' => 11681, 'ph' => 15484, 'sg' => 11681, 'th' => 9874, 'vn' => 8947],
        'Polo Shirts' => ['my' => 11822, 'ph' => 15485, 'sg' => 6454, 'th' => 10020, 'vn' => 11327],
        'Short Sleeve Shirts' => ['my' => 6778, 'ph' => 15484, 'sg' => 6460, 'th' => 9873, 'vn' => 11324],
        'Long Sleeve Shirts' => ['my' => 11681, 'ph' => 15483, 'sg' => 11682, 'th' => 8794, 'vn' => 11325],
        'Hoodies' => ['my' => 6789, 'ph' => 21418, 'sg' => 19636, 'th' => 8808, 'vn' => 8945],
        'Tops' => ['my' => 11681, 'ph' => 15484, 'sg' => 11681, 'th' => 9873, 'vn' => 8947],
        'Suits' => ['my' => 6790, 'ph' => 18757, 'sg' => 19437, 'th' => 8809, 'vn' => 11414],
        'Tops & Pants' => ['my' => 11822, 'ph' => 15485, 'sg' => 6454, 'th' => 9874, 'vn' => 11327],

        //男装裤子
        'Jogger Pants' => ['my' => 6783, 'ph' => 18773, 'sg' => 19431, 'th' => 8815, 'vn' => 8955],
        'Jeans Pants' => ['my' => 6785, 'ph' => 18771, 'sg' => 19435, 'th' => 10023, 'vn' => 8955],
        'Formal Pants' => ['my' => 21174, 'ph' => 18773, 'sg' => 19432, 'th' => 8816, 'vn' => 8955],

        //内衣
        'Underwear' => ['my' => 6804, 'ph' => 15686, 'sg' => 11720, 'th' => 8811, 'vn' => 15024],

        //袜子
        'Socks' => ['my' => 20970, 'ph' => 21551, 'sg' => 19601, 'th' => 10601, 'vn' => 9576],


        //鞋
        'Sneakers Low Tops Shoes' => ['my' => 7136, 'ph' => 11769, 'sg' => 19586, 'th' => 21875, 'vn' => 11351],
        'Sneakers High Tops Shoes' => ['my' => 7135, 'ph' => 11770, 'sg' => 19577, 'th' => 21874, 'vn' => 11351],
        'Running Shoes' => ['my' => 11784, 'ph' => 11769, 'sg' => 19586, 'th' => 21875, 'vn' => 11351],
        'Basketball Shoes' => ['my' => 7135, 'ph' => 11770, 'sg' => 19577, 'th' => 21874, 'vn' => 11351],

        'Loafers Slip-Ons Shoes' => ['my' => 11780, 'ph' => 11763, 'sg' => 19879, 'th' => 13496, 'vn' => 11355],
        'Flip-flop Shoes' => ['my' => 11778, 'ph' => 11766, 'sg' => 6475, 'th' => 9190, 'vn' => 16942],

    ];

    public $women_apparel_category = [
        'Shirts' => ['my' => '6562', 'ph' => '6807', 'th' => '8636', 'sg' => '6416', 'vn' => '8597'],
        'T-Shirts' => ['my' => '6561', 'ph' => '6800', 'th' => '8630', 'sg' => '6415', 'vn' => '8599'],
        'Denim Jacket' => ['my' => '20844', 'ph' => '21530', 'th' => '21457', 'sg' => '21788', 'vn' => '8612'],
        'Denim Skirts' => ['my' => '6562', 'ph' => '6859', 'th' => '8628', 'sg' => '21789', 'vn' => '8607'],
        'Denim Jeans' => ['my' => '6597', 'ph' => '11872', 'th' => '8669', 'sg' => '19347', 'vn' => '8620'],
        'Denim Shorts' => ['my' => '6599', 'ph' => '7242', 'th' => '21456', 'sg' => '21787', 'vn' => '8626'],
        'Coat' => ['my' => '6574', 'ph' => '21536', 'th' => '8659', 'sg' => '11474', 'vn' => '8611'],
        'Trench Coat' => ['my' => '6574', 'ph' => '21536', 'th' => '8659', 'sg' => '11474', 'vn' => '8610'],
        'Jacket' => ['my' => '6574', 'ph' => '21533', 'th' => '8657', 'sg' => '6401', 'vn' => '8609'],
        'Hoodies' => ['my' => '6574', 'ph' => '6866', 'th' => '8660', 'sg' => '11476', 'vn' => '8609'],
        'Sweater' => ['my' => '6570', 'ph' => '21527', 'th' => '8656', 'sg' => '11476', 'vn' => '8614'],
        'Vest' => ['my' => '20844', 'ph' => '6870', 'th' => '8658', 'sg' => '19344', 'vn' => '8615'],
        'Suits' => ['my' => '19205', 'ph' => '21533', 'th' =>  '8661', 'sg' => '11475', 'vn' => '10922'],
        'Dresses' => ['my' => '6590', 'ph' => '6753', 'th' => '8628', 'sg' => '6391', 'vn' => '8601'],
        'Midi Dresses' => ['my' => '6590', 'ph' => '6753', 'th' => '21419', 'sg' => '6389', 'vn' => '8601'],
        'Mini Dresses' => ['my' => '6590', 'ph' => '6753', 'th' => '8627', 'sg' => '11490', 'vn' => '8601'],
        'Maxi Dresses' => ['my' => '6590', 'ph' => '6753', 'th' => '8628', 'sg' => '6391', 'vn' => '8601'],
        'Shirt Dresses' => ['my' => '6590', 'ph' => '6753', 'th' => '8628', 'sg' => '6391', 'vn' => '8601'],
        'Skirts' => ['my' => '6595', 'ph' => '6859', 'th' => '8639', 'sg' => '6409', 'vn' => '8607'],
        'Mini Skirts' => ['my' => '6592', 'ph' => '7241', 'th' => '8638', 'sg' => '6410', 'vn' => '8605'],
        'Midi Skirts' => ['my' => '6593', 'ph' => '6858', 'th' => '21444', 'sg' => '19714', 'vn' => '8607'],
        'Maxi Skirts' => ['my' => '6595', 'ph' => '6859', 'th' => '8639', 'sg' => '6409', 'vn' => '8607'],
        'Jumpsuits' => ['my' => '11176', 'ph' => '11061', 'th' => '8643', 'sg' => '11494', 'vn' => '10574'],
        'Shorts' => ['my' => '6599', 'ph' => '11060', 'th' => '8666', 'sg' => '21953', 'vn' => '8626'],
        'Jogger Pants' => ['my' => '6601', 'ph' => '6840', 'th' => '8668', 'sg' => '11486', 'vn' => '10917'],
        'Pants' => ['my' => '6598', 'ph' => '11057', 'th' => '8667', 'sg' => '21254', 'vn' => '8619'],

        'Top & Pants' => ['my' => '20847', 'ph' => '17628', 'th' => '16340', 'sg' => '6416', 'vn' => '10573'],
        'Top & Skirts' => ['my' => '20847', 'ph' => '17630', 'th' => '16341', 'sg' => '6416', 'vn' => '10573'],
        'Top & Shorts' => ['my' => '20847', 'ph' => '17629', 'th' => '16338', 'sg' => '6416', 'vn' => '10573'],


    ];

    public $bags_category = [];



    public $profit = [
        'my' => 1.2,
        'ph' => 18,
        'th' => 12,
        'sg' => 0.5,
        'vn' => 8300,
        'id' => 6000,
    ];

    public $discount = 0.4;

    public $vn_max_price = 2300000;


    public function init()
    {
        parent::init();
    }

    // 求$col 的下一个列号
    function excelColPlus($col)
    {
        //先转化成27进制数字
        $col_chars = str_split($col);
        $col_num = 0;
        foreach ($col_chars as $col_char) {
            $col_num = (ord($col_char) - 64) + 27 * ($col_num);
        }
        $col_num++;
        //数字转化为答案
        $ans = '';
        while ($col_num) {
            $ans .= chr(floor($col_num % 27) + 64);
            $col_num = floor($col_num / 27);
        }
        // '@' 都变成A
        $ans = str_replace('@', 'A', $ans);
        // 反转
        $ans = strrev($ans);
        return $ans;
    }

    public function generateTemplate($goods, $name, $site = 'my', $category_arr = 'category_id', $index = 0)
    {

        // $vn_max = $this->vn_max_price;

        $column = json_decode(Yii::$app->redis->get('ps_column_index'), true);

        $dir = Yii::$app->basePath . '/data/xlsx/' . $name . '/' . Date('Y-m-d H') . '/' . $site . '/';

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $filename = $dir . 'shopee_product_list_' . $name . '_' . Date('YmdHis') . '_' . $site . '_' . '' . $index . '.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet
            ->fromArray(
                array_values($column),  // The data to set
                NULL,        // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
            //    we want to set these values (default is A1)
            );

        $profit = $this->profit[$site];


        foreach ($goods as $k => $good) {

//            if ($good['is_translated'] == 2) {
//
//                $category = json_decode($good['category_id'], true)[$site];
//
//            } else {
//
//                $category = json_decode(Yii::$app->redis->get('category:' . @$this->$category_arr[$good['cate']]['th'] . ''), true)[$site];
//            }

           // $category = json_decode(Yii::$app->redis->get('category:' . @$this->$category_arr[$good['cate']]['th'] . ''), true)[$site];
            $category = json_decode($good['category_id'],true)[$site];

            // if($category){

            $price = $good['price'] * $profit;

            $c = $k + 2;

            if ($site == 'vn' && $price > $this->vn_max_price) {

                continue;

            } elseif ($site != 'vn') {

                $price = round(($price) / $this->discount);

            }


            $sheet->setCellValue('A' . $c, $category);
            $sheet->setCellValue('B' . $c, $good['title_en']);
            $sheet->setCellValue('C' . $c, $this->desc);
            $sheet->setCellValue('D' . $c, $price);
            $sheet->setCellValue('E' . $c, 10);
            $sheet->setCellValue('F' . $c, $site == 'vn' ? 1 : 0.01);
            $sheet->setCellValue('G' . $c, 2);
            $sheet->setCellValue('H' . $c, $good['shop_id'] . '/' . $good['goods_id']);


            $formats = json_decode($good['formats'], true);

            $count = count($formats);

            if ($count > 0) {

                for ($i = 1; $i <= $count; $i++) {

                    if ($i <= 20) {

                        $var_sku_index = array_search('ps_variation ' . $i . ' ps_variation_sku', $column) . $c;
                        $var_name_index = array_search('ps_variation ' . $i . ' ps_variation_name', $column) . $c;
                        $var_price_index = array_search('ps_variation ' . $i . ' ps_variation_price', $column) . $c;
                        $var_stock_index = array_search('ps_variation ' . $i . ' ps_variation_stock', $column) . $c;

                        $sheet->setCellValue($var_sku_index, '');
                        $sheet->setCellValue($var_name_index, $formats[$i - 1]);
                        $sheet->setCellValue($var_price_index, '=D' . $c . '');
                        $sheet->setCellValue($var_stock_index, 10);

                    }

                }
            }

            $imgs = json_decode($good['imgsSrc'], true);

            $imgs_cnt = count($imgs);

            for ($j = 1; $j <= $imgs_cnt; $j++) {
                $img_index = array_search('ps_img_' . $j . '', $column);
                $sheet->setCellValue($img_index . $c, $imgs[$j - 1]);
            }

            // }

        }

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        @$writer->save($filename);
    }

    public function generateTemplateNew($goods, $name, $site = 'my', $page = 0){
	
        $dir = Yii::$app->basePath . '/data/xlsx/' . $name . '/' . Date('Y-m-d H') . '/' . $site . '/';

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $filename = $dir . 'shopee_mass_upload' . $name . '_' . Date('YmdHis') . '_' . $site . '_' . '' . $page . '.xlsx';

        $contents = file_get_contents(Yii::$app->basePath . '/data/template/' . $site . '/' . $site . '_shopee_mass_upload_basic_template.xlsx');

        file_put_contents($filename, $contents);
		
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
		
        $profit = $this->profit[$site];

        $images_columns = ['N','O','P','Q','R','S','T','U','V'];

        $index = 6;

        $var_name = 'Size';
		
        foreach ($goods as $k => $good) {

            $category = json_decode($good['category_id'],true)[$site];

            $price = $good['price'] * $profit;

            if ($site == 'vn' && $price > $this->vn_max_price) {

                continue;

            } elseif ($site != 'vn') {

                $price = round(($price) / $this->discount);

            }

            $formats = json_decode($good['formats'], true);
            $imgs = json_decode($good['imgsSrc'], true);

            foreach ($formats as $f => $format){

                $index += $f;

                $sheet->setCellValue('A' . $index, $category);
                $sheet->setCellValue('B' . $index, $good['title_en']);
                $sheet->setCellValue('C' . $index, 'abc');
                $sheet->setCellValue('D' . $index, $good['shop_id'] . '/' . $good['goods_id']);
                $sheet->setCellValue('E' . $index, uniqid());
                $sheet->setCellValue('F' . $index, $var_name);
                $sheet->setCellValue('G' . $index, $format);
                $sheet->setCellValue('H' . $index, $imgs[0]);
                $sheet->setCellValue('K' . $index, $price);
                $sheet->setCellValue('L' . $index, 10);
                $sheet->setCellValue('W' . $index, 1);

                foreach ($imgs as $m => $img){

                    $sheet->setCellValue($images_columns[$m] . $index, $img);

                }

                $sheet->setCellValue('AA' . $index, 'On');

            }
        }

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        @$writer->save($filename);



    }



    public function setParams($seller)
    {

        $this->seller = $seller;
        $this->domain = $this->seller['site'] == 'cn' ? 'https://seller.xiapi.shopee.cn' : ('https://seller.' . $this->seller['site'] . '.shopee.cn');
        $auth = Yii::$app->redis->get($this->seller['username']);
        $auth = json_decode($auth, true);
        $this->SPC_CDS = $auth['SPC_CDS'];
        $this->SPC_CDS_VER = 2;
        $this->COOKIE = $auth['COOKIE'];
        $this->httpClient = new Client(['headers' => ['Cookie' => $this->COOKIE]]);

    }

    private function setLoginUrl()
    {
        $this->URI = '/api/v2/login/';
        $this->queryStr = '?SPC_CDS=' . $this->SPC_CDS . '&SPC_CDS_VER=' . $this->SPC_CDS_VER . '';
    }

    private function setGetOrderListUrl()
    {
        /*
         * ?SPC_CDS=47a9b00d-3c93-446b-90ce-afa255f3b5c0
         * &SPC_CDS_VER=2
         * &limit=40
         * &offset=0
         * &total=0
         * &flip_direction=ahead
         * &page_sentinel=0,0
         * &order_by_create_date=desc
         * &is_massship=false
         */

        $this->queryStr = http_build_query([
            'SPC_CDS' => $this->SPC_CDS,
            'SPC_CDS_VER' => $this->SPC_CDS_VER,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'total' => $this->total,
            'flip_direction' => $this->flip_direction,
            'page_sentinel' => $this->page_sentinel,
            'order_by_create_date' => $this->order_by_create_date,
            'is_massship' => $this->is_massship
        ]);

        $this->URI = '/api/v3/order/get_order_list/';

    }

    public function getRequestURL()
    {

        $this->URL = $this->domain . $this->URI . '?' . urldecode($this->queryStr);

//        echo $this->URL;
//        echo "\n";
//        echo $this->COOKIE;
//        echo "\n";

        return $this->URL;

    }

    private function execHttp($method = 'GET',$params=[])
    {
        $data = ['http_errors' => false];

        if($method !== 'GET'){
            $data['json']= $params;
        }

        $response = $this->httpClient->request($method, $this->getRequestURL(), $data);

        $output = $response->getStatusCode() == 200 ? json_decode($response->getBody(), true) : $response->getStatusCode();

        return $output;
    }

    private function execFormData($image){

        $client = new Client();

        try{
            $response = $client->request('POST',$this->getRequestURL(),[
                'headers' => ['Cookie'=>$this->COOKIE],
                'multipart' => [

                    [
                        'name' => 'file',
                        'contents' => fopen($image, 'r'),
                    ],
                ]
            ]);

        }
        catch (\Exception $ex) {
            var_dump($ex->getMessage());exit;
        }

        echo $response->getBody();

//        try {
//            $response = $client->request('POST', 'https://seller.id.shopee.cn/api/v3/general/upload_image/?SPC_CDS=a3b5e1a7-1f1c-4e84-a296-4c138aa95603&SPC_CDS_VER=2', [
//
//                'headers'=>['Cookie'=>'_ga=GA1.2.1593123027.1568830111; _ga=GA1.4.1593123027.1568830111; SPC_T_F=1; SPC_SC_SA_TK=; UYOMAPJWEMDGJ=; SPC_SC_SA_UD=; SPC_F=amnP3mudVyxuhqN7pF7PSeIMTcjuxCu0; SC_U=133537359; SPC_CDS=a3b5e1a7-1f1c-4e84-a296-4c138aa95603; SPC_T_IV="lWPiqJnAseY26lOhiM4QvQ=="; SPC_T_ID="311hIQoAHS6VPgEAhfqVDIj8M2KVu6NoAfLyECWGqSt9wtFN1hxsT0XMQ3iOXJYyJvnxMdqhT5D6txxDPMKPgX/wyLFYKmCbJSiQqp/xQK8="; csrftoken=MGwYhM0bGL6fJbACa8cABHZpgNranlX5; SPC_SC_TK=; UYOMAPJWEMDGJ=; SPC_SC_UD=; SPC_SC_SA_TK=; SPC_SC_SA_UD=; _gid=GA1.2.1869179657.1595209942; SC_DFP=plsG5sf4TgM0vcxeT93L6WJVV41vBg3p; SPC_SC_TK=e3e16897c9c2ffb6fa379f21fe5cfe94; SPC_WST="/Sb9Vfz4Q0LKo7kAAmvFy4YamRArPv3v6flbZCnN6pasyMFG+QiwfPjsHNSd2kfRYD9zYZ7f1VCQXcziDz28GN8VBy7Ols/wfZwbAPXQpKxB5D/feSJhA2tuAOF/o9MtdpPO2jzCF5PKiIqgei4bAzBT+Dx/UJGOMldt1edns20="; SPC_EC="/Sb9Vfz4Q0LKo7kAAmvFy4YamRArPv3v6flbZCnN6pasyMFG+QiwfPjsHNSd2kfRYD9zYZ7f1VCQXcziDz28GN8VBy7Ols/wfZwbAPXQpKxB5D/feSJhA2tuAOF/o9MtdpPO2jzCF5PKiIqgei4bAzBT+Dx/UJGOMldt1edns20="; SPC_SC_UD=133537359; SPC_U=13353735'],
//
//                'multipart' => [
//
//                    [
//                        'name' => 'file',
//                        'contents' => fopen($image, 'r'),
//                    ],
//                ]
//            ]);
//        } catch (\Exception $ex) {
//            var_dump($ex->getMessage());exit;
//        }
//
//        echo $response->getBody();




    }


    /*
     * shopee login api
     */
    public function doLogin()
    {

        $this->setLoginUrl();

        return $this->execHttp();
    }

    public function getOrderList()
    {

        $this->setGetOrderListUrl();

        return $this->execHttp();
    }


    public function getOrderDetail()
    {

    }

    public function getProductDetail()
    {

    }


    public function getProducts($num=1,$type='live')
    {

        //$url = 'https://seller.sg.shopee.cn/api/v3/product/page_product_list/?SPC_CDS=84e762fd-7603-4819-afe8-6776320f2d60&SPC_CDS_VER=2&page_number=1&page_size=24&list_type=&search_type=name&source=seller_center&version=3.1.0';

        $this->URI = '/api/v3/product/page_product_list/';

        $this->queryStr = http_build_query([
            'SPC_CDS' => $this->SPC_CDS,
            'SPC_CDS_VER' => $this->SPC_CDS_VER,
            'page_number' => $num,
            'page_size'=>48,
            'list_type'=>$type,
            'search_type'=>'name',
            'source'=>'seller_center',
            'version'=>'3.1.0'
        ]);

        return $this->execHttp();

    }

    /*
     * shopee market
     */

    public function getPromotionIdBySku(){

       // $url1 = 'https://seller.id.shopee.cn/api/marketing/v3/discount//search/?SPC_CDS=a3b5e1a7-1f1c-4e84-a296-4c138aa95603&SPC_CDS_VER=2&keyword=A201910201109448761693%2FI202005241223356660346729&discount_type=ongoing&search_type=item_sku';

        $this->URI = '/api/marketing/v3/discount//search/';


        $this->queryStr = http_build_query([
            'SPC_CDS' => $this->SPC_CDS,
            'SPC_CDS_VER' => $this->SPC_CDS_VER,
            'keyword'=>urlencode($this->keyword),
            'discount_type'=>'ongoing',
            'search_type'=>'item_sku',
        ]);

       // $this->URL = 'https://seller.id.shopee.cn/api/marketing/v3/discount//search/?SPC_CDS=418b654c-5606-440d-af44-d829b91d51a5&SPC_CDS_VER=2&keyword=A201910201109448761693%2FI202005241223356660346729&discount_type=ongoing&search_type=item_sku';

        return $this->execHttp();

    }

    public function getItemBySkuInPromo($discount_id){

        //https://seller.id.shopee.cn/api/marketing/v3/discount/nominate/search/
        //?SPC_CDS=a3b5e1a7-1f1c-4e84-a296-4c138aa95603&SPC_CDS_VER=2&keyword=A201910201109448761693%2FI202005241223356660346729&
        //discount_id=1071921217&
        //search_type=item_sku

        $this->URI = '/api/marketing/v3/discount/nominate/search/';

        $this->queryStr = http_build_query([
            'SPC_CDS' => $this->SPC_CDS,
            'SPC_CDS_VER' => $this->SPC_CDS_VER,
            'keyword'=>urlencode($this->keyword),
            'discount_id'=>$discount_id,
            'search_type'=>'item_sku',
        ]);

        return $this->execHttp();
    }

    public function deletePromoBySku($discount_id,$itemid){

        //https://seller.id.shopee.cn/api/marketing/v3/discount/nominate/?SPC_CDS=a3b5e1a7-1f1c-4e84-a296-4c138aa95603&SPC_CDS_VER=2
        $this->URI = '/api/marketing/v3/discount/nominate/';
        //{"discount_id":1071921217,"itemid":7935693280}

        $this->queryStr = http_build_query([
            'SPC_CDS' => $this->SPC_CDS,
            'SPC_CDS_VER' => $this->SPC_CDS_VER,
        ]);

        $args = [];
        $args['discount_id'] = $discount_id;
        $args['itemid'] = $itemid;

        $this->execHttp('DELETE',$args);
    }


    public function uploadImage($img){

        //api/v3/general/upload_image/?SPC_CDS=a3b5e1a7-1f1c-4e84-a296-4c138aa95603&SPC_CDS_VER=2



        $this->URI = '/api/v3/general/upload_image/';

        $this->queryStr = http_build_query([
            'SPC_CDS' => $this->SPC_CDS,
            'SPC_CDS_VER' => $this->SPC_CDS_VER,
        ]);

        $response = $this->execFormData($img);

        exit;


        print_r($output);

    }






}