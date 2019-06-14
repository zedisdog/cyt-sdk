<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-20
 * Time: 上午11:46
 */
declare(strict_types=1);
namespace Dezsidog\CytSdk\Requests\Order;

use Carbon\Carbon;
use Dezsidog\CytSdk\Contracts\OutContract;

/**
 * Class Product
 * @package Dezsidog\CytSdk\Requests\OrderCreate
 * @property    int     $resourceId     产品id
 * @property    string  $productName    产品名称
 * @property    Carbon  $visitDate      游玩日期 yyyy-MM-dd
 * @property    int     $sellPrice      产品卖价 单位：分
 */
class Product implements OutContract
{
    public $resourceId;
    public $productName;
    public $visitDate;
    public $sellPrice;

    public function __construct(int $resourceId, string $productName, Carbon $visitDate, int $sellPrice)
    {
        $this->resourceId = $resourceId;
        $this->productName = $productName;
        $this->visitDate = $visitDate;
        $this->sellPrice = $sellPrice;
    }

    public function __toString(): string
    {
        $templet = <<<DOC
<qm:product>
    <qm:resourceId>%d</qm:resourceId>
    <qm:productName>%s</qm:productName>
    <qm:visitDate>%s</qm:visitDate>
    <qm:sellPrice>%d</qm:sellPrice>
</qm:product>
DOC;
        return sprintf(
            $templet,
            $this->resourceId,
            $this->productName,
            $this->visitDate->format('Y-m-d'),
            $this->sellPrice
        );
    }
}