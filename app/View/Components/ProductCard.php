<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductCard extends Component
{
    /**
     * Create a new component instance.
     */
    public $product;
    public $wishlistProductIds;
    public $cartProductIds;
    public function __construct($product, $wishlistProductIds = [], $cartProductIds = [])
    {
        $this->product = $product;
        $this->wishlistProductIds = $wishlistProductIds;
        $this->cartProductIds = $cartProductIds;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.product-card');
    }
}
