<template>
  <div
    class="flex md:flex-row flex-col w-full items-center"
    v-if="product.qty > 0"
  >
    <label for="qty" class="font-semibold block p-3">Qty</label>
    <div
      class="flex flex-row items-center my-2 md:my-0 justify-center w-full md:w-2/12"
    >
      <button
        @click="adjustQty('-')"
        class="flex w-8 h-8 items-center justify-center font-semibold"
      >
        -
      </button>
      <input
        type="text"
        readonly
        v-model="qty"
        class="h-8 w-8 text-center focus:outline-none"
      />
      <button
        @click="adjustQty('+')"
        class="flex w-8 h-8 items-center justify-center font-semibold"
      >
        +
      </button>
    </div>
    <button
      @click="productToCartEvent"
      v-bind:class="{
        'bg-red-700 hover:bg-red-700': productInCart,
        'bg-green-900 hover:bg-green-800': !productInCart
      }"
      class="add-cart focus:outline-none text-gray-200 md:py-3 md:px-5 mt-3 md:m-0 p-3 md:ml-3 rounded text-sm md:text-base"
    >
      <span v-if="!productInCart">ADD TO CART</span>
      <span v-else>REMOVE FROM CART</span>
    </button>
    <i
      v-if="processing"
      class="fa fa-spinner fa-spin fa-3x fa-fw text-base ml-2 text-gray-600"
    ></i>
  </div>
  <div v-else class="text-red-700 font-semibold text-3xl">Out of Stock</div>
</template>

<script>
export default {
  data() {
    return {
      qty: 1,
      productInCart: false,
      processing: false
    };
  },
  props: ["product"],
  mounted() {
    this.checkProductInCart();
  },
  methods: {
    checkProductInCart() {
      var item = this.$CookieCart.getItem(this.product.id);
      if (item) {
        this.productInCart = true;
        this.qty = item.qty;
      } else this.productInCart = false;
    },
    productToCartEvent() {
      this.product.qty > 0
        ? this.productInCart
          ? this.removeFromCart()
          : this.addToCart()
        : false;
    },
    addToCart() {
      this.processing = true;
      this.$CookieCart.addItem(
        this.product.id,
        this.product.product_name,
        this.product.price,
        this.qty,
        this.product
      );
      this.productInCart = true;
      this.$eventBus.$emit("reload_cart");
      this.processing = false;
    },
    removeFromCart() {
      this.processing = true;
      this.$CookieCart.removeItem(this.product.id);
      this.productInCart = false;
      this.$eventBus.$emit("reload_cart");
      this.processing = false;
    },
    adjustQty(action) {
      if (action === "+" && this.qty < this.product.qty) {
        this.qty += 1;
      } else if (action == "-" && this.qty > 1) this.qty -= 1;
    }
  }
};
</script>
