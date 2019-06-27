<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-20
 * Time: 下午2:14
 */
declare(strict_types=1);
namespace Dezsidog\CytSdk\Requests;


use Dezsidog\CytSdk\Contracts\OutContract;

/**
 * Class ProductRequest
 * @package App\Lib\CytSdk\src\Requests
 */
class ProductRequest implements OutContract
{
    /**
     * @var string 方法
     */
    public $method = 'ALL';
    /**
     * @var int 当前页数
     */
    public $currentPage = 1;
    /**
     * @var int 每页大小
     */
    public $pageSize = 100;
    /**
     * @var int 产品id
     */
    public $resourceId = '0';

    public function __construct($resourceId = null)
    {
        if ($resourceId) {
            $this->resourceId = $resourceId;
            $this->method = 'SINGLE';
        }
    }

    /**
     * 设置当前页数
     * @param int $currentPage
     * @return $this
     */
    public function currentPage($currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * 设置每页大小
     * @param int $pageSize
     * @return $this
     */
    public function pageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    public function __toString(): string
    {
        $templet = <<<DOC
<qm:body xsi:type="qm:%sRequestBody">
    <qm:method>%s</qm:method>
    <qm:currentPage>%d</qm:currentPage>
    <qm:pageSize>%d</qm:pageSize>
    <qm:resourceId>%s</qm:resourceId>
</qm:body>
DOC;

        return sprintf(
            $templet,
            'getProductByOTA',
            $this->method,
            $this->currentPage,
            $this->pageSize,
            strval($this->resourceId)
        );
    }
}