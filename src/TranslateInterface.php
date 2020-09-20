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


interface TranslateInterface
{
    public function translate($string, $source = false);
}