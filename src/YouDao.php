<?php
// +----------------------------------------------------------------------
// | NicePHP [ NICE TO MEET YOU ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2019 http://wanghang.fun All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: king <863390956@qq.com>
// +----------------------------------------------------------------------


namespace KingHang\Translate;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use KingHang\Translate\Exceptions\TranslateException;
use KingHang\Translate\Traits\ServiceTrait;

class YouDao implements TranslateInterface
{
    use ServiceTrait;

    protected static $language = [
        '' => 'auto',   //中文
        'auto' => 'auto',   //中文
        'zh' => 'zh-CHS',   //中文
        'hk' => 'zh-CH',   //有道不支持繁体
        'en' => 'en',   //英文
        'jp' => 'ja',   //日文
        'ko' => 'ko',  //韩文
        'fr' => 'fr',  //法语
        'ru' => 'ru',   //俄语
        'es' => 'es',  //西班牙语
        'pt' => 'pt',  //葡萄牙语
    ];

    /**
     * @param $string
     * @param bool $source 返回原数据结构
     * @return mixed
     * @throws TranslateException
     * @throws GuzzleException
     */
    public function translate($string, $source = false)
    {
        $this->source = $source;
        $this->httpClient = new Client($this->options); // Create HTTP client
        $data = $this->getData($string);
        $response = $this->httpClient->request('GET', $this->base_url, [
            'query' => $data
        ]);
        $result = json_decode($response->getBody(), true);
        return $this->response($result);
    }

    /**
     * @param $result
     * @return mixed
     * @throws TranslateException
     */
    private function response($result)
    {
        if (is_array($result) && $result['errorCode'] != 0) {
            throw new TranslateException($result['errorCode']);
        }

        if (is_array($result) && isset($result['translation'])) {
            if ($this->source) {
                return $result;
            }
            return $result['translation'];
        }

        throw new TranslateException(10003);
    }

    /**
     * @param $string
     * @return array
     */
    private function getData($string)
    {
        $salt = time();
        return [
            "from" => $this->from,
            "to" => $this->to,
            "appKey" => $this->app_id,
            "q" => $string,
            "salt" => $salt,
            "sign" => $this->getSign($string, $salt),
            "signType" => "v3",
            "curtime" => $salt
        ];
    }

    /**
     * @param $string
     * @param $time
     * @return string
     */
    private function getSign($string, $time)
    {
        $str = $this->app_id . $string . $time . $this->app_key;
        return hash('sha256', $str);
    }
}