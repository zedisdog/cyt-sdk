<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-20
 * Time: 下午7:00
 */
declare(strict_types=1);
namespace Dezsidog\CytSdk\Responses;

/**
 * Class SendOrderEticketResponse
 * @package Dezsidog\CytSdk\Responses
 * @property-read   bool    $success    是否成功
 */
class SendOrderEticketResponse extends BaseIn
{
    public function __get($name)
    {
        switch ($name) {
            case 'success':
                return $this->get('header.code') == '1000';
            default:
                return null;
        }
    }
}