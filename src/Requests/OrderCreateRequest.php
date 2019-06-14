<?php
/**
 * Created by zed
 */
declare(strict_types=1);
namespace Dezsidog\CytSdk\Requests;

use Carbon\Carbon;
use Dezsidog\CytSdk\Contracts\OutContract;
use Dezsidog\CytSdk\Requests\Order\ContactPerson;
use Dezsidog\CytSdk\Requests\Order\Product;
use Dezsidog\CytSdk\Requests\Order\VisitPerson;

/**
 * Class OrderCreateRequest
 * @package Dezsidog\CytSdk\Requests
 */
class OrderCreateRequest implements OutContract
{
    public $orderId;
    public $orderQuantity;
    public $orderPrice;
    public $orderStatus = 'PREPAY_ORDER_PRINTING';
    /**
     * @var Product
     */
    public $product;
    /**
     * @var VisitPerson
     */
    public $visitPerson;
    /**
     * @var ContactPerson
     */
    public $contactPerson;

    public function __construct(
        string $orderId,
        int $orderQuantity,
        int $orderPrice,
        int $resourceId,
        string $productName,
        Carbon $visitDate,
        int $sellPrice,
        string $contactPersonName,
        string $contactPersonCredentials,
        string $mobile,
        string $contactPersonCredentialsType,
        string $visitPersonName,
        string $visitPersonCredentials,
        string $visitPersonCredentialsType = 'ID_CARD'
    )
    {
        $this->orderId = $orderId;
        $this->orderQuantity = $orderQuantity;
        $this->orderPrice = $orderPrice;
        $this->product = new Product($resourceId, $productName, $visitDate, $sellPrice);
        $this->visitPerson = new VisitPerson($visitPersonName, $visitPersonCredentials, $visitPersonCredentialsType);
        $this->contactPerson = new ContactPerson($contactPersonName, $contactPersonCredentials, $mobile, $contactPersonCredentialsType);
    }

    public function __toString(): string
    {
        $templet = <<<DOC
<qm:body xsi:type="qm:%sRequestBody">
    <qm:orderInfo>
        <qm:orderId>%s</qm:orderId>
        <qm:orderQuantity>%d</qm:orderQuantity>
        <qm:orderPrice>%d</qm:orderPrice>
        <qm:orderStatus>%s</qm:orderStatus>
        %s
        %s
        %s
    </qm:orderInfo>
</qm:body>
DOC;

        return sprintf(
            $templet,
            'createPaymentOrder',
            $this->orderId,
            $this->orderQuantity,
            $this->orderPrice,
            $this->orderStatus,
            strval($this->product),
            strval($this->visitPerson),
            strval($this->contactPerson)
        );
    }
}