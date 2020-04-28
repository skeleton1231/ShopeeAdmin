<?php

namespace app\components;
use yii\base\Component;

class UtilsComponent extends Component
{

//    private static $_instance;
//
//    public static function getInstance()
//    {
//        if( null == self::$_instance )
//        {
//            self::$_instance = new self();
//        }
//        return self::$_instance;
//    }



    /**
     * 按照指定数量分块
     * @datetime 2019年7月2日  下午5:50:55
     * @comment
     *
     * @param unknown $data
     * @param number $num
     * @return array
     */
    public function split( $data, $num = 5 )
    {

        $arrRet = array();
        if( !isset( $data ) || empty( $data ) )
        {
            return $arrRet;
        }

        $iCount = count( $data )/$num;
        if( !is_int( $iCount ) )
        {
            $iCount = ceil( $iCount );
        }
        else
        {
            $iCount += 1;
        }
        for( $i=0; $i<$iCount;++$i )
        {
            $arrInfos = array_slice( $data, $i*$num, $num );
            if( empty( $arrInfos ) )
            {
                continue;
            }
            $arrRet[] = $arrInfos;
            unset( $arrInfos );
        }

        return $arrRet;

    }



}

