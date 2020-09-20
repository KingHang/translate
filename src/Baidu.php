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

class Baidu implements TranslateInterface
{
    use ServiceTrait;

    protected static $language = [
        '' => 'auto',   //中文
        'auto' => 'auto',   //中文
        'zh' => 'zh',   //中文
        'hk' => 'cht',   //繁体
        'en' => 'en',   //英文
        'jp' => 'jp',   //日文
        'ko' => 'kor',  //韩文
        'fr' => 'fra',  //法语
        'ru' => 'ru',   //俄语
        'es' => 'spa',  //西班牙语
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
        $response = $this->httpClient->request('POST', $this->base_url, [
            'form_params' => $data
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
        if (is_array($result) && isset($result['error_code'])) {
            throw new TranslateException($result['error_code']);
        }

        if (is_array($result) && isset($result['trans_result'])) {
            if ($this->source) {
                return $result;
            }
            return $result['trans_result'][0]['dst'];
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
            "appid" => $this->app_id,
            "q" => $string,
            "salt" => $salt,
            "sign" => $this->getSign($string, $salt),
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
        return md5($str);
    }
}