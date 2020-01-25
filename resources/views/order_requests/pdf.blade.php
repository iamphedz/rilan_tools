@inject ('Carbon','Carbon\Carbon')
@inject ('Brand','App\Brand')
@inject ('Product','App\Product')
<style>
    .order-request-summary {
        font-size: 70%;
    }

    .header {
        text-align: center;
    }

    span {
        font-weight: bold;
    }

    table .thead {
        background-color: #e9e9e9;
        font-weight: bold;
    }

    .tracking-no {
        position: absolute;
        right: 0;
    }

    h1 {
        text-align: center;
        margin: 50px;
    }
</style>
<div class="order-request-summary">
    <div class="copy-type">
        @guest
        REQUESTOR'S COPY<br>
        @endguest
        {{ $Carbon::now()->format("F j, Y - h:i:s A") }}
    </div>
    <div class="header">
        <p>
            <span style="font-size: 15px;">Rilan Tool Supply</span><Br>
            #32 Sauyo Road, Sauyo, Novaliches, Quezon City
            <br />Contact No.: 0938-735-7344 / 0975-434-9910
        </p>
    </div>
    <h1>Order Request Form</h1>
    @auth
    <h3>Order Status: <u>{{ $order_request->status_details->status }}</u></h3>
    @endauth
    <div class="tracking-no">
        <table>
            <tr>
                <td style="font-weight: bold;">Tracking # :</td>
                <td style="text-align: right;">{{$order_request->tracking_no}}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;"> Date :</td>
                <td style="text-align: right;">{{ $Carbon::parse($order_request->created_at)->format("F j, Y") }}</td>
            </tr>
        </table>
        <br />
    </div>
    <div class="request-info">
        <span>Requestor's Information:</span>
        <table style="margin-bottom: 20px;">
            <tr>
                <td style="font-weight: bold;">Name :</td>
                <td>{{$order_request->name}}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Address :</td>
                <td>{{$order_request->address}}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Email :</td>
                <td>{{$order_request->email}}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Contact:</td>
                <td>{{$order_request->contact_no}}</td>
            </tr>
        </table>
    </div>
    <span style="text-align: center;">Product(s) Ordered:</span>
    <table style="width: 100%;">
        <tr class="thead">
            <td style="text-align: left;">Product</td>
            <td style="text-align: left;">Brand</td>
            <td style="text-align: left;">Category</td>
            <td style="text-align: right;">Unit Price</td>
            <td style="text-align: right;">Quantity</td>
            <td style="text-align: right;">Total</td>
        </tr>
        @foreach ($product_instances as $product)
            @if ($product['instance'])
        <tr>
            @else
            <tr style="color: red;">
                @endif
            <td style="text-align: left;">{{$product['order_data']['name']}}</td>
            <td style="text-align: left;">{{ $product['instance'] ? $product['instance']->brand->brand_name : "---" }}</td>
            <td style="text-align: left;">{{$product['instance'] ? $product['instance']->category->category_name : "---"}}</td>
            <td style="text-align: right;">{{ number_format($product['order_data']['price'],2) }}</td>
            <td style="text-align: right;">{{$product['order_data']['qty']}}</td>
            <td style="text-align: right;">
                {{ number_format($product['order_data']['price']*$product['order_data']['qty'],2) }}
            </td>
        </tr>
        @endforeach

        <tr class="total">
            <td style="text-align: right; font-weight: bold;" colspan="5">Subtotal:</td>
            <td colspan="1" style="text-align: right;">{{ number_format($order_request['order_data']['sub_total'],2) }}</td>
        </tr>
        <tr class="shipping-cost" style="border: 0;">
            <td style="text-align: right; font-weight: bold;" colspan="5">Shipping Fee:</td>
            <td colspan="1" style="text-align: right;">{{ number_format($order_data['fees']['shipping'],2) }}</td>
        </tr>
        <tr class="shipping-cost" style="border: 0;">
            <td style="text-align: right; font-weight: bold;" colspan="5">Discount:</td>
            <td colspan="1" style="text-align: right;">-{{ number_format($order_data['fees']['discount'],2) }}</td>
        </tr>
        <tr class="grand-total" style="border: 0;">
            <td style="text-align: right; font-weight: bold;" colspan="5">Net Total:</td>
            <td colspan="1" style="text-align: right;">PHP {{ number_format(($order_request['order_data']['sub_total']+$order_request['order_data']['fees']['shipping'])-$order_request['order_data']['fees']['discount'],2) }}</td>
        </tr>
    </table>
    <!--<p class="order-note">
        Note:
        <br />Shipping fee will be calculated after sending the request. It will be send to you via SMS and needed to be added to the sub total of this order upon payment.
    </p>-->
</div>