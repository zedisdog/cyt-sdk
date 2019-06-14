<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-19
 * Time: 下午6:03
 */

namespace Dezsidog\CytSdk\Responses\Items;


use Dezsidog\CytSdk\Traits\Filter;
use Illuminate\Support\Collection;

/**
 * Class Product
 * @package Dezsidog\CytSdk\Items
 * @property-read   int                         $resourceId                         供应商产品 ID（唯一，不能重复）
 * @property-read   string                      $sightName                          景区名称
 * @property-read   string                      $city                               目的地
 * @property-read   string                      $productName                        产品名称2-20 字（如果包含特殊字符，需要进行转义或者用cddata进行封装）
 * @property-read   string                      $paymentType                        支付方式 PREPAY：在线支付 CASHPAY: 景区现付
 * @property-read   int                         $bookAdvanceDay                     预订限制：提前预定天数 例如 0，即当天
 * @property-read   string                      $bookAdvanceTime                    预订限制：提前预定时间 hh:mm 例如23:59 ，今日的23:59分之前可以预 订 ，同bookadvanceDay共同生效
 * @property-read   int                         $useAdvanceHour                     使用限制：预定后几小时才能入园 例如：该值为 2. 用户 8 点订票出票，最早需 10 点才能进行入园。（单位：小时）
 * @property-read   int                         $autoCancelTime                     不支付自动取消订单时间 下单后多少分钟不支付自动取消订单例如：120分钟
 * @property-read   string                      $bookPersonType                     是否需要游客信息 CONTACT_PERSON：只需要取票人信息 CONTACT_PERSON_AND_VISIT_PERSON：需要游客和取票人信息
 * @property-read   int                         $visitPersonRequiredForQuantity     每几个游客共享一个游客信息 例如：1，即每个游客都需要填写游客信息，仅bookPersonType 为CONTACT_PERSON_AND_VISIT_PERSON有效
 * @property-read   string                      $validType                          有效期限制 有效期限制：BETWEEN_USE_DATE_AND_N_DAYSAFTER：游客选定的游玩日期起_____天内有效
 * @property-read   int                         $daysAfterUseDateValid              游客选定的游玩日期起 x天内有效 整 数 ，配合validType 生效。注：1=当日
 * @property-read   Collection|CalendarPrice[]  $calendarPrices                     游玩日期集合
 * @property-read   string                      $remind                             使用说明 格式：文本 300 字以内
 * @property-read   bool                        $canRefund                          是否支持退款
 * @property-read   bool                        $canOverdueRefund                   是否支持过期退款
 * @property-read   string                      $refundApplyTimeBeforeValidEndDay   最晚有效期前几天几点可退款 格式：x_hh:mm 如：2_22:00，表示最晚有效期前 2 天22 点之前可退款。例如最晚有效期是20号，则 18 号晚上 22 点前可退。
 * @property-read   int                         $refundCharge                       退款手续费 单位：分 0 或空表示无手续费
 * @property-read   string                      $refundChargeType                   退款手续费类型 REFUND 退订手续费 OVERDUEREFUND 过期退手续费
 * @property-read   string                      $refundInfo                         退款规则 格式：文本
 * @property-read   string                      $smsTemplet                         短信模板 自定义短信内容，请填写取票方式或入园方式，80 字以内，例如：您将会在3-5 分钟内收到电子票，在景区售票处取票入园。
 * @property-read   string                      $eticketType                        电子票类型 C_ STRING：畅游通提供的串码作为电子票（需OTA支付后提供） C_CODE: 畅游通提供的二维码作为电子票（需OTA支付后提供）NO_CODE：无串码提供。
 */
class Product
{
    use Filter;
    /**
     * @var array 产品信息数组
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
        $calendarPrices = [];
        if (isset($this->data['priceConfig']['calendarPrices']['calendarPrice'][0])) {
            foreach ($this->data['priceConfig']['calendarPrices']['calendarPrice'] as $item) {
                array_push($calendarPrices, new CalendarPrice($item));
            }
        } else {
            array_push($calendarPrices, new CalendarPrice($this->data['priceConfig']['calendarPrices']['calendarPrice']));
        }
        if (class_exists(Collection::class)) {
            $calendarPrices = new Collection($calendarPrices);
        }
        $this->data['priceConfig']['calendarPrices'] = $calendarPrices;
    }

    /**
     * @param $name
     * @return bool|\Carbon\Carbon|int|null
     * @throws \Exception
     */
    public function __get($name)
    {
        switch ($name) {
            case 'resourceId':
                return $this->filterAndConvert($this->data['baseInfo']['resourceId'], 'intval');
            case 'sightName':
                return $this->filterAndConvert($this->data['baseInfo']['sights']['sight']['sightName'], 'strval');
            case 'city':
                return $this->filterAndConvert($this->data['baseInfo']['sights']['sight']['city'], 'strval');
            case 'productName':
                return $this->filterAndConvert($this->data['baseInfo']['productName'], 'strval');
            case 'paymentType':
                return $this->filterAndConvert($this->data['bookConfig']['paymentType'], 'strval');
            case 'bookAdvanceDay':
                return $this->filterAndConvert($this->data['bookConfig']['advanceOption']['bookAdvanceDay'], 'intval');
            case 'bookAdvanceTime':
                return $this->filterAndConvert($this->data['bookConfig']['advanceOption']['bookAdvanceTime'],'strval');
            case 'useAdvanceHour':
                return $this->filterAndConvert($this->data['bookConfig']['advanceOption']['useAdvanceHour'], 'intval');
            case 'autoCancelTime':
                return $this->filterAndConvert($this->data['bookConfig']['autoCancelTime'], 'intval');
            case 'bookPersonType':
                return $this->filterAndConvert($this->data['bookConfig']['bookPersonType'], 'strval');
            case 'visitPersonRequiredForQuantity':
                return $this->filterAndConvert($this->data['bookConfig']['visitPersonRequiredForQuantity'], 'intval');
            case 'validType':
                return $this->filterAndConvert($this->data['priceConfig']['validType'], 'strval');
            case 'daysAfterUseDateValid':
                return $this->filterAndConvert($this->data['priceConfig']['daysAfterUseDateValid'], 'intval');
            case 'calendarPrices':
                return $this->data['priceConfig']['calendarPrices'];
            case 'remind':
                return $this->filterAndConvert($this->data['productDescription']['remind'], 'strval');
            case 'canRefund':
                return $this->filterAndConvert($this->data['productDescription']['refundOption']['canRefund'], 'boolval');
            case 'canOverdueRefund':
                return $this->filterAndConvert($this->data['productDescription']['refundOption']['canOverdueRefund'], 'boolval');
            case 'refundApplyTimeBeforeValidEndDay':
                return $this->filterAndConvert($this->data['productDescription']['refundOption']['refundApplyTimeBeforeValidEndDay'], 'strval');
            case 'refundCharge':
                return intval($this->filterAndConvert($this->data['productDescription']['refundOption']['refundCharge'], 'intval'));
            case 'refundChargeType':
                return $this->filterAndConvert($this->data['productDescription']['refundOption']['refundChargeType'], 'strval');
            case 'refundInfo':
                return $this->filterAndConvert($this->data['productDescription']['refundOption']['refundInfo'], 'strval');
            case 'smsTemplet':
                return $this->filterAndConvert($this->data['otherConfig']['smsTemplet'], 'strval');
            case 'eticketType':
                return $this->filterAndConvert($this->data['otherConfig']['eticketType'], 'strval');
            default:
                return null;
        }
    }
}