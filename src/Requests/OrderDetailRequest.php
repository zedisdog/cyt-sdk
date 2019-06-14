<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-20
 * Time: 下午5:54
 */
declare(strict_types=1);
namespace Dezsidog\CytSdk\Requests;


use Dezsidog\CytSdk\Contracts\OutContract;

class OrderDetailRequest implements OutContract
{
    protected $partnerOrderId;

    public function __construct(string $partnerOrderId)
    {
        $this->partnerOrderId = $partnerOrderId;
    }

    public function __toString(): string
    {
        $templet = <<<DOC
<qm:body xsi:type="qm:%sRequestBody">
    <qm:partnerOrderId>%s</qm:partnerOrderId>
</qm:body>
DOC;
        return sprintf(
            $templet,
            'getOrderByOTA',
            $this->partnerOrderId
        );

    }
}