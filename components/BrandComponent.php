<?php

namespace app\components;

use Faker\Provider\Image;
use Yii;
use yii\base\Component;


Class BrandComponent extends Component
{
    public $brands;
    public $brands_en;
    public $categories;
    public $material;

    public $en_num_size = ['XS','S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL','6XL'];
    public $en_size = ['XS','S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
    public $size;
    public $formats;

    public $men_clothes_category = [


    ];


    public $women_category = [

        'Maxi Dresses' => ['连衣裙' => [
            'Midi Dresses' => ['中长'],
            'Mini Dresses' => ['迷你'],
        ]],

        'Shirt Dresses' => ['衬衣裙'=>[],],

        'Maxi Skirts' => ['裙' => [
            'Midi Skirts' => ['中长'],
            'Mini Skirts' => ['迷你','短'],
        ]],

        'T-Shirts' => ['T恤'=>[],'T'=>[]],

        'Shirts' => ['衬衣'=>[], '衬衫'=>[], '上衣'=>[]],

        'Denim' => ['牛仔' => [
            'Denim Jacket' => ['外套','夹克'],
            'Denim Skirts' => ['裙','短裙'],
            'Denim Jeans' => ['裤'],
            'Denim Shorts' => ['短裤'],
        ]
        ],

        'Coat' => ['外套'=>[]],
        'Trench Coat'=>['风衣'=>[]],
        'Jacket' => ['夹克'=>[],'皮衣'=>[]],
        'Hoodies' => ['连帽'=>[]],
        'Sweater' => ['卫衣'=>[],'毛衣'=>[],'开衫'=>[]],
        'Vest' => ['背心'=>[]],
        'Suits'=>['西装'=>[]],

        'Shorts' =>['短裤' =>[]],

        'Pants' => ['裤' => [
            'Jogger Pants' => ['休闲'],
            'Jumpsuits' =>['连体'],
        ]
        ],

    ];

    public $shoes_category = [
        'Sneakers Low Tops Shoes' => ['休闲鞋', '板鞋', '低帮'],
        'Sneakers High Tops Shoes' => ['高帮', '老爹鞋'],
        'Running Shoes' => ['跑步鞋', '慢跑鞋'],
        'Basketball Shoes' => ['篮球鞋'],
        'Loafers Slip-Ons Shoes' => ['懒人鞋', '一脚蹬', '豆豆', '西装', '商务', '皮鞋'],
        'Flip-flop Shoes' => ['拖鞋', '人字拖']
    ];

    public $bags_category= [

        'Crossbody Bags' => ['肩','挎包'],
        'Backpacks' => ['双肩包','背包'],
        'Tote Bags' => ['手提',],
        'HandBags' => ['手包',],

    ];

    public function parseBrandV2($title){

        $brand = '';
        $title = strtolower($title);


        foreach ($this->brands as $k => $brand_v){

            foreach ($brand_v as $b_v){
                if(strpos($title,strtolower($b_v)) !== false){
                    $brand = $k;
                }
            }
        }


        if (!$brand) {
            foreach ($this->brands_en as $key => $b_item) {
                $brand_arr = explode(' ',$b_item);
                foreach ($brand_arr as $arr){
                    if (strpos($title, strtolower($arr)) !== false) {
                        $brand = $b_item;
                    }
                }

            }
        }

        if(!$brand){

            foreach ($this->brands_en as $brand_en){

                $words = explode(' ',$brand_en);
                foreach ($words as $word){
                    $len = strlen($word);
                    for($i=0;$i<$len;$i++){
                        $replace=$word;
                        $replace[$i]='*';
                        if(strpos($title,strtolower($replace))!==false){
                            $brand = $brand_en;
                        }
                    }
                }

            }
        }

        return $brand;
    }


    public function parseBrand($seg_list, $price)
    {

        $brand = '';
        $brands = [];
        $last_word = '';
        $brand_en = array_keys($this->brands);
        $title = [];

        foreach ($seg_list as $seg) {

            $word = trim(strtolower($seg['word']));
            $title[] = $word;

            $word = str_replace(' ', '', $word);

            $tag = $seg['tag'];

            if (strtolower($word) == 'm') {

                continue;
            }

            if ($tag == 'm') {

                if (!$last_word && preg_match('/\d+/', $word, $ret)) {

                    if ($ret[0] == $price) {

                        $last_word = $word;

                        continue;
                    }
                }
            }

            if ($tag != 'm' && $tag != 'eng') {

//                echo $tag . '------------';
//                echo "\n";

                foreach ($this->brands as $key => $value) {

                    foreach ($value as $v) {

//                        echo $word;
//                        echo "\n";
//                        echo $v;
//                        echo "\n";

                        if ($v == $word) {
                            $brands[] = $key;
                        }
//                        elseif (strpos($v, $word) !== false) {
//                            $brands[] = $key;
//                        }

//                        print_r($brands);
//                        echo "\n";
                    }
                }
            }

            if ($tag == 'eng') {

//                echo $tag . '------------';
//                echo "\n";

                foreach ($brand_en as $key => $value) {

                    $stack = trim(strtolower($value));

                    $stack = explode(" ", $stack);

//                    echo $word;
//                    echo "\n";
//                    print_r($stack);
//                    echo "\n";

                    if (in_array($word, $stack)) {
                        $brands[] = $value;
                    }

//                    print_r($brands);
//                    echo "\n";

                }

            }

        }


        if ($last_word && !$brands) {

//            echo $last_word;
//            echo "\n";
//            echo $price;
//            echo "\n";

            $last_word_arr = explode($price, $last_word);

            $needle = $last_word_arr[1];

            foreach ($brand_en as $key => $value) {

                $stack = trim(strtolower($value));

                $stack = explode(" ", $stack);

//                echo $needle;
//                echo "\n";
//                print_r($stack);
//                echo "\n";

                if (in_array($needle, $stack)) {
                    $brands[] = $value;
                }
//                print_r($brands);
//                echo "\n";
            }

        }

        $brands = array_unique($brands);
        $count = count($brands);

        if ($count == 1) {
            $brand = $brands[0];
        } elseif ($count > 1) {
            $brand = implode(' ', $brands);

        }

        if (!$brand) {
            $b_items = array_keys($this->brands);
            $title = implode(' ', $title);
            foreach ($b_items as $key => $b_item) {
                if (strpos($title, strtolower($b_item)) !== false) {
                    $brand = $b_item;
                }
            }
        }

        return $brand;
    }

    public function pareseCategoryV2($title,$cates = 'shoes_category'){

        $title = strtolower($title);

        $category = '';

        foreach ($this->$cates as $k => $cate){

            foreach ($cate as $c){

                if(strpos($title,$c) !== false){

//                    echo $c;
//                    echo "\n";
//                    echo $k;
//                    echo "\n";

                    $category = $k;
                }

            }
        }

//        if(!$category){
//
//            if ($cates == 'shoes_category') {
//
//                $category = 'Sneakers Low Tops Shoes';
//
//            } else {
//
//                $category = 'T-Shirts';
//
//            }
//
//        }

        return $category;

    }

    public function parseCategory($seg_list, $cate = 'shoes_category')
    {

        foreach ($seg_list as $seg) {

            $word = strtolower($seg['word']);
            $tag = strtolower($seg['tag']);

            if ($tag != 'eng' && $tag != 'm') {

                foreach ($this->$cate as $key => $value) {

                    foreach ($value as $v) {

                        if ($v == $word) {
                            $category = $key;
                            return $category;
                        }
                    }
                }

            }
        }
        if ($cate == 'shoes_category') {

            $category = 'Sneakers Low Tops Shoes';

        } else {

            $category = 'T-Shirts';

        }

        return $category;
    }

    public function setBrands()
    {
        $this->brands = [
            'Fendi' => ['芬迪','F家'],
            'Celine'=>['思琳'],
            'Dolce Gabbana' => ['杜嘉班纳'],
            'LV Louis Vuitton' => ['路易威登', '路易斯', '威登','L家','驴家'],
            'Valentino' => ['华伦天奴','val'],
            'Thom Browne' => ['汤姆布朗',],
            'PP Phlipp Plein' => ['菲利普普兰'],
            'Balenciaga' => ['巴黎世家',],
            'Chanel' => ['香奈儿','小香'],
            'Chrome Hearts' => ['克罗心'],
            'AJ Armani' => ['阿玛尼'],
            'Gucci' => ['古琦', '古奇', '古驰', '古齐', '古池','G家'],
            'Hermes' => ['爱马仕','爱马'],
            'Burberry' => ['巴宝莉', '博柏利', '巴堡莉', '巴宝利', 'B家', '百宝莉','巴宝'],
            'Versace' => ['范思哲', '范家', '美杜莎', '巴洛克','范思'],
            'Prada' => ['普拉达','普拉'],
            'Dior' => ['迪奥','D家'],
            'Moncler' => ['蒙口'],
            'Canada Goose' => ['大鹅'],
            'Stone Island' => ['石头岛'],
            'Givenchy' => ['纪梵希', '梵希'],
            'Loewe' => ['罗意威'],
            'NY' => ['洋基队'],
            'Ralph Lauren' => ['拉夫劳伦', '保罗'],
            'Off White' => [],
            'Moschino' => [],
            'Hazzys' => ['哈吉斯'],
            'Kenzo' => ['凯卓'],
            'Lacoste' => ['鳄鱼'],
            'Christian Louboutin' => ['克里斯提', '红底'],
            'Ferragamo' => ['菲拉格慕'],
            "Tod's" => ['托德斯'],
            'BV BottegaVeneta' => ['葆蝶家'],
            'GGDB Golden Goose' => ['小脏鞋'],
            //'Ed Hardy' => ['埃德', '哈迪'],
            'Champion' => ['冠军'],
            'Boy' => [],
            'Hugo Boss' => [],
            'MMJ Mastermind JAPAN' => [],
            'Tommy' => [],
            'Alexander McQueen' => ['麦昆'],
            'BALLY' => ['巴利'],
            'Miu Miu'=>[],
            'Balmain'=>['巴尔曼','巴尔'],
            'Maje'=>[],
            'MCM'=>[],

            //运动
            'Nike' => ['耐克'],
            'Adidas' => ['阿迪达斯'],
            'Vans' => ['万斯'],
            'Converse' => ['匡威'],
            'Reebok' => ['锐步'],
            'Onitsuka tiger' => ['鬼塚虎',],
            'Asics' => ['亚瑟士'],
            'Puma' => ['彪马'],
            'Fila' => ['斐乐'],
            'Y-3' => [],

        ];

        $this->brands_en = array_keys($this->brands);

    }

    public function setCategories()
    {

        $this->categories = [
            'Jackets' => ['夹克', '外套','风衣'],
            'Sweater' => ['毛衣', '羊绒衫', '羊毛衫'],
            'Down Jackets' => ['羽绒服'],
            'Vest' => ['马甲'],
            'Shirts' => ['衬衫','衬衣'],
            'Polo Shirts' => ['polo'],
            'T-Shirts' => ['T恤','tee','卫衣'],
            'Hoodies' => ['连帽'],
            'Jogger Pants' => ['休闲裤','长裤'],
            'Jeans Pants' => ['牛仔裤'],
            'Formal Pants' => ['西裤'],
            'Underwear' => ['内裤'],
            'Suits' => ['西装', '西服'],
            'Socks' => ['袜'],
            'Tops & Pants' => ['套装'],
        ];
    }

    public function setMaterial()
    {

        $this->material = [
            'Leather' => ['皮'],
            'Canvas' => ['布'],
            'Cotton' => ['棉'],
        ];

    }

    public function parseSex($title,$sex="Men's")
    {
        //   $sex = "Men's";
        //查找sex
        $men = strpos($title, '男');
        $women = strpos($title, '女');
        $couple = strpos($title, '情侣');


        if ($men !== false && $women !== false) {
            $sex = "Men's And Women's";
        } elseif ($couple) {
            $sex = "Couple's";
        } elseif ($men) {
            $sex = "Men's";
        } else if ($women) {
            $sex = "Women's";
        }


        return $sex;
    }

    public function parseBags($good,$brand){

        $this->setBrands();
        $this->setCategories();
        $this->setMaterial();

        //\d+[\s\S]*\d+[\s\S]*\d+
        //
        $good['title'] = str_replace('，', ',', $good['title']);
        $good['title'] = str_replace('～', '-', $good['title']);
        $good['title'] = str_replace('：', ':', $good['title']);
        $good['title'] = str_replace('—', '-', $good['title']);
        $good['title'] = str_replace(':', '', $good['title']);
        $good['title'] = preg_replace('/[A-Z][0-9]{5}/', '', $good['title']);
        $good['title'] = str_replace($good['price'], '', $good['title']);
		$good['title'] = str_replace(',', ' ', $good['title']);
        $good['title'] = strtolower($good['title']);

        //$brand = $this->parseBrandV2($good['title']);

        $category = $this->pareseCategoryV2($good['title'], 'bags_category');


        if(!$category){

            $category = 'Bag';
        }

        $sex = $this->parseSex($good['title'],"Women's");

        $good['title'] = preg_replace('#[\x{4e00}-\x{9fa5}]#u', '', $good['title']);
        $good['title'] = preg_replace('/([\x80-\xff]*)/i', '', $good['title']);

        $size = '';

        if(preg_match('/\d+[\s\S]*\d+[\s\S]*\d+/',$good['title'],$match)){

            $size = $match[0];
        }

        $code = substr($good['shop_id'] . '/' . $good['goods_id'], -6);

        $good['title_en'] = 'Original ' . Date('Y') . ' ' . $brand . ' ' . $sex . ' ' . $category . ' ';

        if($size){

            $good['title_en'] .=  $size . ' ' . $code;
        }
        else{

            $good['title_en'] .= $code;
        }

        $good['title_en'] = ucwords($good['title_en']);

        $good['formats'] = '[]';

        return $good;

    }

    public function parseTitle($good)
    {
        $m = 0;

        $item = $good;
        $item['title'] = str_replace('，', ',', $item['title']);
        $item['title'] = str_replace('：', ':', $item['title']);
        $item['title'] = str_replace('～', '-', $item['title']);
        $item['title'] = str_replace('~', '-', $item['title']);
        $item['title'] = str_replace('-2020', '-', $item['title']);
        //-2020

        $title = str_replace('~', '', $item['title']);
        // $title = str_replace('-', '', $title);
        $title = str_replace('*', '', $title);
        $title = str_replace('&', '', $title);
        $title = str_replace('20SS', '', $title);

        $title = preg_replace('/[A-Z][0-9]-[0-9]{5}/','',$title);

        $segs = Yii::$app->jieba->parse($title);

        $item['brand'] = $this->parseBrand($segs, $item['price']);

        $item['brand'] = $this->parseBrandV2($title);

        $item['cate'] = $this->pareseCategoryV2($title, 'categories');

        //查找brand,category
        foreach ($segs as $seg) {

            $tag = $seg['tag'];
            $word = $seg['word'];

            if (strtolower($word) == 'm') {

                continue;
            }

            if ($tag == 'm' && $m == 0 && !$item['price']) {
                if (preg_match('/\d+/', $seg['word'], $ret)) {
                    $item['price'] = $ret[0];
                    $m = 1;
                }
            }

        }

        $item['sex'] = $this->parseSex($title);

        $this->clothesSize($title,$item['sex']);

        //查找尺寸
//        if (preg_match_all('/M-[\w+][A- Z]{2}/', trim($title), $sizeArr)) {
//
//            $format_str = $sizeArr[0][0];
//
//        } else {
//            $format_str = 'M-3XL';
//        }
//
//
//        $size_str = "Size: $format_str";
//
//        $size = explode('-', $format_str);
//
//
//        $start = array_search('M', $this->en_num_size);
//        $end = array_search('3XL', $this->en_num_size);
//
//        $formats = [];
//
//        //$item['formats'] = json_decode($item['formats'], true);
//
//        if (isset($size[1])) {
//
//            $size[0] = strtoupper($size[0]);
//            $size[1] = strtoupper($size[1]);
//
//            if (in_array($size[1], $this->en_num_size)) {
//
//                $start = array_search($size[0], $this->en_num_size);
//                $end = array_search($size[1], $this->en_num_size);
//
//                for ($i = $start; $i <= $end; $i++) {
//                    $formats[] = $this->en_num_size[$i];
//                }
//            } elseif (in_array($size[1], $this->en_size)) {
//
//                $start = array_search($size[0], $this->en_size);
//                $end = array_search($size[1], $this->en_size);
//
//                for ($i = $start; $i <= $end; $i++) {
//                    $formats[] = $this->en_size[$i];
//                }
//            } elseif (is_numeric($start) && is_numeric($end)) {
//                $start = (int)$size[0];
//                $end = (int)$size[1];
//                for ($i = $start; $i <= $end; $i++) {
//                    $formats[] = $i;
//                }
//
//            }
//        } else {
//            for ($i = $start; $i <= $end; $i++) {
//                $formats[] = $this->en_num_size[$i];
//            }
//        }

        $size_str = 'Size: '.$this->size.'';

        $item['formats'] = json_encode($this->formats);

        $item['parent_sku'] = $item['shop_id'] . '/' . $item['goods_id'];

        $sku_brr = substr($item['goods_id'], -6);

        $item['title_en'] = 'Original ' . Date('Y') . ' Latest ' . $item["brand"] . ' ' . $item['sex'] . ' ';

        $sleeves = $this->sleeves($title);

        if($sleeves){

            $item['title_en']  .=  $sleeves . ' ';
        }

        $item['title_en']  .= '' . $item["cate"] . ' ' . $size_str . ' ' . $sku_brr . '';

        $item['title_en'] = ucwords($item['title_en']);

        return $item;
    }


    public function parseShoe($good)
    {
        $good['title'] = str_replace('，', ',', $good['title']);
        $good['title'] = str_replace('～', '-', $good['title']);
        $good['title'] = str_replace('：', ':', $good['title']);
        $good['title'] = str_replace('—', '-', $good['title']);
        $good['title'] = str_replace('2019', '', $good['title']);
        $good['title'] = str_replace('2020', '', $good['title']);
        $good['title'] = str_replace('*', '', $good['title']);
        $good['title'] = preg_replace('/([A-Z]{2})\w+-([0-9]{3})/', '', $good['title']);

        $good['title'] = strtolower($good['title']);

        if (preg_match('/\d{2}-\d{2}/', $good['title'], $sizeArr)) {

            $size = $sizeArr[0];
        } else {
            $size = '38-44';
        }


        $size_arr = explode('-', $size);
        $start = (int)$size_arr[0];
        $end = (int)$size_arr[1];

        $formats = [];

        for ($i = $start; $i <= $end; $i++) {

            if ($i < 46) {
                $formats[] = $i;
            }
        }

        //  $seg_list = Yii::$app->jieba->parse($good['title']);

        $this->setBrands();
        $this->setCategories();
        $this->setMaterial();

        $brand = $this->parseBrandV2($good['title']);

        $category = $this->pareseCategoryV2($good['title']);


//        foreach ($this->shoes_category as $key => $shoe_category) {
//
//            foreach ($shoe_category as $sc) {
//
//                if (strpos($good['title'], $sc) !== false) {
//
//                    $category = $key;
//                }
//            }
//        }
//
//        if (!$category) {
//            $category = $this->parseCategory($seg_list);
//        }

        $good['cate'] = $category;

        //echo $category;
        //echo "\n";

        $sex = $this->parseSex($good['title']);

        $code = substr($good['shop_id'] . '/' . $good['goods_id'], -6);

        $good['title_en'] = 'Original ' . Date('Y') . ' ' . $brand . ' ' . $sex . ' Leather ' . $category . ' Size: ' . $size . ' ' . $code . '';

        $good['formats'] = json_encode($formats);

        return $good;
    }


    public function parseWomenShoe($good){

        $sex = "Women's";

        if (preg_match('/\d{2}-\d{2}/', $good['title'], $sizeArr)) {

            $size = $sizeArr[0];
        } else {
            $size = '35-40';
        }


        $size_arr = explode('-', $size);
        $start = (int)$size_arr[0];
        $end = (int)$size_arr[1];

        $formats = [];

        for ($i = $start; $i <= $end; $i++) {

            if ($i < 46) {
                $formats[] = $i;
            }
        }

        $this->setBrands();
        $this->setCategories();

        $brand = $this->parseBrandV2($good['title']);

        $code = substr($good['shop_id'] . '/' . $good['goods_id'], -6);

        $good['title_en'] = 'Original ' . Date('Y') . ' ' . $brand . ' ' . $sex . ' Leather Shoes' .  ' Size: ' . $size . ' ' . $code . '';

        $good['formats'] = json_encode($formats);

        return $good;

    }

    public function parseSport($good)
    {

        $this->setBrands();
        $this->setCategories();
        $this->setMaterial();

        $good['title'] = str_replace('，', ',', $good['title']);
        $good['title'] = str_replace('～', '-', $good['title']);
        $good['title'] = str_replace('：', ':', $good['title']);
        $good['title'] = str_replace('—', '-', $good['title']);
        $good['title'] = str_replace('2019', '', $good['title']);
        $good['title'] = str_replace('2020', '', $good['title']);
        $good['title'] = str_replace('*', '', $good['title']);
        $good['title'] = str_replace('!', '', $good['title']);
        $good['title'] = str_replace('！', '', $good['title']);
        $good['title'] = str_replace('‼', '', $good['title']);
        $good['title'] = str_replace(':', '', $good['title']);
        $good['title'] = str_replace(',', '', $good['title']);
        $good['title'] = str_replace('+ ', '', $good['title']);
        $good['title'] = str_replace('\r\n', '', $good['title']);
        $good['title'] = str_replace('Size', '', $good['title']);


        $seg_list = Yii::$app->jieba->parse($good['title']);
//        print_r($seg_list);
//        exit;


        $brand = strtolower($this->parseBrand($seg_list, $good['price']));

        $title = strtolower($good['title']);
        $title = str_replace($brand, '', $title);
        $title = strtolower(str_replace($good['price'], '', $title));

//        preg_match('/[a-z]{3}[0-9]{3}-[a-z]{3}/',$title,$arr);
//
//        print_r($arr);
//        exit;

//        echo $good['title'];
//        echo "\n";

        $title = preg_replace('/[a-z]{3}[0-9]{3}-[a-z]{3}/', '', $title);
        $title = preg_replace('#[\x{4e00}-\x{9fa5}]#u', '', $title);
        $title = preg_replace('/([\x80-\xff]*)/i', '', $title);

        // $title = preg_replace("/[^\x{4e00}-\x{9fa5}]/iu",'',$title);

//        foreach ($seg_list as $seg){
//
//            if($seg['tag'] != 'eng' && $seg['tag'] != 'm'){
//
//                $title = mb_ereg_replace($seg['word'], '', $title);
//
//            }
//
//        }


        $title_words = explode(' ', $title);

        $model = [];
        $formats = [];
        $default_formats_str = "36-44";


        foreach ($title_words as $title_word) {

            if (trim($title_word)) {

                $model[] = trim($title_word);
            }

            if (is_numeric($title_word) && $title_word < 49 && $title_word >= 35) {

                $formats[] = $title_word;

            }
        }



        if ($formats && count($formats) > 6) {

            $formats[] = intval($formats[0]);
            $formats[] = 44;
            sort($formats);
            $formats = array_unique($formats);
            $formats = array_values($formats);



        } else {

            $formats = json_decode($good['formats'], true);

            $formats_cnt = count($formats);

            if (!$formats || $formats_cnt < 5) {

                if (preg_match('/\d{2}-\d{2}/', $good['title'], $sizeArr)) {

                    $size = $sizeArr[0];

                } else {
                    $size = $default_formats_str;
                }

                $formats = [];
                $size_arr = explode('-', $size);
                $start = (int)$size_arr[0];
                $end = (int)$size_arr[1];

                for ($i = $start; $i <= $end; $i++) {
                    $formats[] = $i;
                }

            }

        }

        foreach ($formats as $ft => $format) {

            $formats[$ft] = trim($format);
        }
        // if(count($formats) > count(json_decode($good['formats']),true)){
        $formats = array_unique($formats);
        $formats = array_values($formats);


        $good['formats'] = json_encode($formats);
        //}


        if ($formats) {
            $size = min($formats) . '-' . max($formats);
        }


        $category = '';

        foreach ($this->shoes_category as $key => $shoe_category) {

            foreach ($shoe_category as $sc) {

                if (strpos($good['title'], $sc) !== false) {

                    $category = $key;
                }
            }
        }

        if (!$category) {
            $category = $this->parseCategory($seg_list);
        }

        $good['cate'] = $category;

        $code = substr($good['shop_id'] . '/' . $good['goods_id'], -6);

//        unset($model[count($model)-1]);
//        unset($model[count($model)-2]);
//
        $model = implode(' ', $model);

        $good['title_en'] = $this->uniqueWords(ucwords('Original ' . $brand . ' ' . $model . ' ' . $category . ' Size:' . $size . ' ' . $code));

        return $good;
    }

    public function parseBelt($good){

        $this->setBrands();
        $this->setCategories();
        $this->setMaterial();

        //\d+[\s\S]*\d+[\s\S]*\d+
        //
        $good['title'] = str_replace('，', ',', $good['title']);
        $good['title'] = str_replace('～', '-', $good['title']);
        $good['title'] = str_replace('：', ':', $good['title']);
        $good['title'] = str_replace('—', '-', $good['title']);
        $good['title'] = str_replace(':', '', $good['title']);
        $good['title'] = preg_replace('/[A-Z][0-9]{5}/', '', $good['title']);
        $good['title'] = str_replace($good['price'], '', $good['title']);
        $good['title'] = str_replace(',', ' ', $good['title']);
        $good['title'] = strtolower($good['title']);

        $category = 'Belts';

        $sex = $this->parseSex($good['title'],"Women's");

        $good['title'] = preg_replace('#[\x{4e00}-\x{9fa5}]#u', '', $good['title']);
        $good['title'] = preg_replace('/([\x80-\xff]*)/i', '', $good['title']);

        $code = substr($good['shop_id'] . '/' . $good['goods_id'], -6);

        $good['title_en'] =  $good['title'] . ' ' . $sex . ' ' . $category . ' ' . $code;

        $good['title_en'] = ucwords($good['title_en']);

        $good['formats'] = '[]';

        return $good;
    }

    public function parseWatch($good){

        $this->setBrands();
        $this->setCategories();
        $this->setMaterial();

        //\d+[\s\S]*\d+[\s\S]*\d+
        //
//        $good['title'] = str_replace('，', ',', $good['title']);
//        $good['title'] = str_replace('～', '-', $good['title']);
//        $good['title'] = str_replace('：', ':', $good['title']);
//        $good['title'] = str_replace('—', '-', $good['title']);
//        $good['title'] = str_replace(':', '', $good['title']);
//        $good['title'] = preg_replace('/[A-Z][0-9]{5}/', '', $good['title']);
//        $good['title'] = str_replace($good['price'], '', $good['title']);
//        $good['title'] = str_replace(',', ' ', $good['title']);
//        $good['title'] = strtolower($good['title']);

        $category = 'Watches';

        $sex = $this->parseSex($good['title']);

       // $good['title'] = preg_replace('#[\x{4e00}-\x{9fa5}]#u', ' ', $good['title']);
       // $good['title'] = preg_replace('/([\x80-\xff]*)/i', ' ', $good['title']);
		
		 $title = '';
		
        if(preg_match_all('/\w+/', $good['title'], $matches)){
			
			if(is_array($matches)){$title = implode(' ', $matches[0]);}
			//print_r($matches);
        }

        $code = substr($good['shop_id'] . '/' . $good['goods_id'], -6);

        $good['title_en'] =   'Round1 Luxury ' . $title . ' ' . $sex . ' ' . $category . ' ' . $code;

        $good['title_en'] = ucwords($good['title_en']);

        $good['formats'] = '[]';

        return $good;
    }

    public function uniqueWords($title)
    {

        $arr = explode(' ', $title);
        $arr = array_unique($arr);

        $title = implode(' ', $arr);

        return $title;
    }

    public function parseMenApparel($good){

        $this->setBrands();

    }

    public function sleeves($title){

        if(strpos($title,'短袖') !== false){

            return 'Short Sleeves';
        }
        elseif(strpos($title,'长袖') !== false){

            return 'Long Sleeves';

        }
        elseif(strpos($title,'无袖') !== false){

            return 'Sleeveless';

        }
        else{
            return '';
        }
    }

    public function clothesSize($title,$sex){

        $formats = [];
        $title = strtoupper($title);

        $size_arr_num = [];
        foreach ($this->en_num_size as $k =>$size_v){

            if(strpos($title,$size_v.'-') !== false || strpos($title,'-'.$size_v) !== false){

                $size_arr_num[] = $size_v;
            }

        }

        $size_arr_en = [];
        foreach ($this->en_size as $k =>$size_v){

            if(strpos($title,$size_v.'-') !== false || strpos($title,'-'.$size_v) !== false){

                $size_arr_en[] = $size_v;
            }
        }

        $sen_num = count($size_arr_en);
        $snum_num = count($size_arr_num);

        if($sen_num >1 || $snum_num >1){

            if($sen_num > $snum_num){
                $end = array_search($size_arr_en[$sen_num-1],$this->en_size);
                $start = array_search( $size_arr_en[0], $this->en_size);
                $stack = $this->en_size;
                $size = $size_arr_en[0] . '-' . $size_arr_en[$sen_num-1];
            }
            else{


                $end = array_search($size_arr_num[$snum_num-1],$this->en_num_size);

                $start = array_search($size_arr_num[0],$this->en_num_size);
                $stack = $this->en_num_size;
                $size = $size_arr_num[0] . '-' . $size_arr_num[$snum_num-1];
            }

            for($i=$start;$i<=$end;$i++){

                $formats[] = $stack[$i];
            }
        }
        elseif (preg_match('/\d{2}-\d{2}/', $title, $sizeArr)) {


            $size = $sizeArr[0];

            $size_arr = explode('-', $size);
            $start = (int)$size_arr[0];
            $end = (int)$size_arr[1];

            $formats = [];

            for ($i = $start; $i <= $end; $i++) {

                if ($i < 46) {
                    $formats[] = $i;
                }
            }
        }
        else{

            $formats= $sex == "Women's" ? ['S','M','L'] :['M','L','XL','2XL','3XL'];
            $size =  $sex == "Women's" ? 'S-L' : 'M-3XL';
        }

        $this->size = $size;
        $this->formats = $formats;

        return $formats;

    }

    public function glassesSize($title){

        $regx = '\d+[*]\d+[*]\d+';
    }

    public function parseWomenApparel($good){

        $this->setBrands();
        $title = $good['title'];
        $category = '';
        //$seg_list = Yii::$app->jieba->parse($good['title']);

        //$brand = $this->parseBrand($seg_list,$good['price']);
        $brand = $this->parseBrandV2($title);

        //is set

        if(strpos($title,'套装') !== false || strpos($title,'两件套')){

            if(strpos($title,'裙')!==false){

                $category = 'Top & Skirts';
            }
            elseif(strpos($title,'短裤')!==false){
                $category = 'Top & Shorts';
            }
            else{
                $category = 'Top & Pants';
            }
        }
        else{


            foreach ($this->women_category as $cat_en => $cat){

                foreach ($cat as $c_cn => $c){

                    if(strpos($title,$c_cn) !== false && !$category){
                        $category = $cat_en;
                        if($c){
                            foreach ($c as $c_k => $c_v){
                                foreach ($c_v as $v){
                                    if(strpos($title,$v) !== false){
                                        $category = $c_k;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if(!$category){
                $category = 'Maxi Dresses';
            }
        }

        $formats = [];

        if (preg_match('/\d{2}-\d{2}/',$good['title'], $sizeArr)) {

            $size = $sizeArr[0];
            $size_arr = explode('-', $size);
            $start = (int)$size_arr[0];
            $end = (int)$size_arr[1];
            for ($i = $start; $i <= $end; $i++) {
                //if ($i < ) {
                $formats[] = $i;
                //}
            }

        }
        else{

            //$size='S-L';
            $size_arr_num = [];
            foreach ($this->en_num_size as $k =>$size_v){

                if(strpos($title,$size_v) !== false){

                    $size_arr_num[] = $size_v;
                }

            }

            $size_arr_en = [];
            foreach ($this->en_size as $k =>$size_v){

                if(strpos($title,$size_v) !== false){

                    $size_arr_en[] = $size_v;
                }

            }

            $sen_num = count($size_arr_en);
            $snum_num = count($size_arr_num);

            if($sen_num >1 || $snum_num >1){

                if($sen_num > $snum_num){
                    $end = array_search($size_arr_en[$sen_num-1],$this->en_size);
                    $start = array_search( $size_arr_en[0], $this->en_size);
                    $stack = $this->en_size;
                    $size = $size_arr_en[0] . '-' . $size_arr_en[$sen_num-1];
                }
                else{
                    $end = array_search($size_arr_num[$snum_num-1],$this->en_num_size);
                    $start = array_search($size_arr_num[0],$this->en_num_size);
                    $stack = $this->en_num_size;
                    $size = $size_arr_num[0] . '-' . $size_arr_num[$snum_num-1];
                }

                for($i=$start;$i<=$end;$i++){

                    $formats[] = $stack[$i];
                }
            }
            else{

                $formats=['S','M','L'];
                $size = 'S-L';
            }

        }



        //  $category_str = implode(' ', $category);

        $good['formats'] = json_encode($formats);
        $good['cate'] = $category;
        $good['cat_id'] = @Yii::$app->shopee->women_apparel_category[$good['cate']];


        $code = substr($good['shop_id'] . '/' . $good['goods_id'], -6);

        $good['title_en'] = $this->uniqueWords('Original ' . Date('Y') . ' Latest ' . $brand . ' Women\'s ' . $category . ' Size: ' . $size . ' ' . $code . '');

        return $good;
//
//        print_r($good);



    }



}