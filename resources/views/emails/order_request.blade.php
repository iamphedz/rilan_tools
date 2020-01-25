@component('mail::layout')
@inject('Brand','App\Brand')
@inject('Product','App\Product')
@inject('Carbon','Carbon\Carbon')
@slot('header')
@component('mail::header', ['url' => 'rilantools.beta'])
@endcomponent
@endslot
Dear {{ $order_request->name }},

Thank you for ordering!

Your order request has been confirmed. Total amount has been recalculated to add shipping cost. <br>
<br>
Order request will expire when payment is not done within a day after receiving this confirmation.
<br><br>
After paying, track your order in our Track Order page and send the payment details. We will verify and receive your payment first before finalizing its shipment.<br><br>
<div>
    <table>
        <tr>
            <td colspan="5">
                <p style="font-size: 90%;"><br />
                    <h1>ORT#: {{$order_request->tracking_no}}</h1>
                    Ordered on {{ $Carbon::parse($order_request->created_at)->format("F j, Y h:mm:ss a") }}
                    <br />
                    <br />
                    <b>Delivery Address:</b><br>
                    Name: {{$order_request->name}}
                    <br />
                    Email: {{$order_request->email}}
                    <br />
                    Contact No.: {{$order_request->contact_no}}
                    <br />
                    Address:
                    {{$order_request->address}}
                </p>

            </td>
        </tr>
    </table><br />
    <span style="font-weight: bold; text-align: center;">Product(s) Ordered:</span>
    <table>
        <thead>
            <th class="text-center">Product</th>
            <th class="text-right" style="text-align: right;">Price</th>
            <th class="text-right" style="text-align: right;">Quantity</th>
            <th class="text-right" style="text-align: right;">Amount</th>
        </thead>
        @foreach( $order_request->order_data['items'] as $item)
        <tr class="text-right">
            <td class="text-left" width="25%">{{ $item['product_name'] }} ({{ $Brand::find($Product::find($item['id'])->brand_id)->brand_name }})</td>
            <td width="20%" style="text-align: right;">{{ number_format($item['price'],2) }}</td>
            <td width="20%" style="text-align: right;">{{ $item['qty'] }}</td>
            <td width="20%" style="text-align: right;">
                {{ number_format($item['price']*$item['qty'],2) }}
            </td>
        </tr>
        @endforeach
        <tr class="grand-total">
            <td class="text-right font-weight-bold" style="text-align: right;" colspan="3"><b>Total:</b></td>
            <td colspan="2" class="text-right" style="text-align: right;"><b>PHP {{ number_format( $order_request['order_data']['sub_total'],2) }}</b></td>
        </tr>
    </table>
</div>

@component('mail::button', ['url' => url('/').'/track_order?tracking_no='.$order_request->tracking_no])
Track Order
@endcomponent

@slot('footer')
@component('mail::footer')
Rilan Tool Supply
<p>#32 Sauyo Road, Sauyo, Novaliches, Quezon City
    <br />Contact No.: 0938-735-7344 / 0975-434-9910</p>
@endcomponent
@endslot
@endcomponent