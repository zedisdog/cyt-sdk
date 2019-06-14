<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-20
 * Time: 下午2:29
 */

namespace Dezsidog\CytSdk\Contracts;

/**
 * 对内数据接口
 * Interface InContract
 * @package Dezsidog\CytSdk\Responses
 */
interface InContract
{
    public function get(string $key, $default);
}