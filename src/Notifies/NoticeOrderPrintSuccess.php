<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-24
 * Time: 下午12:59
 */

namespace Dezsidog\CytSdk\Notifies;


use Dezsidog\CytSdk\Response\Filter;
use Dezsidog\CytSdk\Responses\BaseIn;
use Dezsidog\CytSdk\Traits\HasLogger;
use Psr\Log\LoggerInterface;

/**
 * Class NoticeOrderPrintSuccess
 * @package Dezsidog\CytSdk\Notifies
 * @property-read   string  $partnerorderId 畅游通生成的订单 ID
 * @property-read   string  $otaorderId     OTA订单 ID
 * @property-read   string  $orderStatus    订单状态 PREPAY_ORDER_PRINT_FAILED:预付,出票失败 PREPAY_ORDER_PRINT_SUCCESS:预付,出票成功
 */
class NoticeOrderPrintSuccess extends BaseIn
{
    use Filter, HasLogger;

    public function __construct(string $raw, ?LoggerInterface $logger = null)
    {
        $this->setLogger($logger);
        parent::__construct($raw);
        $this->logger->info('PrintSuccess', [
            'partnerorderId' => $this->partnerorderId,
            'otaorderId' => $this->otaorderId,
            'orderStatus' => $this->orderStatus
        ]);
    }

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
            case 'otaorderId':
                return $this->filterAndConvert($this->get('body.orderInfo.otaorderId'), 'strval');
            case 'orderStatus':
                return $this->filterAndConvert($this->get('body.orderInfo.orderStatus'), 'strval');
            default:
                return null;
        }
    }
}