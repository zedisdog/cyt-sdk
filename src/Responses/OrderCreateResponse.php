<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-20
 * Time: 下午4:01
 */
declare(strict_types=1);
namespace Dezsidog\CytSdk\Responses;


/**
 * Class OrderCreateResponse
 * @package Dezsidog\CytSdk\Responses
 * @property-read   string  $partnerorderId 畅游通订单id
 * @property-read   string  $orderStatus    订单状态
 * @property-read   string  $qrCodeStr      二维码内容
 * @property-read   string  $qrCodeUrl      二维码url
 * @property-read   string  $verifyCode     验证码
 */
class OrderCreateResponse extends BaseIn
{
    /**
     * @param $name
     * @return bool|\Carbon\Carbon|null
     * @throws \Exception
     */
    public function __get($name)
    {
        switch ($name) {
            case 'partnerorderId':
                return $this->filterAndConvert($this->get('body.orderInfo.partnerorderId'), 'strval');
            case 'orderStatus':
                return $this->filterAndConvert($this->get('body.orderInfo.orderStatus'), 'strval');
            case 'qrCodeStr':
                return $this->filterAndConvert($this->get('body.orderInfo.qrCodeStr'), 'strval');
            case 'qrCodeUrl':
                return $this->filterAndConvert($this->get('body.orderInfo.qrCodeUrl'), 'strval');
            case 'verifyCode':
                return $this->filterAndConvert($this->get('body.orderInfo.verifyCode'), 'strval');
            default:
                return null;
        }
    }
}