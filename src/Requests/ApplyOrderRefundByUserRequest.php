<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-20
 * Time: 下午7:11
 */
declare(strict_types=1);
namespace Dezsidog\CytSdk\Requests;


use Dezsidog\CytSdk\Contracts\OutContract;
use Dezsidog\CytSdk\Requests\Order\VisitPerson;

class ApplyOrderRefundByUserRequest implements OutContract
{
    /**
     * @var string 订单 ID
     */
    public $partnerorderId;
    /**
     * @var string 退款流水号
     */
    public $refundSeq;
    /**
     * @var int 原始订单金额单位：分
     */
    public $orderPrice;
    /**
     * @var int 订单票数
     */
    public $orderQuantity;
    /**
     * @var int 退款票数
     */
    public $refundQuantity;
    /**
     * @var int 退款金额 单位：分
     */
    public $orderRefundPrice;
    /**
     * @var int 退款手续费 单位：分
     */
    public $orderRefundCharge;
    /**
     * @var string 退款说明
     */
    public $refundExplain;
    /**
     * @var VisitPerson 游玩人
     */
    public $visitPerson;

    public function __construct(
        string $partnerorderId,
        string $refundSeq,
        int $orderPrice,
        int $orderQuantity,
        int $refundQuantity,
        int $orderRefundPrice,
        int $orderRefundCharge,
        string $refundExplain,
        string $visitPersonName = '',
        string $visitPersonCredentials = '',
        string $visitPersonCredentialsType = 'ID_CARD'
    )
    {
        $this->partnerorderId = $partnerorderId;
        $this->refundSeq = $refundSeq;
        $this->orderPrice = $orderPrice;
        $this->orderQuantity = $orderQuantity;
        $this->refundQuantity = $refundQuantity;
        $this->orderRefundPrice = $orderRefundPrice;
        $this->orderRefundCharge = $orderRefundCharge;
        $this->refundExplain = $refundExplain;
        if ($visitPersonCredentials) {
            $this->visitPerson = new VisitPerson($visitPersonName, $visitPersonCredentials, $visitPersonCredentialsType);
        }
    }

    public function __toString(): string
    {
        $templet = <<<DOC
<qm:body xsi:type="qm:%sRequestBody">
    <qm:orderInfo>
        <qm:partnerorderId>%s</qm:partnerorderId>
        <qm:refundSeq>%s</qm:refundSeq>
        <qm:orderPrice>%d</qm:orderPrice>
        <qm:orderQuantity>%d</qm:orderQuantity>
        <qm:refundQuantity>%d</qm:refundQuantity>
        <qm:orderRefundPrice>%d</qm:orderRefundPrice>
        <qm:orderRefundCharge>%d</qm:orderRefundCharge>
        <qm:refundExplain>%s</qm:refundExplain>
        %s
    </qm:orderInfo>
</qm:body>
DOC;
        return sprintf(
            $templet,
            'applyOrderRefundByUser',
            $this->partnerorderId,
            $this->refundSeq,
            $this->orderPrice,
            $this->orderQuantity,
            $this->refundQuantity,
            $this->orderRefundPrice,
            $this->orderRefundCharge,
            $this->refundExplain,
            strval($this->visitPerson)
        );
    }
}