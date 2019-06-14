<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-19
 * Time: 下午6:19
 */
declare(strict_types=1);
namespace Dezsidog\CytSdk\Responses\Items;


use Carbon\Carbon;
use Dezsidog\CytSdk\Traits\Filter;

/**
 * Class CalendarPrice
 * @package Dezsidog\CytSdk\Items
 * @property-read   Carbon  $useDate        日期
 * @property-read   int     $marketPrice    票面价 单位：分
 * @property-read   int     $sellPrice      销售产品单价 单位：分
 * @property-read   int     $sellstock      当日库存
 */
class CalendarPrice
{
    use Filter;
    /**
     * @var array 数据
     */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param $name
     * @return bool|Carbon|null
     * @throws \Exception
     */
    public function __get($name)
    {
        switch ($name) {
            case 'useDate':
                return $this->filterAndConvert($this->data['useDate'], 'carbon');
            case 'marketPrice':
                return $this->filterAndConvert($this->data['marketPrice'], 'intval');
            case 'sellPrice':
                return $this->filterAndConvert($this->data['sellPrice'], 'intval');
            case 'sellstock':
                return $this->filterAndConvert($this->data['sellstock'], 'intval');
            default:
                return null;
        }
    }
}