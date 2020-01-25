<template>
  <transition name="fade-down">
    <div
      class="w-full p-2 mt-10 border-t"
      v-if="
        related_products.name ||
          related_products.brand ||
          related_products.category
      "
    >
      <span
        class="text-4xl md:font-light font-semibold w-full block text-center py-10"
        >Related Products</span
      >
      <div
        class="flex md:flex-row flex-wrap w-full mx-auto p-2"
        v-show="related_products.name"
      >
        <span class="block w-full py-5 text-xl font-light">
          By
          <a
            v-bind:href="slug_href(brands[product.brand_id - 1], 'name')"
            class="font-semibold text-teal-500"
            >Name</a
          >:
        </span>
        <product-thumbnail
          v-for="product in related_products.name"
          v-bind:key="product.id"
          v-bind:product="product"
        />
      </div>
      <div
        class="flex md:flex-row flex-wrap w-full mx-auto p-2"
        v-show="related_products.brand"
      >
        <span class="block w-full py-5 text-xl font-light">
          By
          <a
            v-bind:href="slug_href(brands[product.brand_id], 'brand')"
            class="font-semibold text-teal-500"
            >Brand</a
          >:
        </span>
        <product-thumbnail
          v-for="product in related_products.brand"
          v-bind:key="product.id"
          v-bind:product="product"
        />
      </div>
      <div
        class="flex md:flex-row flex-wrap w-full mx-auto p-2"
        v-show="related_products.category"
      >
        <span class="block w-full py-5 text-xl font-light">
          By
          <a
            v-bind:href="slug_href(categories[product.category_id], 'category')"
            class="font-semibold text-teal-500"
            >Category</a
          >:
        </span>
        <product-thumbnail
          v-for="product in related_products.category"
          v-bind:key="product.id"
          v-bind:product="product"
        />
      </div>
    </div>
  </transition>
</template>
<script>
export default {
  data() {
    return {
      related_products: [],
      brands: [],
      categories: []
    };
  },
  mounted() {
    this.fetchRelatedProducts(this.product_id);
  },
  methods: {
    //
    fetchRelatedProducts(id) {
      fetch(`/api/products/related_products/${id}`)
        .then(res => res.json())
        .then(res => {
          this.related_products = res.related_products;
          this.categories = res.categories;
          this.brands = res.brands;

          console.log(this.related_products);
        })
        .catch(err => console.log(err));
    },
    slug_href(obj, type) {
      if (type == "brand") {
        return (
          window.location.origin +
          "/products/brand/" +
          obj.id +
          "-" +
          this.slug(obj.brand_name)
        );
      } else if (type == "category") {
        return (
          window.location.origin +
          "/products/category/" +
          obj.id +
          "-" +
          this.slug(obj.category_name)
        );
      } else {
        return window.location.origin + "/products/search";
      }
    },
    slug(value) {
      var slug = "";
      // Change to lower case
      var stringLower = value.toLowerCase();
      // Letter "e"
      slug = stringLower.replace(/e|é|è|ẽ|ẻ|ẹ|ê|ế|ề|ễ|ể|ệ/gi, "e");
      // Letter "a"
      slug = slug.replace(/a|á|à|ã|ả|ạ|ă|ắ|ằ|ẵ|ẳ|ặ|â|ấ|ầ|ẫ|ẩ|ậ/gi, "a");
      // Letter "o"
      slug = slug.replace(/o|ó|ò|õ|ỏ|ọ|ô|ố|ồ|ỗ|ổ|ộ|ơ|ớ|ờ|ỡ|ở|ợ/gi, "o");
      // Letter "u"
      slug = slug.replace(/u|ú|ù|ũ|ủ|ụ|ư|ứ|ừ|ữ|ử|ự/gi, "u");
      // Letter "d"
      slug = slug.replace(/đ/gi, "d");
      // Trim the last whitespace
      slug = slug.replace(/\s*$/g, "");
      // Change whitespace to "-"
      slug = slug.replace(/\s+/g, "-");
      // Trim back/forwardslash
      slug = slug.replace(/(\/+|\\+)/g, "");

      return slug;
    }
  },
  props: {
    product_id: Number,
    product: Object
  }
};
</script>
