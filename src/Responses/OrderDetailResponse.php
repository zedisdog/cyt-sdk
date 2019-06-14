<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-20
 * Time: 下午6:10
 */
declare(strict_types=1);
namespace Dezsidog\CytSdk\Responses;



/**
 * Class OrderDetailResponse
 * @package Dezsidog\CytSdk\Responses
 * @property-read   string  $partnerorderId 畅游通订单id
 * @property-read   string  $orderStatus    订单状态
 * @property-read   int     $orderQuantity  订单票数
 * @property-read   string  $eticketNo      电子票号 订单号[20180920999777010],密码[19901116],二维码[http://dy.jingqu.cn/z/31s_rhga.do],二维码字符串[CYT_3d838f96c23000b0c858e31f830bbbdc0ca1f353faaee02a,0,c54a394fd5ec4ffc1b68d1522efc1846]
 * @property-read   bool    $eticketSended  电子票发送状态 是否已发送
 * @property-read   int     $useQuantity    已消费票数
 * @property-read   string  $consumeInfo    消费信息 备注
 */
class OrderDetailResponse extends BaseIn
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
            case 'orderQuantity':
                return $this->filterAndConvert($this->get('body.orderInfo.orderQuantity'), 'intval');
            case 'eticketNo':
                return $this->filterAndConvert($this->get('body.orderInfo.eticketNo'), 'strval');
            case 'eticketSended':
                return $this->filterAndConvert($this->get('body.orderInfo.eticketSended'), 'bool');
            case 'useQuantity':
                return $this->filterAndConvert($this->get('body.orderInfo.useQuantity'), 'intval');
            case 'consumeInfo':
                return $this->filterAndConvert($this->get('body.orderInfo.consumeInfo'), 'strval');
            default:
                return null;
        }
    }
}