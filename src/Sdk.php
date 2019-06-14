<?php
/**
 * Created by zed.
 */
declare(strict_types=1);
namespace Dezsidog\CytSdk;


use App\Lib\CytSdk\src\Responses\ErrorResponse;
use Carbon\Carbon;
use Dezsidog\CytSdk\Contracts\InContract;
use Dezsidog\CytSdk\Contracts\OutContract;
use Dezsidog\CytSdk\Notifies\NotifyFactory;
use Dezsidog\CytSdk\Notifies\Response;
use Dezsidog\CytSdk\Requests\ApplyOrderRefundByUserRequest;
use Dezsidog\CytSdk\Requests\OrderCreateRequest;
use Dezsidog\CytSdk\Requests\OrderDetailRequest;
use Dezsidog\CytSdk\Requests\ProductRequest;
use Dezsidog\CytSdk\Requests\SendOrderEticketRequest;
use Dezsidog\CytSdk\Responses\Items\Product;
use Dezsidog\CytSdk\Responses\ApplyOrderRefundByUserResponse;
use Dezsidog\CytSdk\Responses\OrderCreateResponse;
use Dezsidog\CytSdk\Responses\OrderDetailResponse;
use Dezsidog\CytSdk\Responses\ProductResponse;
use Dezsidog\CytSdk\Responses\SendOrderEticketResponse;
use Dezsidog\CytSdk\Traits\HasLogger;
use Psr\Log\LoggerInterface;

class Sdk
{
    use HasLogger;

    /** @var string 签名类型 */
    protected $securityType = 'MD5';
    /** @var string|null api网关地址 默认是测试地址 */
    protected $url = 'http://dy.jingqu.cn/service/distributor.do';

    protected $supplierIdentity;
    protected $key;
    protected $createUser;

    /** @var bool 不报异常 */
    protected $dontReportAll = false;

    /** @var array 请求与响应对应数组 */
    protected $map = [
        ProductRequest::class => ProductResponse::class,
        OrderCreateRequest::class => OrderCreateResponse::class,
        OrderDetailRequest::class => OrderDetailResponse::class,
        SendOrderEticketRequest::class => SendOrderEticketResponse::class,
        ApplyOrderRefundByUserRequest::class => ApplyOrderRefundByUserResponse::class
    ];

    public function __construct(string $createUser, string $key, string $supplierIdentity, ?LoggerInterface $logger = null, ?string $url = null)
    {
        $this->createUser = $createUser;
        $this->key = $key;
        $this->supplierIdentity = $supplierIdentity;
        $this->setUrl($url);
        $this->setLogger($logger);
    }

    public function setUrl(?string $url): self
    {
        if ($url) {
            $this->url = $url;
        }

        return $this;
    }

    /**
     * 设置签名类型
     * @param string $securityType
     * @return $this
     */
    public function setSecurityType(string $securityType) {
        $this->securityType = $securityType;
        return $this;
    }

    /**
     * 所有错误都不抛出异常
     * @return $this
     */
    public function dontReportAll()
    {
        $this->dontReportAll = true;
        return $this;
    }

    protected function generateHeader($mhd)
    {
        $header = '<qm:header>';
        $header.= '<qm:application>tour.ectrip.com</qm:application>';
        $header.= '<qm:processor>DataExchangeProcessor</qm:processor>';
        $header.= '<qm:version>v2.0.0</qm:version>';
        $header.= '<qm:bodyType>'.$mhd.'RequestBody</qm:bodyType>';
        $header.= '<qm:createUser>'.$this->createUser.'</qm:createUser>';
        $header.= '<qm:createTime>'.date("Y-m-d H:i:s").'</qm:createTime>';
        $header.= '<qm:supplierIdentity>'.$this->supplierIdentity.'</qm:supplierIdentity>';
        $header.= '</qm:header>';
        return $header;
    }

    /**
     * @param string $mhd
     * @param OutContract $body
     * @return InContract
     * @throws \Exception
     */
    protected function request(string $mhd, OutContract $body): InContract
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<qm:request xmlns:qm="http://piao.ectrip.com/2014/QMenpiaoRequestSchema" xsi:schemaLocation="http://piao.ectrip.com/2014/QMenpiaoRequestSchema QMRequestDataSchema-1.1.0.xsd"	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $xml .= $this->generateHeader($mhd);
        $xml .= $body;
        $xml .= '</qm:request>';
        $data = base64_encode($xml);
        $signed = strtoupper(md5($this->key.$data));
        $data = urlencode($data);
        $requestParam = ['data'=>$data,'signed'=>$signed,'securityType'=>$this->securityType];

        $requestString = sprintf('method=%s&requestParam=%s', $mhd, json_encode($requestParam));

        $response = $this->post($this->url, $requestString);

        if (!$response) {
            throw new \Exception('response is null');
        }
        // 解析
        $response = json_decode($response);
        $response = base64_decode($response->data);

        $this->logger->debug('cytRequest', ["url" => $this->url, "request" => $xml, "response" => $response]);

        $response = str_replace("qm:","",$response);

        // 判错
        $responseData = json_decode(json_encode(simplexml_load_string($response)),true);
        if ($responseData['header']['code'] != '1000') {
            if ($this->dontReportAll) {
                return new ErrorResponse($response);
            } else {
                throw new \RuntimeException(sprintf('CytError: %s, %s', $responseData['header']['code'], $responseData['header']['describe']));
            }
        } else {
            $requestClass = get_class($body);
            $responseClass = $this->map[$requestClass];

            return new $responseClass($response);
        }
    }

    protected function post($url, $data) {
        //初始化
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //执行命令
        $response = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        return $response;
    }

    /**
     * @param int|null $resourceId
     * @return ErrorResponse|array|Product|ProductResponse|\Illuminate\Support\Collection|null
     * @throws \Exception
     */
    public function getProduct(?int $resourceId = null)
    {
        /**
         * @var ProductResponse|ErrorResponse $response
         */
        $response = $this->request('getProductByOTA', new ProductRequest($resourceId));

        if ($response instanceof ErrorResponse) {
            return $response;
        } else {
            if ($response->isSingle()) {
                return $response->product;
            } else {
                return $response->products;
            }
        }
    }

    /**
     * @param string $orderId
     * @param int $orderQuantity
     * @param int $orderPrice
     * @param int $resourceId
     * @param string $productName
     * @param Carbon $visitDate
     * @param int $sellPrice
     * @param string $mobile
     * @param string $visitPersonName
     * @param string $visitPersonCredentials
     * @param string $visitPersonCredentialsType
     * @param string $contactPersonName
     * @param string $contactPersonCredentials
     * @param string $contactPersonCredentialsType
     * @return InContract
     * @throws \Exception
     */
    public function createPaymentOrder(
        string $orderId,
        int $orderQuantity,
        int $orderPrice,
        int $resourceId,
        string $productName,
        Carbon $visitDate,
        int $sellPrice,
        string $mobile,
        string $visitPersonName = '',
        string $visitPersonCredentials = '',
        string $visitPersonCredentialsType = 'ID_CARD',
        string $contactPersonName = '',
        string $contactPersonCredentials = '',
        string $contactPersonCredentialsType = 'ID_CARD'
    )
    {
        return $this->request('createPaymentOrder', new OrderCreateRequest(
            $orderId,
            $orderQuantity,
            $orderPrice,
            $resourceId,
            $productName,
            $visitDate,
            $sellPrice,
            $contactPersonName,
            $contactPersonCredentials,
            $mobile,
            $contactPersonCredentialsType,
            $visitPersonName,
            $visitPersonCredentials,
            $visitPersonCredentialsType
        ));
    }

    /**
     * @param string $partnerOrderId
     * @return InContract
     * @throws \Exception
     */
    public function getOrder(string $partnerOrderId)
    {
        return $this->request('getOrderByOTA', new OrderDetailRequest($partnerOrderId));
    }

    /**
     * @param string $partnerOrderId
     * @param string $phoneNumber
     * @return InContract
     * @throws \Exception
     */
    public function sendOrderEticket(string $partnerOrderId, string $phoneNumber)
    {
        return $this->request('sendOrderEticket', new SendOrderEticketRequest($partnerOrderId, $phoneNumber));
    }

    /**
     * @param string $partnerorderId
     * @param string $refundSeq
     * @param int $orderPrice
     * @param int $orderQuantity
     * @param int $refundQuantity
     * @param int $orderRefundPrice
     * @param int $orderRefundCharge
     * @param string $refundExplain
     * @param string $visitPersonName
     * @param string $visitPersonCredentials
     * @param string $visitPersonCredentialsType
     * @return InContract
     * @throws \Exception
     */
    public function applyOrderRefundByUser(
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
        // todo: RuntimeException : CytError: 16005, 抱歉,此订单已消费,不能退订!
        $request = new ApplyOrderRefundByUserRequest(
            $partnerorderId,
            $refundSeq,
            $orderPrice,
            $orderQuantity,
            $refundQuantity,
            $orderRefundPrice,
            $orderRefundCharge,
            $refundExplain,
            $visitPersonName,
            $visitPersonCredentials,
            $visitPersonCredentialsType
        );
        return $this->request('applyOrderRefundByUser', $request);
    }

    public function parseNotice(string $method, string $requestParam)
    {
        $params = json_decode($requestParam, true);
        $data = base64_decode($params['data']);
        $data = str_replace("qm:", "", $data);
        $this->logger->info('requestXml', ['xml' => $data]);
        $notice = NotifyFactory::createByMethod($method, $data);
        return $notice;
    }

    public function noticeResponse($method)
    {
        $xml = strval(new Response($method, $this->createUser));
        $data = base64_encode($xml);
        $signed = strtoupper(md5($this->key.$data));
        $requestParam = ['data'=>$data,'signed'=>$signed,'securityType'=>$this->securityType];
        return $requestParam;
    }
}