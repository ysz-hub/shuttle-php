<?php

namespace Shuttle\Packag;

class Packag
{

    static public function encode($data)
    {
        $packagConf = ['type' => 'json'];

        return self::{'encode' . $packagConf['type']}($data);
    }

    static public function decode($data)
    {
        $packagConf = ['type' => 'json'];

        return self::{'decode' . $packagConf['type']}($data);
    }


    /**
     * json 方式
     */
    public function encodeJson($data)
    {
        return json_encode($data);
    }

    /**
     * json 方式
     */
    public function decodeJson($data)
    {
        return json_decode($data, true);
    }

}