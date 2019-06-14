<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-19
 * Time: 下午7:48
 */
declare(strict_types=1);
namespace Dezsidog\CytSdk\Traits;


use Carbon\Carbon;

trait Filter
{
    /**
     * @param $item
     * @param $typeConverter
     * @return bool|Carbon|null
     * @throws \Exception
     */
    protected function filterAndConvert($item, $typeConverter)
    {
        if (is_array($item) && empty($item) && $item !== 0 || $item == '[]') {
            return null;
        }

        if ($typeConverter == 'boolval' && is_string($item) && $item) {
            return strtolower($item) == 'true' ? true : false;
        }

        if ($typeConverter == 'carbon') {
            return new Carbon($item);
        }

        return $typeConverter($item);
    }
}