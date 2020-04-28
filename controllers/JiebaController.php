<?php

namespace app\controllers;

//use NLP\Jieba\Jieba;
//use NLP\Jieba\Finalseg;
//use NLP\Jieba\Posseg;
use Yii;


class JiebaController extends \yii\web\Controller
{
    public function actionTest()
    {
//        ini_set('memory_limit', '1024M');
//
//        Finalseg::init();
//        Posseg::init();
//        Jieba::init(array('cjk'=>'all'));

    //    $title = "P210  古奇   原单精品2020春夏新款，专柜即将上架。帅酷爆款风衣夹克外套 时尚都市款 特供面料都是英国进口的超薄聚酯纤维纤维面料 淡淡光泽 质感独特 手感柔嫩顺滑；极简拉链开衫夹克连帽外套 完美修身版型 强烈推荐 尺码:(M~3XL最大穿190斤)";
          $title = "P160 LV2020春版新款 高端精品休闲裤 百年奢侈品牌,极具高贵的设计风格,赢取无数人的欢心。选用进口面料 颜色非常正,高成本才能出好货,整个洗水工艺都是符合环保 对人体皮肤0刺激的。实物非常漂亮,细节都能彰显大牌的品质感！尺码：28-42";

          $t = 'LV2020';
            preg_match('/A-Z/',$t,$sizeArr);

          //        preg_match('/\d{2}-\d{2}/',$title,$sizeArr);
//
       print_r($sizeArr);exit;

//        preg_match('/\w+([\-])\w+/',$title,$sizeArr);
//
//        print_r($sizeArr);
//
//
//        exit;

        $seg_list = Yii::$app->jieba->parse($title);

        print_r($seg_list);
        exit;


       // $seg_list = Posseg::cut('');
        $m = 0;
        foreach ($seg_list as $seg){

            if($seg['tag'] == 'm' && $m == 0){

                if(preg_match('/\d+/',$seg['word'],$arr)){
                    $price = $arr[0];
                }

                $m = 1;

                $pos =  strpos(strtolower($seg['word']),'burberry');
                echo $pos;
                exit;
            }

            if($seg['word'] == 'eng'){


            }
        }

        $pinyin = [];
//        foreach ($seg_list as $seg){
//
//            if($seg['tag'] != 'm' && $seg['tag'] != 'eng'){
//
//                $pinyin[] = Yii::$app->pinyin->get_all_py($seg['word']);
//                $words[] = $seg['word'];
//            }
//        }
//        print_r($pinyin);
//
//        print_r($words);

    }

    public function actionPinyin(){

        $rs = Yii::$app->pinyin->get_all_py('普拉达');
        print_r($rs);
    }

}