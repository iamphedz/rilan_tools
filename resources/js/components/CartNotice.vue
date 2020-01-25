<template>
  <div
    class="cart-icon flex flex-row cursor-pointer m-5 fixed bottom-0 z-50 right-0"
  >
    <!-- <div
      class="cart-icon-msg flex flew-row rounded text-xs items-center"
      style="display: none;"
    >
      <span class="p-3 bg-yellow-200 rounded border border-yellow-400"
        >{{ cart.items.length }} item(s) in the cart.</span
      >
      <div class="flex flex-col py-2 text-yellow-200 mr-2">
        <svg
          height="10"
          width="5"
          class="fill-current stroke-current text-yellow-400"
        >
          <polygon points="0 0, 5 5, 0 10" />
        </svg>
      </div>
    </div>-->
    <transition name="fade-in">
      <a
        v-bind:href="cart_href()"
        class="bg-green-900 p-2 border border-teal-700 rounded flex flex-row md:mt-0"
        v-if="render_done"
      >
        <div class="relative">
          <transition name="fade-in">
            <span
              class="md:absolute item-count md:-ml-4 md:-mt-4"
              v-if="itemCount > 0"
              >{{ itemCount }}</span
            >
          </transition>
          <span class="text-yellow-300 text-xs">
            <i class="fa fa-shopping-cart p-1" aria-hidden="true"></i>
          </span>
        </div>
      </a>
    </transition>
  </div>
</template>
<script>
export default {
  data() {
    return {
      cart: {},
      render_done: false
    };
  },
  created() {
    this.cart = this.$CookieCart.getInstance();
    console.log(this.cart);
  },
  mounted() {
    //this.$CookieCart.destroy();
    this.render_done = true;
    this.$eventBus.$on("reload_cart", this.reloadCart);
  },
  computed: {
    itemCount: function() {
      return Object.entries(this.cart.items).length;
    }
  },
  methods: {
    reloadCart() {
      this.cart = this.$CookieCart.getInstance();
    },
    cart_href() {
      return window.location.origin + "/shopping_cart";
    }
  }
};
</script>
