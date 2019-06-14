<?php
/**
 * Created by zed.
 */

declare(strict_types=1);
namespace Dezsidog\CytSdk\Traits;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

trait HasLogger
{
    /** @var LoggerInterface */
    protected $logger;

    public function setLogger(?LoggerInterface $logger): self
    {
        if ($logger) {
            $this->logger = $logger;
        } else {
            $this->logger = new Logger('oauth');
            $this->logger->pushHandler(new StreamHandler('php://stderr'));
        }
        return $this;
    }
}