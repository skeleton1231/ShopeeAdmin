<?php

namespace app\controllers;

use app\models\WegoGoodsList;
use app\models\Words;
use NLP\Jieba\Finalseg;
use NLP\Jieba\Jieba;
use NLP\Jieba\Posseg;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class WegoController extends \yii\web\Controller
{

    public $supplier = [
        'A201909160222353911973' => 'oupai',
        'A2018012416361123761' => '123',
        'A2018030812060772146' => 'huihuang',
        'A201911130303131591323' => 'aison',
        'A2017110512082711937' => 'woaiyundong',
        'A2017123001495401349' => 'hongda',
        'A2017110114531608903' => 'kelenvzhuang',
        'A201805191935118730125778' => 'baobao',
		'A201910201109448761693' => 'nvxie'
    ];
	
	public function actionBag($goods_id)
    {

        $good = ArrayHelper::toArray(WegoGoodsList::find()->where(['goods_id' => $goods_id])->one());


        $good = Yii::$app->brand->parseBags($good);
		

        if ($good['is_translated'] != 1) {

            $goodM = WegoGoodsList::find()->where(['goods_id' => $good['goods_id']])->one();
            $goodM->title_en = $good['title_en'];
            $rs = $goodM->update();

            echo $rs;
        }
    }
	

    public function actionBags($shop_id)
    {

        $brands = ['MCM'=>'A201809281053489030076545','Gucci'=>'A2018010612332803328','Celine'=>'A202005091831330920350205','LV Louis Vuitton'=>'A201901052210110110169239','Chanel'=>'A202006081335465760274077','Dior'=>'A201809281053489030076545'];

        $brand = array_search($shop_id,$brands);

        if($brand){

            $command = Yii::$app->db->createCommand("SELECT * FROM `wego_goods_list` WHERE `shop_id` = '{$shop_id}' AND `price`!=0");
            $goods = $command->queryAll();

            $items = [];

            foreach ($goods as $good) {

                $good = Yii::$app->brand->parseBags($good,$brand);

                $goodM = WegoGoodsList::find()->where(['goods_id' => $good['goods_id']])->one();
                $goodM->title_en = $good['title_en'];
                $goodM->update();

                $items[] = $good;


            }

            print_r($items);

        }



    }

    public function actionSport($goods_id)
    {

        $good = ArrayHelper::toArray(WegoGoodsList::find()->where(['goods_id' => $goods_id])->one());


        $good = Yii::$app->brand->parseSport($good);

        print_r($good);


    }

    public function actionSports($shop_id)
    {

        $command = Yii::$app->db->createCommand("SELECT * FROM `wego_goods_list` WHERE `shop_id` = '{$shop_id}' AND `price`!=0");
        $goods = $command->queryAll();

        $items = [];

        foreach ($goods as $good) {

            $good = Yii::$app->brand->parseSport($good);

            // if($good['is_translated'] != 1){

            $goodM = WegoGoodsList::find()->where(['goods_id' => $good['goods_id']])->one();

            $goodM->formats = $good['formats'];
            $goodM->title_en = $good['title_en'];
            $goodM->cate = $good['cate'];
            $goodM->update();

            $items[] = $good;
            //}

        }

        print_r($items);

    }


    public function actionShoe($goods_id)
    {

        $good = ArrayHelper::toArray(WegoGoodsList::find()->where(['goods_id' => $goods_id])->one());

        $good = Yii::$app->brand->parseShoe($good);

        print_r($good);

        if ($good['is_translated'] != 1) {

            $goodM = WegoGoodsList::find()->where(['goods_id' => $good['goods_id']])->one();

            $goodM->formats = $good['formats'];
            $goodM->title_en = $good['title_en'];
            $goodM->cate = $good['cate'];
            $rs = $goodM->update();

            echo $rs;
        }

    }

    public function actionShoes($shop_id, $start = '', $end = '')
    {

        $command = Yii::$app->db->createCommand("SELECT * FROM `wego_goods_list` WHERE `shop_id` = '{$shop_id}' AND `is_translated`=0 AND `price`!=0");
        $goods = $command->queryAll();

        $items = [];

        foreach ($goods as $good) {

            $good = Yii::$app->brand->parseShoe($good);

            if ($good['is_translated'] != 2) {

                $goodM = WegoGoodsList::find()->where(['goods_id' => $good['goods_id']])->one();

                $goodM->formats = $good['formats'];
                $goodM->title_en = $good['title_en'];
                $goodM->cate = $good['cate'];
                $goodM->update();
                $goodM->is_translated = 1;
                $items[] = $good;
            }

        }

        print_r($items);
    }

    public function actionWomenshoes($shop_id){

        $command = Yii::$app->db->createCommand("SELECT * FROM `wego_goods_list` WHERE `shop_id` = '{$shop_id}' AND `is_translated`=0 AND `price`!=0");
        $goods = $command->queryAll();

        $items = [];

        foreach ($goods as $good) {

            $good = Yii::$app->brand->parseWomenShoe($good);

            if ($good['is_translated'] != 2) {

                $goodM = WegoGoodsList::find()->where(['goods_id' => $good['goods_id']])->one();

                $goodM->formats = $good['formats'];
                $goodM->title_en = $good['title_en'];
                //$goodM->cate = $good['cate'];
                $goodM->update();
                $goodM->is_translated = 1;
                $items[] = $good;
            }

        }

        print_r($items);
    }


    public function actionList($shopId = '', $search_value = '', $start_date = '', $end_date = '')
    {
        Yii::$app->wego->shopId = $shopId;
        Yii::$app->wego->search_value = $search_value;
        Yii::$app->wego->start_date = $start_date;
        Yii::$app->wego->end_date = $end_date;
        Yii::$app->wego->getList();

    }

    public function actionGet($goods_id)
    {

        $good = ArrayHelper::toArray(WegoGoodsList::find()->where(['goods_id' => $goods_id])->one());

        Yii::$app->brand->setBrands();
        Yii::$app->brand->setCategories();
        Yii::$app->brand->setMaterial();

        $item = Yii::$app->brand->parseTitle($good);
        print_r($item);

    }

    public function actionBatch()
    {
        ini_set('memory_limit', '1024M');

        Finalseg::init();
        Posseg::init();
        Jieba::init(array('cjk' => 'all'));

        $command = Yii::$app->db->createCommand('SELECT `title` FROM `wego_goods_list`');
        $goods = $command->queryAll();

        $words = [];

        foreach ($goods as $good) {

            $seg_list = Posseg::cut($good['title']);
            foreach ($seg_list as $seg) {
                $w = trim($seg['word']);
                if (!isset($words[$seg['word']])) {
                    $words[$w] = 1;
                } else {
                    $words[$w]++;
                }
            }
        }

        $arrs = [];

        foreach ($words as $key => $num) {
            $arr = [];
            $arr['word'] = $key;
            $arr['num'] = $num;
            $arrs[] = $arr;
        }


        Yii::$app->db->createCommand()->batchInsert('words', ['word', 'num'], $arrs)->execute();


    }

    public function actionTitle($shop_id)
    {
        $command = Yii::$app->db->createCommand("SELECT * FROM `wego_goods_list` WHERE `shop_id` = '{$shop_id}' AND `is_translated`=0");
        $goods = $command->queryAll();

        Yii::$app->brand->setBrands();
        Yii::$app->brand->setCategories();
        Yii::$app->brand->setMaterial();



        $items = [];

        foreach ($goods as $good) {

            $item = Yii::$app->brand->parseTitle($good);

            $goodM = WegoGoodsList::find()->where(['goods_id' => $good['goods_id']])->one();
            $goodM->price = $item['price'];
            $goodM->formats = $item['formats'];
            $goodM->title_en = $item['title_en'];
            $goodM->cate = $item['cate'];

            $goodM->is_translated = 1;
            $goodM->update();

            $items[] = $item;

        }

        print_r($items);
    }


    public function actionExcelsites($shop_id, $is_translated = '', $start = '', $end = '', $category)
    {

        ini_set('memory_limit', '2048M');

        $supplier = $this->supplier;

        $name = $supplier[$shop_id];

        $sql = 'SELECT * FROM `wego_goods_list` 
                WHERE `shop_id` = "' . $shop_id . '" 
                AND `price`!=0';

        if ($is_translated !== '') {
            $sql .= ' AND `is_translated` = ' . $is_translated . '';
        }
        //AND `is_translated` = '.$is_translated.'

        if ($start) {

            // $start_timestamp = strtotime($start) * 1000;
            $sql .= ' AND `time_stamp`>= ' . $start . '';
        }

        if ($end) {

            // $end_timestamp = strtotime($end) * 1000;
            $sql .= ' AND `time_stamp` <= ' . $end . '';

        }

        $orderby = ' ORDER BY `cate`';

        $sql .= $orderby;

        $command = Yii::$app->db->createCommand($sql);
        $goods = $command->queryAll();

        if (!$goods) {

            echo "no goods";
            exit;
        }

        $splits = Yii::$app->utils->split($goods, 500);

        foreach ($splits as $k => $split) {


            foreach (Yii::$app->shopee->sites as $site) {

                //Yii::$app->shopee->generateTemplate($split, $name, $site, $category, $k);
                Yii::$app->shopee->generateTemplateNew($split, $name, $site, $k);

            }

        }


    }

    public function actionExcel($shop_id, $is_translated = 0)
    {

        $command = Yii::$app->db->createCommand('SELECT * FROM `wego_goods_list` WHERE `shop_id` = "' . $shop_id . '" AND `is_translated` = ' . $is_translated . ' AND `price`!=0 ORDER BY `cate`');
        $goods = $command->queryAll();

        $column = json_decode(Yii::$app->redis->get('ps_column_index'), true);


        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $filename = Yii::$app->basePath . '/data/shopee_mass_upload_product_list_' . Date('YmdHis') . '_' . $shop_id . '.xlsx';

        $sheet = $spreadsheet->getActiveSheet();

        $sheet
            ->fromArray(
                array_values($column),  // The data to set
                NULL,        // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
            //    we want to set these values (default is A1)
            );


        foreach ($goods as $k => $good) {

            $c = $k + 2;


            $sheet->setCellValue('A' . $c, 10000);
            $sheet->setCellValue('B' . $c, $good['title']);
            $sheet->setCellValue('C' . $c, $good['title_en']);
            $sheet->setCellValue('D' . $c, '');
            $sheet->setCellValue('E' . $c, $good['price']);
            $sheet->setCellValue('F' . $c, 10);
            $sheet->setCellValue('G' . $c, 0.01);
            $sheet->setCellValue('H' . $c, 2);
            $sheet->setCellValue('I' . $c, $good['shop_id'] . '/' . $good['goods_id']);
            $sheet->setCellValue('J' . $c, '');


            $formats = json_decode($good['formats'], true);

            $count = count($formats);

            if ($count > 0) {

                for ($i = 1; $i <= $count; $i++) {

                    if ($i <= 20) {

                        $var_sku_index = array_search('ps_variation ' . $i . ' ps_variation_sku', $column) . $c;
                        $var_name_index = array_search('ps_variation ' . $i . ' ps_variation_name', $column) . $c;
                        $var_price_index = array_search('ps_variation ' . $i . ' ps_variation_price', $column) . $c;
                        $var_stock_index = array_search('ps_variation ' . $i . ' ps_variation_stock', $column) . $c;

                        echo $var_sku_index;
                        echo "\n";
                        echo $var_name_index;
                        echo "\n";
                        echo $var_price_index;
                        echo "\n";
                        echo $var_stock_index;
                        echo "\n";

                        try {
                            $sheet->setCellValue($var_sku_index, '');
                            $sheet->setCellValue($var_name_index, $formats[$i - 1]);
                            $sheet->setCellValue($var_price_index, '=E' . $c . '');
                            $sheet->setCellValue($var_stock_index, 10);
                        } catch (Exception $e) {

                            print_r($e);
                            print_r($good['goods_id']);
                        }
                    }

                }
            }

            $imgs = json_decode($good['imgsSrc'], true);

            $imgs_cnt = count($imgs);

            for ($j = 1; $j <= $imgs_cnt; $j++) {
                $img_index = array_search('ps_img_' . $j . '', $column);
                $sheet->setCellValue($img_index . $c, $imgs[$j - 1]);
            }

        }

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        @$writer->save($filename);
    }

    public function actionExcelcolumn()
    {

        $file = Yii::$app->basePath . '/data/colmuns_template.xlsx';

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file);


        $worksheet = $spreadsheet->getActiveSheet();
        $rows = [];
        foreach ($worksheet->getRowIterator() AS $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
            $cells = [];
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getValue();
            }
            $rows[] = $cells;
        }

        $cnt = count($rows[0]);


        //print_r($rows[0]);
        $column_index = [];
        $col = 'A';

        for ($i = 0; $i < $cnt; $i++) {
            $column_index[] = $col;
            $col = Yii::$app->shopee->excelColPlus($col);
        }

        $ps_column_index = array_combine($column_index, $rows[0]);

        Yii::$app->redis->set('ps_column_index', json_encode($ps_column_index));

        print_r(Yii::$app->redis->get('ps_column_index'));


    }

    public function actionFix()
    {

        $file = Yii::$app->basePath . '/data/fix-202003071952.xlsx';

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file);

        $worksheet = $spreadsheet->getActiveSheet();
        $rows = [];
        foreach ($worksheet->getRowIterator() AS $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(TRUE); // This loops through all cells,
            $cells = [];
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getValue();
            }
            $rows[] = $cells;
        }

        $total = count($rows);

        $table = WegoGoodsList::tableName();


        for ($i = 0; $i <= $total - 1; $i++) {

            $title = str_replace('"', '', $rows[$i][0]);
            $sku = explode('/', $rows[$i][1]);
            $shop_id = $sku[0];
            $goods_id = $sku[1];
            $sql = "UPDATE `{$table}` SET `title_en` = \"{$title}\", `is_translated` = 1 where `shop_id`= '{$shop_id}' AND `goods_id` = '{$goods_id}'";

            Yii::$app->db->createCommand($sql)->execute();

        }


        //echo $worksheet->getCellByColumnAndRow('A',)
    }

    public function actionWomenapparel($goods_id)
    {

        $good = ArrayHelper::toArray(WegoGoodsList::find()->where(['goods_id' => $goods_id])->one());


        $good = Yii::$app->brand->parseWomenApparel($good);

        print_r($good);
    }

    public function actionWomenapparels($shop_id)
    {

        $command = Yii::$app->db->createCommand("SELECT * FROM `wego_goods_list` WHERE `shop_id` = '{$shop_id}' AND `price`!=0 AND `is_translated` != 2");
        $goods = $command->queryAll();

        $items = [];

        foreach ($goods as $good) {

            $good = Yii::$app->brand->parseWomenApparel($good);

            $goodM = WegoGoodsList::find()->where(['goods_id' => $good['goods_id']])->one();
            $goodM->formats = $good['formats'];
            $goodM->title_en = $good['title_en'];
            $goodM->cate = $good['cate'];
            $goodM->update();

            $items[] = $good;


        }

        print_r($items);
    }


    public function actionBelt($goods_id)
    {

        $good = ArrayHelper::toArray(WegoGoodsList::find()->where(['goods_id' => $goods_id])->one());


        $good = Yii::$app->brand->parseBelt($good);

        print_r($good);
    }

    public function actionBelts($shop_id){

        $command = Yii::$app->db->createCommand("SELECT * FROM `wego_goods_list` WHERE `shop_id` = '{$shop_id}' AND `is_translated`=0 AND `price`!=0");
        $goods = $command->queryAll();

        $items = [];

        foreach ($goods as $good) {

            $good = Yii::$app->brand->parseBelt($good);

            if ($good['is_translated'] != 2) {

                $goodM = WegoGoodsList::find()->where(['goods_id' => $good['goods_id']])->one();

                $goodM->formats = $good['formats'];
                $goodM->title_en = $good['title_en'];
                $goodM->cate = $good['cate'];
                $goodM->update();
                $goodM->is_translated = 1;
                $items[] = $good;
            }

        }

        print_r($items);
    }


    public function actionEasytemplate($shop_id, $is_translated = '', $start = '', $end = '', $category = 'women_apparel_category')
    {

        $sql = 'SELECT * FROM `wego_goods_list` 
                WHERE `shop_id` = "' . $shop_id . '" AND `title` != "" ';

        if ($is_translated !== '') {
            $sql .= ' AND `is_translated` = ' . $is_translated . '';
        }
        //AND `is_translated` = '.$is_translated.'

        if ($start) {

            // $start_timestamp = strtotime($start) * 1000;
            $sql .= ' AND `time_stamp`>= ' . $start . '';
        }

        if ($end) {

            // $end_timestamp = strtotime($end) * 1000;
            $sql .= ' AND `time_stamp` <= ' . $end . '';

        }

        $orderby = ' ORDER BY `cate`';

        $sql .= $orderby;
		
		//echo $sql;exit;

        $command = Yii::$app->db->createCommand($sql);
        $goods = $command->queryAll();

        if ($goods) {

            $output_dir = \Yii::$app->basePath . "/data/xlsx/{$goods[0]['shop_id']}/";
            $start_day = Date('Y-m-d', explode('.', $start / 1000)[0]);
            $end_day = Date('Y-m-d', explode('.', $end / 1000)[0]);
            $output_name = "{$start_day}-{$end_day}.xlsx";

            $output = $output_dir . $output_name;

            if (!file_exists($output_dir)) {
                mkdir($output_dir, 0777, true);
            }

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $columns = ['title', 'title_en', 'category', 'img1', 'img2', 'img3', 'img4', 'img5', 'img6', 'img7', 'img8', 'img9', 'sku', 'price'];

            $imgs_col = ['D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];

            $sheet
                ->fromArray(
                    $columns,  // The data to set
                    NULL,        // Array values with this value will not be set
                    'A1'         // Top left coordinate of the worksheet range where
                //    we want to set these values (default is A1)
                );

            $sheet->getColumnDimension('B')->setWidth(20);

            foreach ($goods as $k => $good) {

                $c = $k + 2;
                $imgs = json_decode($good['imgsSrc'], true);

                $sheet->setCellValue('A' . $c, $good['title']);
                $sheet->setCellValue('B' . $c, $good['title_en']);
                $sheet->setCellValue('C' . $c, @Yii::$app->shopee->$category[$good['cate']]['th']);

                foreach ($imgs as $i => $img) {

                    $pos = $imgs_col[$i] . $c;

                    $sheet->setCellValue($pos, $img);

                }

                $sheet->setCellValue('M' . $c, $good['shop_id'] . '/' . $good['goods_id']);
                $sheet->setCellValue('N' . $c, $good['price']);
            }

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

            @$writer->save($output);

        }

    }

    public function actionUpload($name, $category = 'category_id', $format = 1)
    {

        $file = Yii::$app->basePath . '/data/xlsx/upload/' . $name . '.xlsx';

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file);


        $worksheet = $spreadsheet->getActiveSheet();
        $rows = [];
        foreach ($worksheet->getRowIterator() AS $row) {

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
            $cells = [];
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getValue();
            }
            $rows[] = $cells;
        }

        unset($rows[0]);

        $errors = [];

        $models = [];


        foreach ($rows as $row) {

            $title_en = trim($row[0]);
            $cate = trim($row[1]);
            $sku = explode("/", trim($row[2]))[1];
            if (isset($row[3])) {
                $price = trim($row[3]);
            }

//            echo $title_en;
//            echo "\n";
//            echo $cate;
//            echo "\n";
//            echo $sku;
//            echo "\n";
//            echo $price;
//            echo "\n";

            if (strlen($title_en) > 0) {

                $sku_brr = substr($sku, -6);

                if (strpos($title_en, $sku_brr) === false) {

                    $title_en .= " " . $sku_brr;
                }


                if ($format == 1) {

                    $sex = Yii::$app->brand->parseSex($title_en);

                    $formats = json_encode(Yii::$app->brand->clothesSize($title_en, $sex));

                } else {

                    $formats = '[]';
                }

                $category_id = Yii::$app->redis->get('category:' . $cate . '');

                $goodM = WegoGoodsList::find()->where(['goods_id' => $sku])->one();

                $goodM->title_en = trim($title_en);
                $goodM->category_id = $category_id;
                $goodM->formats = $formats;

                if (isset($price)) {
                    $goodM->price = $price;
                }

                $goodM->is_translated = 2;

                $goodM->update();

                if ($price != 0) {

                    $models[] = ArrayHelper::toArray($goodM);

                }

            } else {

                $errors[] = $row;

            }

        }

        //print_r($models);
        //exit;

        $splits = Yii::$app->utils->split($models, 500);

        foreach ($splits as $k => $split) {


            foreach (Yii::$app->shopee->sites as $site) {

                //Yii::$app->shopee->generateTemplate($split, $name, $site, $category, $k);
				Yii::$app->shopee->generateTemplateNew($split, $name, $site, $k);

            }

        }


    }
}




