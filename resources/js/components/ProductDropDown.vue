<template>
  <transition name="fade-right">
    <div v-if="render_done" class="relative flex flex-col justify-center w-full">
      <div
        class="container flex flex-col items-center justify-between w-full category-nav md:flex-row md:items-center"
      >
        <div class="flex flex-wrap text-xs md:text-base">
          <span
            @click="selectCategory(category.id)"
            v-for="category in categories"
            v-bind:key="category.id"
            class="flex-none block px-1 py-2 font-semibold text-green-900 uppercase cursor-pointer md:inline-block md:p-2 hover:bg-green-900 hover:text-yellow-400"
            v-bind:class="{ active: selected_category == category.id }"
          >{{ category.category_name }}</span>
        </div>
      </div>
      <div class="relative z-50 flex w-full">
        <transition name="slide">
          <div
            class="absolute flex flex-col w-full text-gray-200 bg-green-800 border-t-8 border-b-8 border-green-900 product-list md:flex-row md:my-0"
            v-if="selected_category"
          >
            <div class="flex flex-wrap w-full my-2 text-gray-200">
              <a
                v-for="product in selected_category_product"
                v-bind:key="product.id"
                v-bind:href="link_href(product)"
                class="inline-block w-40 p-2 mb-1 ml-3 text-sm font-normal text-gray-200 hover:bg-green-700"
              >
                {{
                "(" +
                product.qty +
                ") " +
                brands[product.brand_id].brand_name +
                " " +
                product.product_name
                }}
              </a>
            </div>
          </div>
        </transition>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  data() {
    return {
      categories: [],
      brands: [],
      products: [],
      selected_category: "",
      selected_category_product: [],
      render_done: false
    };
  },
  props: {
    csrf: String
  },
  computed: {
    url: function() {
      return window.location.origin;
    },
    token: function() {
      return $('meta[name="csrf-token"]').attr("content");
    }
  },
  created() {
    this.fetchCategories();
  },
  methods: {
    fetchCategories(page_url) {
      let vm = this;
      page_url = page_url || "/api/categories";
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          //console.log(res);
          this.categories = res.indexed;
          this.products = res.products;
          this.brands = res.brands;
          this.render_done = true;
        })
        .catch(err => console.log(err));
    },
    selectCategory(category) {
      if (this.selected_category != category) this.selected_category = category;
      else this.selected_category = "";

      this.selected_category_product = this.products.filter(product => {
        return product.category_id == this.selected_category;
      });
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
    },
    link_href(value) {
      let base_url = window.location.origin + "/products/view/";
      return base_url + value.id + "-" + this.slug(value.product_name);
    }
  }
};
</script>
