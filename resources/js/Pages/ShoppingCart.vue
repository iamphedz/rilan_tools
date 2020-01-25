<template>
  <div class="md:container flex flex-col md:flex-no-wrap w-full">
    <h1 class="block w-full text-center text-2xl md:text-4xl md:font-thin">
      Shopping Cart
    </h1>
    <div class="flex flex-col md:flex-row w-full">
      <div class="flex flex-col w-full md:w-9/12 px-2 py-4">
        <transition name="fade-down">
          <div v-if="getCartItemCount() > 0">
            <div
              class="flex flex-col md:flex-row w-full border-b py-2"
              v-for="(item, index) in cart.items"
              v-bind:key="index"
            >
              <img
                v-bind:src="item._meta.image_paths[0] | img_path"
                alt="product_image"
                class="w-20 h-20 self-center"
              />
              <div
                class="flex flex-col w-full md:w-4/12 md:ml-4 text-sm justify-center items-center md:items-start"
              >
                <a
                  v-bind:href="item._meta | link_href"
                  class="font-semibold leading-tight hover:underline"
                >
                  {{
                    (item._meta.brand.brand_code !== ""
                      ? item._meta.brand.brand_name + " "
                      : "") + item.name
                  }}
                </a>
                <div
                  class="flex flex-col w-full text-gray-600 text-xs leading-tight text-center md:text-left"
                >
                  <p>
                    {{ item._meta.description | trimString }}
                  </p>
                  <span>₱ {{ item.price | currency }}</span>
                  <span>In Stock: {{ item._meta.qty }}</span>
                </div>
              </div>
              <div
                class="flex flex-row items-center my-2 md:my-0 justify-center w-full md:w-2/12"
              >
                <button
                  @click="adjustQty('-', item.id)"
                  class="flex w-8 h-8 items-center justify-center font-semibold"
                >
                  -
                </button>
                <input
                  type="text"
                  maxlength="2"
                  readonly
                  class="h-8 w-8 text-center focus:outline-none border border-gray-400"
                  v-bind:value="item.qty"
                />
                <button
                  @click="adjustQty('+', item.id)"
                  class="flex w-8 h-8 items-center justify-center font-semibold"
                >
                  +
                </button>
              </div>
              <span
                class="flex items-center w-full my-2 md:my-0 md:w-2/12 justify-center md:justify-end font-bold"
              >
                ₱
                {{ itemAmount(item.qty, item.price) | currency }}
              </span>
              <div
                class="flex items-center justify-center md:justify-end w-full md:w-2/12"
              >
                <button
                  @click="removeFromCart(item.id)"
                  class="text-red-700 hover:underline text-sm"
                >
                  Remove
                </button>
              </div>
            </div>
          </div>
        </transition>
        <div v-if="getCartItemCount() == 0" class="text-sm text-gray-600">
          No items in cart yet.
        </div>
      </div>
      <transition name="fade-in">
        <div
          class="flex flex-col w-full md:w-3/12 px-2 py-4"
          v-if="getCartItemCount() > 0"
        >
          <div class="flex flex-col bg-gray-200 p-2">
            <span class="text-2xl font-thin p-2">Summary</span>
            <div class="flex flex-col p-2">
              <div
                class="sub-summary flex flex-col text-gray-600 leading-tight py-2 border-b"
              >
                <div class="flex flex-row w-full">
                  <span class="w-1/2">Items</span>
                  <span class="w-1/2 text-right">{{ itemCount }} pcs</span>
                </div>
                <div class="flex flex-row w-full">
                  <span class="w-1/2">Subtotal</span>
                  <span class="w-1/2 text-right"
                    >₱ {{ subTotal | currency }}</span
                  >
                </div>
              </div>
              <div
                class="flex flex-col w-full mt-8 font-semibold justify-center text-center"
              >
                <span class="w-full font-bold uppercase">Total Cost</span>
                <span class="w-full text-3xl mt-1"
                  >₱ {{ subTotal | currency }}</span
                >
              </div>
              <div class="flex flex-col md:flex-row mt-4">
                <button
                  @click="cartCheckOut"
                  class="bg-green-800 hover:bg-green-700 text-white p-3 w-full md:w-1/2"
                >
                  <span class="ml-1 uppercase">Checkout</span>
                </button>
                <button
                  @click="emptyCart"
                  class="md:ml-2 mt-2 md:mt-0 bg-red-800 hover:bg-red-700 text-white p-3 w-full md:w-1/2"
                >
                  <span class="ml-1 uppercase">Empty</span>
                </button>
              </div>
            </div>
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
      processing: false,
      cart: {}
    };
  },
  computed: {
    subTotal: function() {
      return this.cart.items.reduce((total, item) => {
        return total + item.price * item.qty;
      }, 0);
    },
    itemCount: function() {
      return this.cart.items.reduce((qtyTotal, item) => {
        return qtyTotal + item.qty;
      }, 0);
    }
  },
  mounted() {
    this.cart = this.$CookieCart.getInstance();
  },
  watch: {
    cart: {
      handler: function(oldVal, newVal) {
        this.$CookieCart.store(this.cart);
      },
      deep: true
    }
  },
  filters: {
    trimString(value) {
      let string = value;
      return string.length > 100 ? string.substr(0, 100) + "..." : string;
    }
  },
  methods: {
    cartCheckOut() {
      window.location.href =
        window.location.origin +
        "/shopping_cart/checkout/" +
        this.cart.session_id;
    },
    emptyCart() {
      if (confirm("Are you sure you want to empty out the cart?")) {
        this.cart.items = [];
      } else {
        return false;
      }
    },
    removeFromCart(id) {
      this.cart.items = this.cart.items.filter(item => {
        return item.id != id;
      });
    },
    adjustQty(action, id) {
      var itemIndex = this.cart.items.findIndex(item => item.id == id);
      var currentQty = this.cart.items[itemIndex].qty;
      if (action === "+" && currentQty < this.cart.items[itemIndex]._meta.qty) {
        this.cart.items[itemIndex].qty = currentQty + 1;
      } else if (action == "-" && currentQty > 1)
        this.cart.items[itemIndex].qty = currentQty - 1;
    },
    itemAmount(qty, price) {
      return +price * +qty;
    },
    getCartItemCount() {
      if (this.cart.items) return Object.entries(this.cart.items).length;
    }
  }
};
</script>
