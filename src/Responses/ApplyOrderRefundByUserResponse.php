<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-20
 * Time: 下午7:29
 */
declare(strict_types=1);
namespace Dezsidog\CytSdk\Responses;

/**
 * Class ApplyOrderRefundByUserResponse
 * @package Dezsidog\CytSdk\Responses
 * @property-read   string  $message    消息
 * @property-read   bool    $success    是否成功
 */
class ApplyOrderRefundByUserResponse extends BaseIn
{
    /**
     * @param $name
     * @return bool|\Carbon\Carbon|null
     * @throws \Exception
     */
    public function __get($name)
    {
        switch ($name) {
            case 'message':
                return $this->filterAndConvert($this->get('body.message'), 'strval');
            case 'success':
                return $this->get('header.code') == '1000';
            default:
                return null;
        }
    }
}