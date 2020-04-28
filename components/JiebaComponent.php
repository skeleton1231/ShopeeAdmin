<?php
namespace app\components;

use NLP\Jieba\Finalseg;
use NLP\Jieba\Jieba;
use NLP\Jieba\Posseg;
use yii\base\Component;

class JiebaComponent extends Component
{

    public function init(){
        parent::init();

        ini_set('memory_limit', '1024M');
        Finalseg::init();
        Posseg::init();
        Jieba::init(array('cjk'=>'all'));
    }

    public function parse($str){

        return Posseg::cut($str);
    }
}