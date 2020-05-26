<?php

namespace app\components;

use app\models\WegoGoodsList;
use GuzzleHttp\Client;
use Yii;
use yii\base\Component;


Class WegoComponent extends Component
{

    public $COOKIE = 'UM_distinctid=1724e9c4f872b-0822b04d5273fe-d373666-144000-1724e9c4f8831; CNZZDATA1275056938=803481028-1590455464-%7C1590455464; sajssdk_2015_cross_new_user=1; token=NjhDMjk5NkQyMUIxRjc0QUE3Qzc5MEVFMzY3REE1OUJCM0I1REFGNDBDMTZFMDJERjgyQUVERERFMDhCNTgyMEI0RUU4NkU0QkUwNUQ4QzUxMUVCNTk3MEY3NTEwRTMx; client_type=net; sensorsdata2015jssdkcross=%7B%22distinct_id%22%3A%22A201902240951350590013479%22%2C%22first_id%22%3A%221724e9c4f9b3c-0da1848b63fb94-d373666-1327104-1724e9c4f9c33%22%2C%22props%22%3A%7B%22%24latest_traffic_source_type%22%3A%22%E7%9B%B4%E6%8E%A5%E6%B5%81%E9%87%8F%22%2C%22%24latest_search_keyword%22%3A%22%E6%9C%AA%E5%8F%96%E5%88%B0%E5%80%BC_%E7%9B%B4%E6%8E%A5%E6%89%93%E5%BC%80%22%2C%22%24latest_referrer%22%3A%22%22%7D%2C%22%24device_id%22%3A%221724e9c4f9b3c-0da1848b63fb94-d373666-1327104-1724e9c4f9c33%22%7D; producte_run_to_dev_tomcat=; JSESSIONID=65CAF5A1C5C88925336EF309F81A3CB1';
    public $shopId;
    public $goodsId;
    public $search_value;
    public $start_date;
    public $end_date;
    public $time_stamp = '';
    public $url = 'https://www.szwego.com/service/album/get_album_themes_list.jsp';

    /**
     * @var string
     */
    private $queryStr;

    private function getUrl()
    {

        return $this->url . '?' . $this->queryStr;
    }

    private function execHttp($arr)
    {

        $client = new Client(['headers' => ['Cookie' => $this->COOKIE, 'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36']]);

        $this->queryStr = urldecode(http_build_query($arr));

        $response = $client->request('GET', $this->getUrl(), ['http_errors' => false]);

        if ($response->getStatusCode() != 200) {

            echo $response->getStatusCode();
            return [];
        }

        return json_decode($response->getBody(), true);

    }

    public function getDetail()
    {

        $arr = [
            'act' => 'single_item',
            'shop_id' => $this->shopId,
            'goods_id' => $this->goodsId,
            'is_spot_format' => 'true',
            'is_add_purchase' => 'true',
            '_' => '1582535567428'
        ];
        //https://www.szwego.com/service/album/get_album_themes_list.jsp?act=single_item&shop_id=A201909160222353911973&goods_id=I202002080037293810208759&is_spot_format=true&is_add_purchase=true&_=1582529110619
        $response = $this->execHttp($arr);

        usleep(50000);

        return $response['result'];
    }

    public function getList()
    {

        $arr = [
            'act' => 'single_album',
            'shop_id' => $this->shopId,
            'search_value' => $this->search_value,
            'search_image' => '',
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'tag' => '[]',
            'from_id' => '',
            'slip_type' => 1,
            'page_index' => 1,
            '_' => time() * 1000
        ];

        if ($this->time_stamp) {
            $arr['time_stamp'] = $this->time_stamp;
        }
		
		print_r($arr);exit;

        $response = $this->execHttp($arr);

        $goods_list = @$response['result']['goods_list'];

        $count = count($goods_list);

        $goods = [];

        if ($count > 0) {

            $this->time_stamp = $goods_list[$count - 1]['time_stamp'];

            foreach ($goods_list as $good) {

                $this->goodsId = $good['goods_id'];

                $g = WegoGoodsList::find()->where(['goods_id' => $good['goods_id']])->one();

                if (!$g) {

                    if ($good['imgsSrc']) {

                        $item = $this->getDetail();

                        $data = [];
                        $data['title'] = $this->filterEmoji($good['title']);
                        $data['goods_id'] = $good['goods_id'];
                        $data['shop_id'] = $good['shop_id'];
                        $data['imgsSrc'] = json_encode($good['imgsSrc']);
                        $data['time_stamp'] = $good['time_stamp'];
                        $data['price'] = @$item['price'] ? $item['price'] : 0;

                        $formats = [];
						
						if(@$item['formats']){
							
							 foreach ($item['formats'] as $ft) {

								$formats[] = $ft['formatName'];
							}
						}
						

                        $data['formats'] = json_encode($formats);

                        $goods[] = $data;

                    }
                }

            }


            Yii::$app->db->createCommand()->batchInsert('wego_goods_list', ['title', 'goods_id', 'shop_id', 'imgsSrc', 'time_stamp', 'price', 'formats'], $goods)->execute();

            $this->getList();

        } else {

            return true;
        }
    }

    // 过滤掉emoji表情
    function filterEmoji($str)
    {
        $str = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);

        $str = str_replace('\n', '', $str);
        $str = str_replace('\r', '', $str);


        return $str;
    }
}