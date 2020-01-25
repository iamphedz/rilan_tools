<template>
    <i
        v-bind:class="iconClass[name] + ' ' + (fillClass ? fillClass : '')"
        aria-hidden="true"
    ></i>
</template>
<script>
/**
 * fontawesome 5 icon component
 */
export default {
    data() {
        return {
            iconClass: {
                //class name of fontawesome icons
                bold: "fas fa-bold",
                italic: "fas fa-italic",
                underline: "fas fa-underline",
                paragraph: "fas fa-paragraph",
                strike: "fas fa-strikethrough",
                quote: "fas fa-quote-right",
                ul: "fas fa-list-ul",
                ol: "fas fa-list-ol",
                undo: "fas fa-undo",
                redo: "fas fa-redo",
                code: "fas fa-code",
                plus: "fa fa-plus",
                minus: "fa fa-minus",
                times: "fa fa-times",
                edit: "far fa-edit",
                edit_solid: "fas fa-edit",
                delete: "fa fa-trash",
                delete_alt: "far fa-trash-alt",
                delete_solid: "fas fa-trash",
                mobile_alt: "fas fa-mobile-alt",
                share: "fas fa-share-alt",
                user_tie: "fas fa-user-tie",
                blogger_b: "fab fa-blogger-b",
                folder_open: "fas fa-folder-open",
                home: "fas fa-home",
                spinner_1: "fas fa-spinner",
                spinner_2: "fas fa-sync fa-spin",
                spinner_3: "fas fa-circle-notch fa-spin",
                spinner_4: "fas fa-spinner fa-pulse",
                sign_up: "fas fa-user-plus",
                sign_in: "fas fa-sign-in-alt",
                sign_out: "fas fa-sign-out-alt",
                user_setting: "fas fa-users-cog",
                user: "fas fa-user",
                setting: "fas fa-sliders-h",
                tag: "fas fa-tag",
                tags: "fas fa-tags",
                photo: "far fa-image",
                photos: "far fa-images",
                basket: "fas fa-shopping-basket",
                cart: "fas fa-shopping-cart",
                cart_add_1: "fas fa-cart-arrow-down",
                cart_add_2: "fas fa-cart-plus",
                cash_register: "fas fa-cash-register",
                cash: "fas fa-money-bill",
                cash_2: "fas fa-money-bill-alt",
                cash_wavy: "fas fa-money-bill-wave",
                cash_wavy_2: "fas fa-money-bill-wave-alt",
                wallet: "fas fa-wallet",
                file: "far fa-file-alt",
                check: "fas fa-check",
                check_square: "far fa-check-square",
                check_circle: "far fa-check-circle",
                check_double: "fas fa-check-double",
                tasks: "fas fa-tasks",
                search: "fas fa-search",
                upload: "fas fa-upload",
                shipping: "fas fa-truck",
                shipping_fast: "fas fa-shipping-fast",
                address: "far fa-address-card",
                email: "fas fa-at",
                phone: "fas fa-phone-alt",
                dolly_flatbed: "fas fa-dolly-flatbed",
                setting: "fas fa-cog",
                settings: "fas fa-cogs",
                arrow_right: "fas fa-arrow-right",
                arrow_left: "fas fa-arrow-left",
                bell: "fas fa-bell",
                refresh: "fa fa-sync-alt",
                print: "fas fa-print",
                pdf: "fas fa-file-pdf",
                pdf_regular: "far fa-file-pdf",
                refresh: "fas fa-sync",
                refresh_1: "fas fa-sync-alt",
                exclamation_round: "fas fa-exclamation-circle",
                exclamation_triangle: "fas fa-exclamation-triangle",
                exclamation: "fas fa-exclamation"
            }
        };
    },
    props: ["name", "fillClass"]
};
</script>
