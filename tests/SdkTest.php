<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-20
 * Time: 下午2:25
 */

namespace Dezsidog\CytSdk\tests;


use Carbon\Carbon;
use Dezsidog\CytSdk\Responses\ApplyOrderRefundByUserResponse;
use Dezsidog\CytSdk\Responses\Items\CalendarPrice;
use Dezsidog\CytSdk\Responses\Items\Product;
use Dezsidog\CytSdk\Responses\OrderCreateResponse;
use Dezsidog\CytSdk\Responses\OrderDetailResponse;
use Dezsidog\CytSdk\Responses\ProductResponse;
use Dezsidog\CytSdk\Responses\SendOrderEticketResponse;
use Dezsidog\CytSdk\Sdk;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class SdkTest extends TestCase
{
    protected $supplierIdentity = '';
    protected $key = '';
    protected $createUser = '';

    /**
     * @throws \Exception
     */
    public function testGetProducts()
    {
        $sdk = new Sdk($this->createUser, $this->key, $this->supplierIdentity);
        $response = $sdk->getProduct();
        /**
         * @var ProductResponse $response
         */
        $this->assertInstanceOf(Collection::class, $response);
        $this->assertFalse($response->isEmpty());
    }

    /**
     * @throws \Exception
     */
    public function testGetProduct()
    {
        $sdk = new Sdk($this->createUser, $this->key, $this->supplierIdentity);

        // 找所有产品
        $products = $sdk->getProduct();
        /**
         * @var ProductResponse $response
         * @var Product $product
         */
        $product = $products->first();


        $product = $sdk->getProduct($product->resourceId);
        /**
         * @var ProductResponse $response
         */
        $this->assertInstanceOf(Product::class, $product);
    }

    /**
     * @throws \Exception
     */
    public function testCreateOrder()
    {
        $sdk = new Sdk($this->createUser, $this->key, $this->supplierIdentity);

        // 找所有产品
        $products = $sdk->getProduct();
        /**
         * @var ProductResponse $response
         * @var Product $product
         */
        $product = $products->first();

        /**
         * @var CalendarPrice $cal
         */
        $cal = $product->calendarPrices->first();

        $response = $sdk->createPaymentOrder(
            Carbon::now()->format('YmdHis'),
            1,
            $cal->sellPrice * 1,
            $product->resourceId,
            $product->productName,
            $cal->useDate,
            $cal->sellPrice,
            '15281009123'// ,
//            '张哲',
//            '510184199011160039',
//            'ID_CARD',
//            '张哲',
//            '510184199011160039'
        );
        /**
         * @var OrderCreateResponse $response
         */
        $this->assertNotEmpty($response);
        $this->assertNotEmpty($response->orderStatus);
        $this->assertNotEmpty($response->partnerorderId);
        $this->assertNotEmpty($response->qrCodeStr);
        $this->assertNotEmpty($response->qrCodeUrl);
        $this->assertNotEmpty($response->verifyCode);
    }

    /**
     * @throws \Exception
     */
    public function testOrderDetail()
    {
        $sdk = new Sdk($this->createUser, $this->key, $this->supplierIdentity);

        // 找所有产品
        $products = $sdk->getProduct();
        /**
         * @var ProductResponse $response
         * @var Product $product
         */
        $product = $products->first();

        /**
         * @var CalendarPrice $cal
         */
        $cal = $product->calendarPrices->first();

        // 创建订单
        /**
         * @var OrderCreateResponse $order
         */
        $order = $sdk->createPaymentOrder(
            Carbon::now()->format('YmdHis'), 1, $cal->sellPrice * 1, $product->resourceId, $product->productName, $cal->useDate, $cal->sellPrice, '15281009123', '张哲', '510184199011160039', 'ID_CARD', '张哲', '510184199011160039'
        );

        /**
         * @var OrderDetailResponse $response
         */
        $response = $sdk->getOrder($order->partnerorderId);
        $this->assertNotEmpty($response->partnerorderId);
        $this->assertNotEmpty($response->orderStatus);
        $this->assertNotEmpty($response->orderQuantity);
        $this->assertNotEmpty($response->eticketNo);
        $this->assertEquals(0,$response->useQuantity);
        $this->assertNull($response->consumeInfo);
    }

    /**
     * @throws \Exception
     */
    public function testSendTicket()
    {
        $sdk = new Sdk($this->createUser, $this->key, $this->supplierIdentity);

        // 找所有产品
        $products = $sdk->getProduct();
        /**
         * @var ProductResponse $response
         * @var Product $product
         */
        $product = $products->first();

        /**
         * @var CalendarPrice $cal
         */
        $cal = $product->calendarPrices->first();

        // 创建订单
        /**
         * @var OrderCreateResponse $order
         */
        $order = $sdk->createPaymentOrder(
            Carbon::now()->format('YmdHis'), 1, $cal->sellPrice * 1, $product->resourceId, $product->productName, $cal->useDate, $cal->sellPrice, '15281009123', '张哲', '510184199011160039', 'ID_CARD', '张哲', '510184199011160039'
        );

        /**
         * @var SendOrderEticketResponse $response
         */
        $response = $sdk->sendOrderEticket($order->partnerorderId, '15281009123');
        $this->assertTrue($response->success);
    }

    /**
     * @throws \Exception
     */
    public function testApplyOrderRefundByUser()
    {
        $sdk = new Sdk($this->createUser, $this->key, $this->supplierIdentity);

        // 找所有产品
        $products = $sdk->getProduct();
        /**
         * @var ProductResponse $response
         * @var Product $product
         */
        $product = $products->first();

        /**
         * @var CalendarPrice $cal
         */
        $cal = $product->calendarPrices->first();

        // 创建订单
        /**
         * @var OrderCreateResponse $order
         */
        $order = $sdk->createPaymentOrder(
            Carbon::now()->format('YmdHis'),
            1,
            $cal->sellPrice * 1,
            $product->resourceId,
            $product->productName,
            $cal->useDate,
            $cal->sellPrice,
            '15281009123'/*,
            '张哲',
            '510184199011160039',
            'ID_CARD',
            '张哲',
            '510184199011160039'*/
        );

        /**
         * @var ApplyOrderRefundByUserResponse $response
         */
        $response = $sdk->applyOrderRefundByUser(
            $order->partnerorderId,
            Carbon::now()->format('YmdHis'),
            $cal->sellPrice * 1,
            1,
            1,
            $cal->sellPrice * 1,
            0,
            'testExplain'/*,
            '张哲',
            '510184199011160039'*/
        );

        $this->assertNotEmpty($response->message);
        $this->assertTrue($response->success);
    }
}