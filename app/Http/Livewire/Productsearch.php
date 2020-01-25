<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Product;

class Productsearch extends Component
{
	public $query;

	public $products;

	public function mount()
	{
		$this->reset();
	}

    public function render()
    {
        return view('livewire.productsearch');
    }

    public function reset()
    {
		$this->query = "";
		$this->products = [];
    }

    public function updatedQuery()
    {
    	$this->products = Product::with(['category','brand'])->where('product_name','like','%'.$this->query.'%')->paginate(5)->toArray();
    }

}
