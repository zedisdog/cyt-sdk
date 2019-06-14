<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-24
 * Time: 下午1:13
 */

namespace Dezsidog\CytSdk\Notifies;


use Dezsidog\CytSdk\Traits\Filter;
use Dezsidog\CytSdk\Responses\BaseIn;
use Dezsidog\CytSdk\Traits\HasLogger;
use Psr\Log\LoggerInterface;

/**
 * Class NoticeOrderConsumed
 * @package Dezsidog\CytSdk\Notifies
 * @property-read   string  $partnerorderId 畅游通生成的订单 ID
 * @property-read   int     $orderQuantity  原始订单总票数
 * @property-read   int     $useQuantity    累计的消费张数
 * @property-read   string  $consumeInfo    电子票消费信息
 */
class NoticeOrderConsumed extends BaseIn
{
    use Filter, HasLogger;

    public function __construct(string $raw, ?LoggerInterface $logger = null)
    {
        $this->setLogger($logger);
        parent::__construct($raw);
        $this->logger->info('Consumed', [
            'partnerorderId' => $this->partnerorderId,
            'orderQuantity' => $this->orderQuantity,
            'useQuantity' => $this->useQuantity,
            'consumeInfo' => $this->consumeInfo,
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
            case 'orderQuantity':
                return $this->filterAndConvert($this->get('body.orderInfo.orderQuantity'), 'intval');
            case 'useQuantity':
                return $this->filterAndConvert($this->get('body.orderInfo.useQuantity'), 'intval');
            case 'consumeInfo':
                return $this->filterAndConvert($this->get('body.orderInfo.consumeInfo'), 'strval');
            default:
                return null;
        }
    }
}