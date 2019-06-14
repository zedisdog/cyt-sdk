<?php
/**
 * Created by PhpStorm.
 * User: zed
 * Date: 18-9-19
 * Time: 下午5:56
 */
declare(strict_types = 1);
namespace Dezsidog\CytSdk\Responses;



use Dezsidog\CytSdk\Responses\Items\Product;
use Illuminate\Support\Collection;

/**
 * Class ProductResponse
 * @package Dezsidog\CytSdk\Responses
 */
class ProductResponse extends BaseIn
{
    /**
     * @var Product|null
     */
    public $product = null;

    /**
     * @var array|Collection|null
     */
    public $products = null;

    protected $single = false;

    protected $empty = false;

    public function __construct(string $raw)
    {
        parent::__construct($raw);
        if (intval($this->get('body.count')) == 1) {
            $this->single = true;
            $this->product = new Product($this->get('body.productInfos.productInfo'));
        } else if(intval($this->get('body.count')) > 1){
            $this->products = $this->wrapperProducts();
        } else {
            $this->empty = true;
        }
    }

    public function isEmpty()
    {
        return $this->empty;
    }

    public function isSingle()
    {
        return $this->single;
    }

    protected function wrapperProducts()
    {
        $products = [];
        foreach ($this->get('body.productInfos.productInfo', []) as $item) {
            array_push($products, new Product($item));
        }

        if (class_exists(Collection::class)) {
            $products = new Collection($products);
        }

        return $products;
    }
}