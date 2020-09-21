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

namespace KingHang\Translate\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use KingHang\Translate\TranslateService;

class TranslateTest extends TestCase
{
    protected $instance;

    public function setUp()
    {
        $file = dirname(__DIR__) . '/config/translate.php';
        $config = include($file);
        $this->instance = new TranslateService($config);
    }

    public function testPushManager()
    {
        $this->assertInstanceOf(TranslateService::class, $this->instance);
    }

    public function testPush()
    {
        echo PHP_EOL . "发送push 中...." . PHP_EOL;
        try {
            $result = $this->instance->translate('我爱你！');
            print_r($result);
            $this->assertEquals(
                "I love you!",
                $result
            );
        } catch (Exception $e) {
            $err = "Error : 错误：" . $e->getMessage();
            echo $err;
        }
    }
}
