<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-10-8
 * Time: 上午10:12
 */

namespace App\Lib\CytSdk\src\Responses;


use Dezsidog\CytSdk\Responses\BaseIn;

/**
 * Class ErrorResponse
 * @package App\Lib\CytSdk\src\Responses
 * @property-read string $code 错误码
 * @property-read string $description 描述
 */
class ErrorResponse extends BaseIn
{
    /**
     * @param $name
     * @return bool|\Carbon\Carbon|null
     * @throws \Exception
     */
    public function __get($name)
    {
        switch ($name) {
            case 'code':
                return $this->filterAndConvert($this->get('header.code'), 'strval');
            case 'description':
                return $this->filterAndConvert($this->get('header.description')."|".$this->get('header.describe'), 'strval');
            default:
                return null;
        }
    }

    public function __toString()
    {
        return $this->description;
    }
}