<template>
  <transition name="fade-in">
    <div
      v-if="product"
      class="view-product flex flex-col mb-2 w-full md:w-48 bg-white md:mr-4 mb-8"
    >
      <div class="flex w-full h-full md:w-48 md:h-48 justify-center">
        <a v-bind:href="product | link_href">
          <img
            v-bind:src="product.image_paths[0] | img_path"
            class="w-full h-full"
            alt="Product Image"
          />
        </a>
      </div>
      <div class="flex flex-col bg-white text-base w-full">
        <h4 class="mt-4 md:mt-2 text-sm">{{ product.product_name }}</h4>
        <span class="text-sm text-gray-500 my-1">
          {{
          product.brand
          ? product.brand.brand_name
          : product.brand_name
          }}
        </span>
        <span class="font-bold mt-1">&#8369; {{ product.price | currency }}</span>
        <div class="flex flex-row w-full text-sm justify-between items-center mt-2">
          <button
            @click="productToCartEvent"
            class="add-cart relative p-2 border border-transparent"
            v-bind:class="{
							'bg-red-700 text-white': product.qty < 1,
							'bg-green-800 hover:bg-green-700 text-white':
								product.qty > 0 && !productInCart,
							'bg-gray-300 hover:bg-gray-200 text-black':
								product.qty > 0 && productInCart
						}"
          >
            <transition name="fade-in">
              <div
                v-if="processing"
                class="absolute inset-0 w-full h-full bg-white opacity-75 flex justify-center items-center border border-gray-400"
              >
                <icon name="spinner_1" class="fill-current text-black" />
              </div>
            </transition>
            <span v-if="product.qty < 1">Out of Stock</span>
            <span v-else-if="product.qty > 0 && !productInCart">Add to Cart</span>
            <span v-else>Remove from Cart</span>
          </button>
          <span class="hidden">
            {{
            product.qty > 0 ? "Available" : ""
            }}
          </span>
        </div>
      </div>
    </div>
  </transition>
</template>
<script>
export default {
  data() {
    return {
      productInCart: false,
      processing: false
    };
  },
  mounted() {
    this.checkProductInCart();
    this.$eventBus.$on("product_update", this.cartItemUpdated);
  },
  props: ["product"],
  methods: {
    cartItemUpdated(id) {
      if (this.product.id == id) {
        this.checkProductInCart();
      }
    },
    checkProductInCart() {
      var item = this.$CookieCart.getItem(this.product.id);
      if (item) {
        this.productInCart = true;
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
        1,
        this.product
      );
      this.$eventBus.$emit("reload_cart");
      this.$eventBus.$emit("product_update", this.product.id, "itemAdded");
      this.processing = false;
    },
    removeFromCart() {
      this.processing = true;
      this.$CookieCart.removeItem(this.product.id);
      this.$eventBus.$emit("reload_cart");
      this.$eventBus.$emit("product_update", this.product.id, "itemRemoved");
      this.processing = false;
    }
  }
};
</script>
