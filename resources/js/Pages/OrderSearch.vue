<template>
  <div class="container relative flex flex-col md:flex-no-wrap w-full">
    <transition name="fade-in">
      <payment-details
        id="payment_modal"
        v-show="paymentDetails"
        @close="paymentDetails = false"
      ></payment-details>
    </transition>

    <h1 class="block w-full text-center text-2xl md:text-4xl md:font-thin">
      Track Order
    </h1>

    <div class="flex flex-col relative my-4">
      <div class="flex md:flex-row flex-col mb-5 w-full">
        <form
          @submit.prevent="searchOrderRequest"
          class="flex flex-col md:flex-row md:m-0 m-auto"
        >
          <div class="flex flex-row">
            <input
              class="px-5 py-2 border rounded-l focus:border-green-700 bg-gray-200 text-xs focus:bg-white focus:outline-none focus:text-green-700"
              type="text"
              v-model="search.tracking_no"
              placeholder="Tracking Number"
            />
            <button
              class="search focus:outline-none bg-green-900 px-5 rounded-r hover:bg-green-800 text-white text-xs"
            >
              Search
            </button>
          </div>
        </form>
        <div class="p-1 flex my-2 md:mx-2 m-auto md:m-0 items-center">
          <span
            class="search-msg w-full md:text-left text-gray-500 text-sm"
            v-if="searching"
          >
            <i class="fa fa-spinner fa-spin fa-3x fa-fw text-sm"></i
            >Searching...
          </span>
          <span
            class="search-msg w-full md:text-left text-gray-500 text-sm"
            v-if="!searching"
            >{{ search_msg }}</span
          >
        </div>
      </div>
      <transition name="fade-in">
        <div
          class="flex flex-col my-10 md:my-2 justify-center"
          v-if="order_found"
        >
          <div
            class="shadow w-full bg-white rounded p-2 flex flex flex-col md:flex-row md:justify-between justify-start border mt-2"
          >
            <div class="flex flex-col leading-relaxed">
              <span class="font-semibold md:text-base text-lg"
                >ORT # {{ order_request.tracking_no }}</span
              >
              <span class="text-gray-500 text-xs"
                >Ordered on
                {{
                  order_request.created_at
                    | formatDate("dddd, MMMM DD, YYYY h:mm:ss a")
                }}</span
              >
            </div>
            <div class="py-1 md:p-2 flex flex-row items-center text-xs">
              <span class="text-gray-500 font-semibold mr-2">TOTAL:</span>
              <span class="font-semibold text-base md:text-xl"
                >&#8369; {{ finalTotal | currency }}</span
              >
            </div>
          </div>
          <div class="flex flex-col md:flex-row w-full my-2">
            <div
              class="flex flex-col border w-full md:w-8/12 p-3 bg-white rounded shadow-lg"
            >
              <span
                class="w-full border-b-2 border-teal-300 text-teal-800 font-semibold py-2"
                >Ordered Item(s)</span
              >
              <div class="flex flex-col mt-5">
                <div
                  class="flex flex-row mb-2 p-2 bg-gray-100 w-full rounded-lg shadow"
                  v-for="product in product_instances"
                  v-bind:key="product.order_data.id"
                  v-bind:class="{
                    'text-red-600': !product.instance
                  }"
                >
                  <img
                    v-bind:src="
                      product.instance
                        ? product.instance.image_paths[0]
                        : '/images/products/default.jpg' | img_path
                    "
                    alt
                    class="md:h-auto h-20 object-cover rounded border w-20 md:w-2/12"
                  />
                  <div class="flex flex-col text-xs md:text-sm ml-2 w-5/12">
                    <a
                      target="_blank"
                      v-bind:href="product.instance | link_href"
                    >
                      <span class="text-base font-semibold">
                        {{
                          product.instance
                            ? product.instance.brand.brand_code !== ""
                              ? product.instance.brand.brand_name + " "
                              : ""
                            : ""
                        }}
                        {{ product.order_data.name }}
                      </span>
                    </a>
                    <div class="flex flex-col text-gray-600 my-1 leading-tight">
                      <p>
                        {{
                          product.instance
                            ? product.instance.description
                            : "" | trimString
                        }}
                      </p>
                      <span
                        >&#8369; {{ product.order_data.price | currency }}</span
                      >
                    </div>
                  </div>
                  <div class="flex w-2/12 justify-center text-sm">
                    <span class="mr-2">Qty:</span>
                    <span>{{ product.order_data.qty }}</span>
                  </div>
                  <span class="w-3/12 text-right"
                    >&#8369;
                    {{
                      (product.order_data.qty * product.order_data.price)
                        | currency
                    }}</span
                  >
                </div>
              </div>
            </div>
            <div class="flex flex-col w-full md:w-4/12 md:ml-3 mt-2 md:mt-0">
              <div
                class="p-3 bg-white border w-full flex flex-col rounded shadow-sm text-gray-700 shadow-lg"
              >
                <span
                  class="w-full border-b-2 border-teal-300 text-teal-800 font-semibold py-2"
                  >Status :
                  {{
                    order_request.has_expired
                      ? "Expired"
                      : order_request.status_details.status
                  }}</span
                >
                <div class="text-sm">
                  <p class="py-2 leading-snug text-justify">
                    {{
                      order_request.has_expired
                        ? "Order request have expired. Please place a new order again."
                        : order_request.status_details.reminders
                    }}
                  </p>
                </div>
                <span
                  class="w-full border-b-2 border-teal-300 text-teal-800 font-semibold py-2"
                  >Order Summary</span
                >
                <div class="text-sm">
                  <div class="flex flex-row mb-1 mt-3 w-full">
                    <span class="w-48">Items</span>
                    <span class="w-full text-right">{{ itemCount }} pcs</span>
                  </div>
                  <div class="flex flex-row my-1 w-full">
                    <span class="w-48">Subtotal</span>
                    <span class="w-full text-right">{{
                      order_request.order_data.sub_total | currency
                    }}</span>
                  </div>
                  <div class="flex flex-row my-1 w-full">
                    <span class="w-48">Shipping Fee</span>
                    <span class="w-full text-right">{{
                      order_request.order_data.fees.shipping | currency
                    }}</span>
                  </div>
                  <div
                    class="flex flex-row my-1 w-full"
                    v-if="order_request.order_data.fees.discount > 0"
                  >
                    <span class="w-48">Discount</span>
                    <span class="w-full text-right"
                      >-{{
                        order_request.order_data.fees.discount | currency
                      }}</span
                    >
                  </div>
                  <div class="flex flex-row my-1 w-full font-semibold">
                    <span class="w-48">Total</span>
                    <span class="w-full text-right">
                      <i
                        class="fas fa-exclamation-circle text-teal-800 cursor-pointer"
                        title="Amount is not final until shipment fee is given."
                      ></i>
                      &#8369;
                      {{ finalTotal | currency }}
                    </span>
                  </div>
                  <div class="flex flex-row my-1 w-full justify-end">
                    <span
                      v-if="
                        order_request.status > 2 && !order_request.has_expired
                      "
                      class="text-teal-700 cursor-pointer"
                      @click="handlePaymentEvent"
                      >Payment Details</span
                    >
                  </div>
                </div>
                <span
                  class="w-full border-b-2 border-teal-300 text-teal-800 font-semibold py-2"
                  >Requestor's Information</span
                >
                <div class="text-sm mb-3">
                  <div class="flex flex-row my-1 mt-3">
                    <span class="w-24">Name:</span>
                    <span class="w-full">{{ order_request.name }}</span>
                  </div>
                  <div class="flex flex-row my-1">
                    <span class="w-24">Address:</span>
                    <span class="w-full">{{ order_request.address }}</span>
                  </div>
                  <div class="flex flex-row my-1">
                    <span class="w-24">Email:</span>
                    <span class="w-full">{{ order_request.email }}</span>
                  </div>
                  <div class="flex flex-row my-1">
                    <span class="w-24">Contact:</span>
                    <span class="w-full">{{ order_request.contact_no }}</span>
                  </div>
                </div>
                <div
                  v-show="!order_request.has_expired"
                  class="flex justify-center text-xs lg:text-base"
                >
                  <button
                    @click="printCopy"
                    class="bg-green-800 text-white p-3 w-48 rounded-l flex items-center justify-center mr-2 md:mr-0 flex-row focus:outline-none border-r border-transparent"
                    v-bind:class="{
                      'disabled-button border-gray-400':
                        order_request.status === 5,
                      'hover:bg-green-700': [1, 2].includes(
                        order_request.status
                      )
                    }"
                  >
                    <i class="fa fa-print" aria-hidden="true"></i>
                    <span class="ml-2">PRINT</span>
                  </button>
                  <button
                    class="bg-red-800 text-white p-3 rounded-r flex items-center justify-center flex-row w-56 focus:outline-none"
                    @click="cancelOrder(order_request.id)"
                    v-bind:class="{
                      'disabled-button': order_request.status > 2,
                      'hover:bg-red-700': order_request.status <= 2
                    }"
                  >
                    <i class="fa fa-times" aria-hidden="true"></i>
                    <span class="ml-2">CANCEL</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
      <transition name="fade-in">
        <div
          class="flex flex-col w-full items-center text-sm text-gray-500"
          v-if="!order_found"
        >
          <div class="flex flex-col w-full md:w-3/12 items-center">
            <img
              v-bind:src="'/images/track_order.svg' | img_path"
              alt=""
              class="mb-2 opacity-50"
            />
            <span class="text-center"
              >Check your order request status and its other details using its
              tracking no.</span
            >
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      search: {
        tracking_no: ""
      },
      order_request: [],
      product_instances: [],
      searching: false,
      search_msg: "",
      order_found: false,
      paymentDetails: false
    };
  },
  props: {
    order_request_: Object
  },
  computed: {
    subTotal: function() {
      let orderItems = Object.values(this.product_instances);
      return orderItems.reduce((total, item) => {
        return total + item.order_data.price * item.order_data.qty;
      }, 0);
    },
    itemCount: function() {
      let orderItems = Object.values(this.product_instances);
      return orderItems.reduce((total, item) => {
        return total + item.order_data.qty;
      }, 0);
    },
    finalTotal: function() {
      let subTotal = Object.values(this.product_instances).reduce(
        (total, item) => {
          return total + item.order_data.price * item.order_data.qty;
        },
        0
      );
      let fees = this.order_request.order_data.fees;
      return subTotal + fees.shipping + -fees.discount;
    }
  },
  mounted() {
    let tn = this.getURLparam("tracking_no");
    if (tn) {
      this.search.tracking_no = tn ? tn : "";
      this.searchOrderRequest();
    }
  },
  methods: {
    handlePaymentEvent() {
      window.scrollTo(0, 0);
      this.paymentDetails = true;
      this.$eventBus.$emit(
        "payment_details",
        this.order_request.id,
        this.order_request.tracking_no
      );
    },
    getOrderStatus: function(status) {
      return {
        "w-1/4": status === 3,
        "w-1/2": status === 4,
        "w-3/4": status === 5,
        "w-full": status === 6
      };
    },
    statusDone(status, point = 0) {
      if (status > point) return true;
      else false;
    },
    getURLparam(param) {
      var url_string = window.location.href;
      var url = new URL(url_string);
      return url.searchParams.get(param);
    },
    searchOrderRequest() {
      if (!this.search.tracking_no) return false;

      this.searching = true;
      fetch("/api/order_request/track_order", {
        method: "post",
        body: JSON.stringify(this.search),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(res => {
          console.log(res);
          if (res) {
            this.order_request = res.order_request;
            this.product_instances = res.product_instances;
            this.order_found = true;
            this.search_msg = "Order request found.";
          } else {
            this.order_found = false;
            this.search_msg = "Order request not found.";
          }

          this.searching = false;
        })
        .catch(err => console.log(err));
    },
    printCopy() {
      if (
        ![5].includes(this.order_request.status) &&
        !this.order_request.has_expired
      ) {
        window.location.href =
          window.location.origin +
          "/order_request/print/" +
          this.search.tracking_no;
      }
      return false;
    },
    cancelOrder(id) {
      if (this.order_request.status <= 2 && !this.order_request.has_expired) {
        if (confirm("Are you sure you want to cancel this request?")) {
          fetch(`/api/order_request/${id}`, {
            method: "delete"
          })
            .then(res => res.json())
            .then(data => {
              alert(data);
              this.order_found = false;
              this.search.tracking_no = "";
              this.search_msg = "";
            });
        } else {
          return false;
        }
      }
      return false;
    }
  },
  filters: {
    trimString(value) {
      let string = value;
      return string.length > 100 ? string.substr(0, 100) + "..." : string;
    }
  }
};
</script>
