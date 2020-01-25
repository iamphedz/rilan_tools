<template>
  <div class="my-4">
    <div class="flex flex-col md:flex-row w-full">
      <div class="flex flex-col w-full md:w-2/12 text-sm select-none">
        <div class="flex flex-col w-full bg-gray-200 p-2 border">
          <div class="flex flex-col w-full py-2 mb-4">
            <div class="flex flex-row justify-between mb-2">
              <span class="font-semibold uppercase">Search</span>
              <button
                @click="resetSearch()"
                class="refresh-icon text-green-600 cursor-pointer"
                v-bind:class="{ 'opacity-25': queryIsDefault() }"
              >
                <icon name="refresh" v-if="!searching"></icon>
                <icon name="spinner_4" v-else></icon>
              </button>
            </div>
            <div
              class="flex flex-row w-full items-center border border-gray-400 focus-within:border-green-500 bg-white p-2"
            >
              <input
                id="search_bar"
                type="text"
                v-model="query.search_term"
                class="w-full focus:outline-none bg-transparent"
                placeholder="Product Name"
              />
              <icon name="search" class="fill-current text-gray-500 m-1"></icon>
            </div>
          </div>
          <div class="flex flex-col w-full py-2">
            <div
              class="flex flex-row justify-between border-b-2 w-full border-gray-300 pb-2"
            >
              <span class="font-semibold uppercase">Category</span>
              <icon
                name="spinner_1"
                class="text-gray-400"
                v-if="categories.length < 1"
              ></icon>
            </div>
            <transition name="slide">
              <ul
                class="flex flex-col text-gray-700 overflow-y-auto"
                v-if="categories.length > 0"
              >
                <li
                  v-for="(category, index) in categories"
                  v-bind:key="category.id"
                  class="py-2 flex justify-between w-full font-semibold text-xs"
                >
                  <div class="flex flex-row">
                    <input
                      type="checkbox"
                      id
                      v-bind:value="category.id"
                      @click="filterCategory(category.id)"
                    />
                    <span class="ml-2">{{ category.category_name }}</span>
                  </div>
                  <span class="mr-2 text-gray-400"
                    >({{ category.total_product }})</span
                  >
                </li>
              </ul>
            </transition>
          </div>
          <div class="flex flex-col w-full py-2">
            <div
              class="flex flex-row justify-between border-b-2 w-full border-gray-300 pb-2"
            >
              <span class="font-semibold uppercase">Brand</span>
              <icon
                name="spinner_1"
                class="text-gray-400"
                v-if="brands.length < 1"
              ></icon>
            </div>
            <transition name="slide">
              <ul
                class="flex flex-col text-gray-700 overflow-y-auto"
                v-if="brands.length > 0"
              >
                <li
                  v-for="(brand, index) in brands"
                  v-bind:key="brand.id"
                  class="py-2 flex justify-between w-full font-semibold text-xs"
                >
                  <div class="flex flex-row">
                    <input
                      type="checkbox"
                      id
                      v-bind:value="brand.id"
                      @click="filterBrand(brand.id)"
                    />
                    <span class="ml-2">{{ brand.brand_name }}</span>
                  </div>
                  <span class="mr-2 text-gray-400"
                    >({{ brand.total_product }})</span
                  >
                </li>
              </ul>
            </transition>
          </div>
        </div>
      </div>
      <div class="flex flex-col w-full md:w-10/12 md:ml-4">
        <div class="flex flex-col md:flex-row w-full text-gray-600">
          <div
            class="flex w-full md:w-1/2 items-center justify-center md:justify-start"
          >
            <span class="text-sm my-2" v-if="pagination.total > 0">{{
              searchMessage
            }}</span>
          </div>
          <div
            class="flex w-full md:w-1/2 justify-center md:justify-end text-sm items-center"
          >
            <span class="font-semibold mr-2">Sort by:</span>
            <div
              class="flex items-center border-gray-400 focus-within:border-green-600 border py-1"
            >
              <select id="sort" v-model="query.sort" class="outline-none w-48">
                <option value="product_name-asc" selected>Name: A-Z</option>
                <option value="product_name-desc">Name: Z-A</option>
                <option value="price-asc">Price: Low to High</option>
                <option value="price-desc">Price: High to Low</option>
                <option value="created_at-desc">Date: New to Old</option>
                <option value="created_at-asc">Date: Old to New</option>
              </select>
            </div>
          </div>
        </div>
        <div class="relative flex flex-wrap w-full pt-4 text-black mt-2">
          <transition-group name="fade-in" class="flex flex-wrap">
            <product-thumbnail
              v-for="product in products"
              v-bind:key="product.id"
              v-bind:product="product"
            />
          </transition-group>
          <div class="w-full flex justify-center">
            <ul
              class="pagination text-xs text-center flex flex-row items-center"
              v-if="products.length > 0"
            >
              <li
                v-bind:class="[{ disabled: !pagination.prev_page_url }]"
                class="bg-green-900 text-white p-2 rounded-l cursor-pointer text-center"
                @click="
                  pagination.prev_page_url
                    ? fetchProductSearch(pagination.prev_page_url)
                    : ''
                "
              >
                Prev
              </li>
              <li class="mx-3 text-gray-600">
                Page {{ pagination.current_page }} of {{ pagination.last_page }}
              </li>
              <li
                v-bind:class="[{ disabled: !pagination.next_page_url }]"
                class="bg-green-900 text-white p-2 rounded-r cursor-pointer text-center"
                @click="
                  pagination.next_page_url
                    ? fetchProductSearch(pagination.next_page_url)
                    : ''
                "
              >
                Next
              </li>
            </ul>
          </div>
          <transition name="fade-in">
            <div
              class="absolute inset-0 w-full h-full flex items-center justify-center p-2"
              v-if="searching"
            >
              <div
                class="flex bg-white z-50 px-5 py-2 rounded text-gray-500 text-sm"
              >
                <icon name="spinner_1" class="mr-1" />Loading...
              </div>
              <div
                class="absolute flex inset-0 w-full h-full bg-black opacity-25"
              ></div>
            </div>
          </transition>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      products: [],
      brands: [],
      categories: [],
      query: {
        sort: "product_name-asc",
        brands: [],
        categories: [],
        search_term: ""
      },
      last_search: "",
      pagination: {},
      quick_search: "",
      searching: false
    };
  },
  watch: {
    query: {
      handler: _.debounce(function(e) {
        this.fetchProductSearch();
      }, 500),
      deep: true
    }
  },
  mounted() {
    this.quick_search = this.getURLparam("quick_search");
    this.query.search_term = this.quick_search ? this.quick_search : "";

    this.fetchProductSearch();

    this.$eventBus.$on("quick_search", this.quickSearch);
  },
  computed: {
    searchMessage: function() {
      return `Showing ${this.pagination.from}-${this.pagination.to} of
                ${this.pagination.total} products.`;
    },
    search_term_watch() {
      return this.query.search_term;
    }
  },
  created() {},
  methods: {
    filterBrand(id) {
      let indexExists = this.query.brands.findIndex(brand => brand == id);
      if (indexExists == -1) this.query.brands.push(id);
      else this.query.brands.splice(indexExists, 1);
    },
    filterCategory(id) {
      let indexExists = this.query.categories.findIndex(
        category => category == id
      );
      if (indexExists == -1) this.query.categories.push(id);
      else this.query.categories.splice(indexExists, 1);
    },
    quickSearch(qsearch) {
      this.query.search_term = qsearch;
      this.fetchProductSearch();
    },
    resetSearch() {
      if (!this.queryIsDefault()) {
        this.query.search_term = "";
        this.query.brands = [];
        this.query.categories = [];
        this.query.sort = "product_name-asc";
        this.uncheckAll();
      }
    },
    queryIsDefault() {
      if (
        this.query.search_term === "" &&
        this.query.brands.length == 0 &&
        this.query.categories.length == 0 &&
        this.query.sort === "product_name-asc"
      )
        return true;
      else return false;
    },
    uncheckAll() {
      var checkboxes = document.getElementsByTagName("input");
      for (var i = 0; i < checkboxes.length; i++) {
        console.log(i);
        if (checkboxes[i].type == "checkbox") {
          checkboxes[i].checked = false;
        }
      }
    },
    getURLparam(param) {
      var url_string = window.location.href;
      var url = new URL(url_string);
      return url.searchParams.get(param);
    },
    fetchProductSearch(page_url) {
      this.searching = true;
      let vm = this;
      let query = Object.keys(this.query)
        .map(key => key + "=" + this.query[key])
        .join("&");
      //console.log(query);

      query = page_url ? "&" + query : "?" + query;

      page_url = page_url || "/api/product_search";
      page_url = page_url + query;

      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          vm.products = res.products.data;
          vm.brands = res.brands;
          vm.categories = res.categories;
          this.last_search = this.query.search_term;
          vm.makePagination(res.products);
        })
        .catch(err => console.log(err))
        .finally(() => {
          this.searching = false;
          window.scrollTo(0, 0);
        });
    },
    makePagination(data) {
      let pagination = {
        current_page: data.current_page,
        last_page: data.last_page,
        next_page_url: data.next_page_url,
        prev_page_url: data.prev_page_url,
        from: data.from,
        to: data.to,
        current_page_url: "/api/product_search?page=" + data.current_page,
        total: data.total
      };
      this.pagination = pagination;
    },
    toggleModal() {
      const body = document.querySelector("body");
      const modal = document.querySelector(".modal");
      modal.classList.toggle("opacity-0");
      modal.classList.toggle("pointer-events-none");
      body.classList.toggle("modal-active");
    },
    queryChange(event) {
      console.log(event.target);
    }
  }
};
</script>
