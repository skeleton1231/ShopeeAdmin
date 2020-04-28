<?php
namespace app\controllers;
use Yii;


class RedisController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //return $this->render('index');

        return false;
    }

    public function actionSet($key='',$vaule=''){

        Yii::$app->redis->set('accounts',json_encode($vaule));

    }

    public function actionGet($key){

        echo $key;
        echo Yii::$app->redis->get($key);

    }

    public function actionDelcategory(){


    }


    public function actionCategory(){

        $file = Yii::$app->basePath . '/data/category.xlsx';

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

        $count = count($rows);

        $columns = $rows[0];

        for($i =1;$i<$count;$i++){
            $row = $rows[$i];
            $value = [];
            foreach ($row as $k => $r){
                Yii::$app->redis->del('category:'.$r.'');
                $value[strtolower($columns[$k])] = $r;
            }
            Yii::$app->redis->set('category:'.$value['th'].'',json_encode($value));

            echo Yii::$app->redis->get('category:'.$value['th'].'');
            echo "\n";

        }

    }


}
