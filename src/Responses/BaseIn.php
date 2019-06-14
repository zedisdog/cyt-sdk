<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-20
 * Time: 下午4:03
 */

namespace Dezsidog\CytSdk\Responses;

use ArrayAccess;
use Dezsidog\CytSdk\Contracts\InContract;
use Dezsidog\CytSdk\Traits\Filter;

class BaseIn implements InContract
{
    use Filter;
    /**
     * @var string 原始消息
     */
    protected $raw;
    /**
     * @var array 数组
     */
    protected $data;

    public function __construct(string $raw)
    {
        $this->raw = $raw;
        $this->data = json_decode(json_encode(simplexml_load_string($raw)), true);
    }

    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * 获取指定元素
     * @param string $key
     * @param mixed $default
     * @return string|array
     */
    public function get(string $key, $default = null)
    {
        if (!static::accessible($this->data)) {
            return value($default);
        }

        if (static::exists($this->data, $key)) {
            return $this->data[$key];
        }

        if (strpos($key, '.') === false) {
            return $this->data[$key] ?? value($default);
        }

        $array = $this->data;
        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return value($default);
            }
        }

        return $array;
    }

    /**
     * Determine whether the given value is array accessible.
     *
     * @param  mixed  $value
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|int  $key
     * @return bool
     */
    public static function exists($array, $key)
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }
}
