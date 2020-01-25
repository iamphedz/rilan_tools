<div class="flex justify-between my-2 mx-6">
    <button class="add-product bg-teal-700 block font-semibold rounded text-center py-2 px-5 text-white hover:bg-teal-600">Add Product</button>
    <ul class="inline-block text-xs text-center my-auto">
        <li class="inline-block my-2 bg-teal-700 text-white p-2 rounded-l"><a href="{{ $products->previousPageUrl() }}" id="pagination_link">Prev</a></li>
        <li class="inline-block mx-2 text-gray-600">Page {{$products->currentPage()}} of {{$products->lastPage()}}</li>
        <li class="inline-block my-2 bg-teal-700 text-white p-2 rounded-r"><a href="{{ $products->nextPageUrl() }}" id="pagination_link">Next</a></li>
    </ul>
</div>
<div class="flex justify-center">
    <table class="w-full text-md bg-white shadow-md rounded mb-4">
        <tr class="border-b text-xs md:text-base">
            <th class="px-3 py-2 text-center font-normal md:font-semibold">Preview</th>
            <th class="px-3 py-2 text-center font-normal md:font-semibold">Category</th>
            <th class="px-3 py-2 text-center font-normal md:font-semibold">Brand</th>
            <th class="px-3 py-2 text-center font-normal md:font-semibold">Product Name</th>
            <th class="px-3 py-2 text-center font-normal md:font-semibold hidden md:table-cell">Description</th>
            <th class="px-3 py-2 text-center font-normal md:font-semibold">Price</th>
            <th class="px-3 py-2 text-center font-normal md:font-semibold">Stock</th>
            <th class="px-3 py-2 text-center font-normal md:font-semibold">Action</th>
        </tr>
        @foreach ($products as $product)
        <tr class="border-b">
            <td class="text-center">
                <img src="" alt="" class="w-12 h-12 md:w-16 md:h-16 mx-auto my-2">
            </td>
            <td class="text-center text-sm">{{ $product->category() }}</td>
            <td class="text-center text-sm">{{ $product->brand() }}</td>
            <td class="text-center text-sm"><a href="{{ $product->path() }}" target="_blank">{{ $product->product_name }}</a></td>
            <td class="text-center text-sm hidden md:table-cell">{{ substr($product->description,0,50) }}</td>
            <td class="text-center text-sm">&#8369; {{ number_format($product->price,2) }}</td>
            <td class="text-center text-sm">{{ $product->qty }}</td>
            <td class="text-center md:text-sm text-xs p-5">
                <button class="edit-product bg-blue-800 text-white w-full md:w-12 my-1 py-1 md:mr-2 rounded hover:bg-blue-600" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                </button>
                <button class="bg-red-800 text-white w-full md:w-12  my-1 py-1 md:mr-2 rounded hover:bg-red-600" title="Delete"><i class="fa fa-times" aria-hidden="true"></i></button>
            </td>
        </tr>
        @endforeach
    </table>
</div>