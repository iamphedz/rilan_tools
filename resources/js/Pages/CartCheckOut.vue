<template>
  <div
    class="checkout-page container relative flex flex-col md:flex-no-wrap w-full"
    v-if="session_id === cart.session_id"
  >
    <h1 class="block w-full text-center text-2xl md:text-4xl md:font-thin">
      {{ order_saved ? "Order Request Complete" : "Cart Checkout" }}
    </h1>
    <transition name="fade-in">
      <div class="order-success flex flex-col w-full my-4" v-if="order_saved">
        <div class="flex flex-wrap flex-col">
          <div class="flex flex-col">
            <span class="mt-3">Tracking No.</span>
            <span
              class="tracking-no mb-6 text-3xl md:text-6xl font-semibold text-green-800"
              >{{ tracking_no }}</span
            >
          </div>
          <span class="my-3">Thank you for ordering!</span>
          <p class="leading-normal">
            A confirmation message will be send to you via email/text. Please
            wait for it and reply to confirm your order request.
          </p>
          <div class="flex flex-col my-3 w-full">
            <a
              v-bind:href="
                ('track_order?tracking_no=' + tracking_no) | base_path
              "
              class="check-status w-48 bg-green-800 text-white p-3 rounded hover:bg-green-700 font-semi my-2 text-center"
            >
              <icon name="file" />CHECK STATUS NOW
            </a>
          </div>
        </div>
      </div>
      <div class="delivery-form flex flex-col w-full rounded-lg my-4" v-else>
        <div class="flex md:flex-row flex-col-reverse mt-2">
          <div class="w-full md:w-7/12 mt-4 md:mt-0">
            <span class="block text-center font-semibold uppercase"
              >Delivery Form</span
            >
            <form @submit.prevent="saveOrderRequest" class="my-2">
              <inputbox
                label="Name"
                type="text"
                required="true"
                v-model="order_request.name"
                v-bind:error="errors.name"
              >
                <span slot="helper">Person, business, or company.</span>
              </inputbox>
              <inputbox
                label="Address"
                type="text"
                required="true"
                v-model="order_request.address"
                v-bind:error="errors.address"
              ></inputbox>
              <inputbox
                label="Email"
                type="email"
                required="true"
                v-model="order_request.email"
                v-bind:error="errors.email"
              ></inputbox>
              <inputbox
                label="Contact No."
                type="text"
                required="true"
                v-model="order_request.contact_no"
                v-bind:error="errors.contact_no"
              >
                <span slot="helper"
                  >Valid contact no. (It will be use for confirmation)</span
                >
              </inputbox>
              <selectbox
                label="Mode of Payment"
                required="true"
                v-model="order_request.mode_of_payment"
                v-bind:error="errors.mode_of_payment"
              >
                <option value="1" selected class="text-gray-900"
                  >Cash on Delivery (COD)</option
                >
                <option value="2" class="text-gray-900">Pay on Pickup</option>
                <option value="3" disabled>Remittance Center</option>
                <option value="4" disabled>Bank</option>
                <option value="5" disabled>eLoad (GCash, Smart Padala)</option>
              </selectbox>

              <div
                class="flex flex-col md:flex-row py-3 w-full justify-center md:justify-start mb-5"
              >
                <button
                  class="submit w-full md:w-36 mb-2 md:mb-0 text-center py-3 m:py-2 px-4 md:px-3 rounded hover:bg-green-700 bg-green-800 text-white md:mr-3 text-sm uppercase"
                >
                  Submit
                </button>
                <a
                  v-bind:href="'shopping_cart' | base_path"
                  class="back w-full md:w-36 py-3 md:py-2 px-4 md:px-3 rounded hover:bg-red-700 bg-red-800 text-white text-sm uppercase flex items-center justify-center"
                  >Back</a
                >
                <div
                  class="submit-progress p-2 mx-auto md:m-0 mt-2 md:mt-0"
                  v-if="processing"
                >
                  <i class="fa fa-spinner fa-pulse fa-fw"></i>
                  Submitting...
                </div>
              </div>
            </form>
          </div>

          <div class="w-full md:w-5/12 mt-5 md:mt-0">
            <div class="flex flex-col p-1 md:p-3" v-if="cart">
              <span class="text-center block font-semibold uppercase"
                >Order Summary</span
              >
              <div class="flex flex-col justify-center mt-5 md:px-5">
                <div
                  class="flex flex-row uppercase text-xs lg:text-sm font-semibold mb-5"
                >
                  <div class="w-3/4 text-left">Item</div>
                  <div class="w-1/4 text-right">Total</div>
                </div>
                <div
                  class="flex flex-row text-xs lg:text-sm mb-2"
                  v-for="(item, index) in cart.items"
                  v-bind:key="index"
                >
                  <div class="w-3/4 text-left">
                    <span>
                      {{
                        item._meta.brand.brand_code !== ""
                          ? item._meta.brand.brand_name
                          : ""
                      }}
                      {{ item.name }} x
                      {{ item.qty }}
                    </span>
                  </div>
                  <div class="w-1/4 text-right">
                    &#8369;
                    {{ itemAmount(item.qty, item.price) | currency }}
                  </div>
                </div>
                <div
                  class="flex flex-row uppercase text-xs lg:text-sm font-semibold border-t py-2"
                >
                  <div class="w-2/4 text-left">SUBTOTAL</div>
                  <div class="w-1/4 text-right"></div>
                  <div class="w-1/4 text-right">
                    &#8369; {{ subTotal | currency }}
                  </div>
                </div>
                <div class="flex flex-col text-xs lg:text-sm py-3">
                  <span class="mb-2">Note:</span>
                  <span class="my-1">
                    a. Final computation of order will be sent to you via
                    email/text after it has been processed. Be sure to input
                    your correct email and contact no.
                  </span>
                  <span class="my-1"
                    >b. Refresh this page if you've added a new item in the
                    cart.</span
                  >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
  <div v-else class="checkout-page md:container md:p-2">
    <h1 class="font-bold text-xl">Cart Session Mismatch.</h1>
  </div>
</template>
<script>
export default {
  data() {
    return {
      order_request: {
        name: "",
        address: "",
        contact_no: "",
        email: "",
        mode_of_payment: 1,
        order_data: {}
      },
      cart: {},
      order_saved: false,
      processing: false,
      tracking_no: "",
      errors: []
    };
  },
  props: ["session_id"],
  computed: {
    subTotal: function() {
      return this.cart.items.reduce((total, item) => {
        return total + item.price * item.qty;
      }, 0);
    },
    cartItems: function() {
      if (this.cart) {
        return this.cart;
      }
      return false;
    }
  },
  mounted() {
    if (this.$CookieCart.itemCount() > 0)
      this.cart = this.$CookieCart.getInstance();
    else window.history.back();
  },
  created() {},
  methods: {
    saveOrderRequest() {
      if (this.processing) return;
      let order_request_ = this.order_request,
        vm = this;
      order_request_.order_data = this.$CookieCart.rawData();
      this.processing = true;
      axios
        .post("/order_request", order_request_)
        .then(res => {
          console.log(res);
          if (res.data.status) {
            vm.order_saved = true;
            vm.tracking_no = res.data.order_request.tracking_no;
            vm.$CookieCart.newInstance();
          }
        })
        .catch(err => this.errorHandle(err))
        .finally(() => (this.processing = false));
    },
    itemAmount(qty, price) {
      return +price * +qty;
    },
    errorHandle(err) {
      switch (err.response.status) {
        case 422:
          console.log(err.response.data.errors);
          this.errors = err.response.data.errors;
          break;
        default:
          console.log(err);
          break;
      }
    }
  }
};
</script>
