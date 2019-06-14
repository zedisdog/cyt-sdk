<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-24
 * Time: 下午1:04
 */

namespace Dezsidog\CytSdk\Notifies;


use Carbon\Carbon;
use Dezsidog\CytSdk\Requests\OutContract;

/**
 * 通知的响应
 * Class Response
 * @package Dezsidog\CytSdk\Notifies
 */
class Response implements OutContract
{
    public $method;
    public $createUser;
    public $message;

    public function __construct(string $method, string $createUser, ?string $message = null)
    {
        $this->method = $method;
        $this->createUser = $createUser;
        $this->message = $message;
    }

    public function __toString(): string
    {
        $template = <<<DOC
<?xml version="1.0" encoding="UTF-8"?>
<qm:response xmlns:qm="http://piao.ectrip.com/2014/QMenpiaoResponseSchema"
	xsi:schemaLocation="http://piao.ectrip.com/2014/QMenpiaoRequestSchema QMRequestDataSchema-1.1.0.xsd"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
	<qm:header>
		<qm:application>tour.ectrip.com</qm:application>
		<qm:processor>DataExchangeProcessor</qm:processor>
		<qm:version>v2.0.0</qm:version>
		<qm:bodyType>%sResponseBody</qm:bodyType>
		<qm:createUser>%s</qm:createUser>
		<qm:createTime>%s</qm:createTime>
		<qm:code>1000</qm:code>
		<qm:describe>SUCCESS</qm:describe>
	</qm:header>
	<qm:body xsi:type="qm:NoticeOrderPrintSuccessResponseBody">
		<qm:message>%s</qm:message>
	</qm:body>
</qm:response>
DOC;
        return sprintf(
            $template,
            $this->method,
            $this->createUser,
            Carbon::now()->format('Y-m-d H:i:s'),
            $this->message
        );

    }
}