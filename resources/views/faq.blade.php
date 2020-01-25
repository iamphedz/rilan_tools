@extends('layouts.app')
@section('title', 'FAQ - ')
@section('content')
<div class="md:container flex flex-col md:flex-no-wrap w-full">
        <span class="text-4xl font-thin mb-8 my-2 md:my-5 text-center">Frequently Asked Questions</span>
    <div class="flex flex-col rounded-lg shadow-lg w-full border mb-5">
        <div class="w-full p-2 md:p-3 text-center bg-green-900 text-white md:text-xl text-sm font-semibold rounded-t-lg">Q: I have submitted an order request. What's the first thing i need to do?</div>
        <div class="p-2 md:p-3 text-gray-700 leading-normal text-sm md:text-base text-justify">
            After submitting/placing an order request, the management will evaluate your order request and will include necessary shipping cost to its total amount. They will then send you a confirmation text/email to let you know that your order request has been processed and waiting for your confirmation.
        </div>
    </div>
    <!--
    <div id="mode_of_payment" class="flex flex-col rounded-lg shadow-lg w-full border mb-5">
        <div class="w-full p-2 md:p-3 text-center bg-green-900 text-white md:text-xl text-sm font-semibold rounded-t-lg">
            Q: Where can I pay?
        </div>
        <div class="flex flex-col p-2 md:p-3 text-gray-700 leading-normal text-sm md:text-base text-justify">
            <span>You can send the payment for your order request on the following:</span>
            <div class="flex flex-col w-full p-2 md:p-3">
                <mode-payment></mode-payment>
                <p class="pt-3">After you completed the payment, track your order in our Track Order page and send us the payment details. We will immediately confirm your payment after receiving the payment details.</p>
            </div>
        </div>
    </div>
    <div class="flex flex-col rounded-lg shadow-lg w-full border mb-5">
        <div class="w-full p-2 md:p-3 text-center bg-green-900 text-white md:text-xl text-sm font-semibold rounded-t-lg">Q: How many days before I receive my order after payment?</div>
        <div class="p-2 md:p-3 text-gray-700 leading-normal text-sm md:text-base text-justify">
            It varies on your delivery address. If you are just within NCR region, it only takes a day to deliver your order. But if you are outside NCR region, it might take up to 3-5 days.
        </div>
    </div>
    <div class="flex flex-col rounded-lg shadow-lg w-full border mb-5">
        <div class="w-full p-2 md:p-3 text-center bg-green-900 text-white md:text-xl text-sm font-semibold rounded-t-lg">Q: Can I pay anytime I want?</div>
        <div class="p-2 md:p-3 text-gray-700 leading-normal text-sm md:text-base text-justify">
            Unfortunately, NO. After the management sends you the full detail of your order request in your email, you have 2 days to pay it or else your order request will expire.
        </div>
    </div>
    <div class="flex flex-col rounded-lg shadow-lg w-full border mb-5">
        <div class="w-full p-2 md:p-3 text-center bg-green-900 text-white md:text-xl text-sm font-semibold rounded-t-lg">Q: But what if i didn't know my order request has expired but still made a payment?</div>
        <div class="p-2 md:p-3 text-gray-700 leading-normal text-sm md:text-base text-justify">
            Even if you pay after order request expiration, we still won't consider it as valid and your order request will still remain expired. We suggest to refund the payment done after your order request expiration.
        </div>
    </div>
-->
    <div class="flex flex-col rounded-lg shadow-lg w-full border mb-5">
        <div class="w-full p-2 md:p-3 text-center bg-green-900 text-white md:text-xl text-sm font-semibold rounded-t-lg">Q: Can I cancel my order request?</div>
        <div class="p-2 md:p-3 text-gray-700 leading-normal text-sm md:text-base text-justify">
            Yes, you can cancel your order request only if it's still in Pending or Confirmed status. Needless to say that order request can't be cancelled anymore if its already shipped or delivered.
        </div>
    </div>
    <div class="flex flex-col rounded-lg shadow-lg w-full border mb-5">
        <div class="w-full p-2 md:p-3 text-center bg-green-900 text-white md:text-xl text-sm font-semibold rounded-t-lg">Q: One or more of the products I ordered are damaged! Can I get a refund or a replacement for it?</div>
        <div class="p-2 md:p-3 text-gray-700 leading-normal text-sm md:text-base text-justify">
            First of all, we apologize if you received a damaged product from your order. We can refund the payment or send a replacement for that product as long as you provide us enough evidence that the product is already damaged after receiving it i.e. pictures after opening the package. Send it to our email address along with your Order Request Tracking # if you still have it. We also need you to send the damaged product/s back to us before we send the refund or replacement.
        </div>
    </div>
</div>

@endsection