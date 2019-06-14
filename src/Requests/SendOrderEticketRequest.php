<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-20
 * Time: 下午6:51
 */
declare(strict_types=1);
namespace Dezsidog\CytSdk\Requests;

use Dezsidog\CytSdk\Contracts\OutContract;

/**
 * Class SendOrderEticketRequest
 * @package Dezsidog\CytSdk\Requests
 */
class SendOrderEticketRequest implements OutContract
{
    /**
     * @var string 畅游通订单号
     */
    public $partnerOrderId;
    /**
     * @var string 手机号
     */
    public $phoneNumber;

    public function __construct($partnerOrderId, $phoneNumber)
    {
        $this->partnerOrderId = $partnerOrderId;
        $this->phoneNumber = $phoneNumber;
    }

    public function __toString(): string
    {
        $templet = <<<DOC
<qm:body xsi:type="qm:%sRequestBody">
    <qm:orderInfo>
        <qm:partnerOrderId>%s</qm:partnerOrderId>
        <qm:phoneNumber>%s</qm:phoneNumber>
    </qm:orderInfo>
</qm:body>
DOC;
        return sprintf(
            $templet,
            'sendOrderEticket',
            $this->partnerOrderId,
            $this->phoneNumber
        );
    }
}