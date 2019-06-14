<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-24
 * Time: 下午1:20
 */

namespace Dezsidog\CytSdk\Notifies;


use Dezsidog\CytSdk\Response\Filter;
use Dezsidog\CytSdk\Responses\BaseIn;
use Dezsidog\CytSdk\Traits\HasLogger;
use Psr\Log\LoggerInterface;

/**
 * Class noticeOrderRefundApproveResult
 * @package Dezsidog\CytSdk\Notifies
 * @property-read   string  $partnerorderId     畅游通生成的订单 ID
 * @property-read   string  $refundSeq          退款流水号，用于标记每一笔退款，由OTA定义
 * @property-read   int     $orderQuantity      原始订单票数
 * @property-read   string  $refundResult       退款审核结果 APPROVE：同意退款 REJECT：拒绝退款
 * @property-read   int     $refundQuantity     退款票数
 * @property-read   int     $orderRefundPrice   退款金额
 * @property-read   int     $orderRefundCharge  退款手续费
 * @property-read   bool    $isApprove          是否同意
 */
class NoticeOrderRefundApproveResult extends BaseIn
{
    use Filter, HasLogger;

    public function __construct(string $raw, ?LoggerInterface $logger = null)
    {
        $this->setLogger($logger);
        parent::__construct($raw);
        $this->logger->info('RefundApproveResult', [
            'partnerorderId' => $this->partnerorderId,
            'refundSeq' => $this->refundSeq,
            'orderQuantity' => $this->orderQuantity,
            'refundResult' => $this->refundResult,
            'refundQuantity' => $this->refundQuantity,
            'orderRefundPrice' => $this->orderRefundPrice,
            'orderRefundCharge' => $this->orderRefundCharge,
            'isApprove' => $this->isApprove
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
            case 'refundSeq':
                return $this->filterAndConvert($this->get('body.orderInfo.refundSeq'), 'strval');
            case 'orderQuantity':
                return $this->filterAndConvert($this->get('body.orderInfo.orderQuantity'), 'intval');
            case 'refundResult':
                return $this->filterAndConvert($this->get('body.orderInfo.refundResult'), 'strval');
            case 'refundQuantity':
                return $this->filterAndConvert($this->get('body.orderInfo.refundQuantity'), 'intval');
            case 'orderRefundPrice':
                return $this->filterAndConvert($this->get('body.orderInfo.orderRefundPrice'), 'intval');
            case 'orderRefundCharge':
                return $this->filterAndConvert($this->get('body.orderInfo.orderRefundCharge'), 'intval');
            case 'isApprove':
                return $this->refundResult == 'APPROVE';
            default:
                return null;
        }
    }
}