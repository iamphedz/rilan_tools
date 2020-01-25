require("./bootstrap");
require("./custom");

window.Vue = require("vue");
import VueRouter from "vue-router";
import NProgress from "nprogress";
import moment from "moment";
import money from "v-money";

import Index from "./Pages/Index";
import Product from "./Pages/Product";
import Brand from "./Pages/Brand";
import Category from "./Pages/Category";
import OrderRequest from "./Pages/OrderRequest";

import CookieCart from "./CookieCart";
Object.defineProperty(Vue.prototype, "$CookieCart", { value: CookieCart });

Vue.prototype.$CookieCart.configure({
    storageType: "localStorage",
    expiration: 30,
    fees: {
        shipping: 0,
        discount: 0
    }
});

Vue.prototype.$CookieCart.init();

Vue.use(VueRouter);
// register directive v-money and component <money>
Vue.use(money, { precision: 4 });

const router = new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/admin-panel/",
            name: "ap-index",
            component: Index,
            meta: { title: "Admin Panel" }
        },
        {
            path: "/admin-panel/products",
            name: "ap-products",
            component: Product,
            meta: { title: "Products" }
        },
        {
            path: "/admin-panel/categories",
            name: "ap-categories",
            component: Category,
            meta: { title: "Product Categories" }
        },
        {
            path: "/admin-panel/brands",
            name: "ap-brands",
            component: Brand,
            meta: { title: "Product Brands" }
        },
        {
            path: "/admin-panel/order_requests",
            name: "ap-orders",
            component: OrderRequest,
            meta: { title: "Order Request" }
        }
    ]
});

NProgress.configure({ showSpinner: false });

router.beforeResolve((to, from, next) => {
    NProgress.start();
    next();
});

router.afterEach((to, from) => {
    NProgress.done();
});

Vue.component("admin-panel", require("./Pages/AdminPanel.vue").default);

Vue.component("shopping-cart", require("./Pages/ShoppingCart.vue").default);

Vue.component("cart-notice", require("./components/CartNotice.vue").default);

Vue.component(
    "product-dropdown",
    require("./components/ProductDropDown.vue").default
);
Vue.component("quick-search", require("./components/QuickSearch.vue").default);

Vue.component("add-cart-qty", require("./components/AddCartQty.vue").default);
Vue.component(
    "related-products",
    require("./components/RelatedProducts.vue").default
);
Vue.component("mode-payment", require("./components/PaymentModes.vue").default);
Vue.component("product-search", require("./Pages/ProductSearch.vue").default);
Vue.component("order-search", require("./Pages/OrderSearch.vue").default);
Vue.component("contact-form", require("./components/ContactForm.vue").default);
Vue.component("cart-checkout", require("./Pages/CartCheckOut.vue").default);

Vue.component(
    "product-thumbnail",
    require("./components/ProductThumbnail.vue").default
);
Vue.component(
    "payment-details",
    require("./components/PaymentDetails.vue").default
);

Vue.component("icon", require("./components/Icons.vue").default);
Vue.component("popup", require("./components/PopUp.vue").default);

//vue eventbus
Vue.prototype.$eventBus = new Vue();

//global filters
Vue.filter("currency", function(value) {
    let val = (value / 1).toFixed(2);
    return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
});

Vue.filter("link_href", function(value) {
    if (value) {
        let base_url = window.location.origin + "/products/view/";
        return (
            base_url +
            value.id +
            "-" +
            value.product_name.replace(/ /g, "-").toLowerCase()
        );
    }
    return "";
});

Vue.filter("img_path", function(value) {
    return window.location.origin + "/" + value;
});

Vue.filter("base_path", function(value) {
    return window.location.origin + "/" + value;
});

Vue.filter("slug", function(value) {
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
});

Vue.filter("error_clean", function(value) {
    return value[0];
});

//Vue.config.productionTip = false;
Vue.filter("formatDate", function(value, format = "MM/DD/YYYY") {
    if (value) {
        return moment(String(value)).format(format);
    }
});

Vue.filter("ucfirst", function(value) {
    return value.charAt(0).toUpperCase() + value.slice(1);
});

//import PortalVue from "portal-vue";
//Vue.use(PortalVue);

// custom form input components
Vue.component("test-component", require("./components/Test.vue").default);
Vue.component("inputbox", require("./components/InputBox.vue").default);
Vue.component("textbox", require("./components/TextBox.vue").default);
Vue.component("selectbox", require("./components/SelectBox.vue").default);
Vue.component("checkbox", require("./components/CheckBox.vue").default);
Vue.component("inputlist", require("./components/InputList.vue").default);
Vue.component("tagbox", require("./components/TagBox.vue").default);

const app = new Vue({
    el: "#app",
    router
});
