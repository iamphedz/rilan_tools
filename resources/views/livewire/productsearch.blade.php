<div class="absolute top-0 left-0 flex w-full -mt-3 border-yellow-400 rounded border-6">
	@if ($query && $products['total'] > 0)
	<button class="absolute inset-0 z-40 w-full h-full cursor-default" wire:click="reset"></button>
	@endif
	<div class="relative z-50 flex flex-col flex-1 border-yellow-400">
		<div class="flex items-center justify-between w-full p-1 bg-white border-2 border-transparent">
			<input type="text" wire:model="query" wire:keydown.escape="reset" class="px-2 py-1 text-sm text-left border-none rounded focus:outline-none" placeholder="Search product here..." />
			<span wire:loading class="text-gray-500">
				<icon name="spinner_4" /></span>
		</div>
		<div class="relative">
			@if (!empty($query))
			<div class="absolute right-0 z-30 flex flex-col w-full bg-white border shadow-lg">
				@if($products['total'] > 0)
				@inject ('Str', 'Illuminate\Support\Str')
				@foreach($products['data'] as $product)
				<a href="{{ url('/products/view/'.$product['id'].'-'.$Str::slug($product['product_name'])) }}" class="flex flex-row p-2 hover:bg-gray-200" wire:key="{{$product['id']}}" tabindex="-1">
					<img src="{{ url('/'.$product['image_paths'][0]) }}" class="w-16 h-16 mr-2" alt="">
					<div class="flex flex-col">
						<span class="mb-1 font-medium">{{$product['product_name'] }}</span>
						<span class="text-sm text-gray-500">{{ $product['category']['category_name']."/".$product['brand']['brand_name'] }}</span>
					</div>
				</a>
				@endforeach
				@if($products['total'] > 5)
				<a href="{{ url('/products?quick_search='.$query) }}" class="block p-2 text-sm font-medium text-center text-blue-500 hover:bg-gray-200">Show More ({{ $products['total'] }})</a>
				@endif
				@endif
			</div>
			@endif
		</div>
	</div>
</div>