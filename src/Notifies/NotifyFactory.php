<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-24
 * Time: 下午1:11
 */

declare(strict_types=1);
namespace Dezsidog\CytSdk\Notifies;

use Psr\Log\LoggerInterface;

class NotifyFactory
{
    public static function createByMethod($method, $data, ?LoggerInterface $logger = null)
    {
        switch (strtolower($method)) {
            case 'noticeorderprintsuccess':
                return new NoticeOrderPrintSuccess($data, $logger);
            case 'noticeorderconsumed':
                return new NoticeOrderConsumed($data, $logger);
            case 'noticeorderrefundapproveresult':
                return new NoticeOrderRefundApproveResult($data, $logger);
            default:
                throw new \RuntimeException(sprintf('unsupport notify: %s', $method));
        }
    }
}