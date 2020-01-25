<template>
	<div>
		<transition name="fade-in">
			<div
				v-if="showSettings"
				class="fixed z-50 flex w-full h-full items-center justify-center"
			>
				<div
					class="bg-white rounded flex flex-col z-20 w-full md:w-1/3 m-2"
				>
					<div
						class="header flex w-full justify-between p-2 border-b"
					>
						<span class="font-semibold uppercase"
							>User Settings</span
						>
						<button @click="showSettings = false">
							<icon name="times" />
						</button>
					</div>
					<div class="flex flex-row p-3 text-gray-600 text-xs">
						<span class="font-semibold mb-2 text-black w-1/3"
							>Product</span
						>
						<div class="flex flex-col w-2/3">
							<div class="flex flex-row w-full items-center mb-2">
								<span class="w-3/4">Stock Alert On</span>
								<div class="w-1/4 flex justify-center">
									<input
										type="checkbox"
										v-model="settings.product.alert"
									/>
								</div>
							</div>
							<div
								class="flex flex-row w-full items-center text-xs mb-2"
							>
								<span class="w-3/4"
									>Minimum stock before sending alert
								</span>
								<div
									class="w-1/4 text-sm flex flex-row items-center justify-center"
								>
									<input
										type="text"
										class="border-2 w-10 text-center focus:outline-none p-1 rounded bg-white"
										maxlength="3"
										v-model="
											settings.product.min_stock_alert
										"
									/>
								</div>
							</div>
						</div>
					</div>
					<div
						class="flex justify-between text-white bg-gray-200 p-2 text-sm"
					>
						<button
							class="w-1/2 p-2 bg-green-900 rounded"
							@click="saveSettings"
						>
							Save
						</button>
						<button class="w-1/2 ml-1 p-2 bg-green-900 rounded">
							Restore to Default
						</button>
					</div>
				</div>
				<div
					class="absolute inset-0 w-full h-full bg-black opacity-25"
					@click="showSettings = false"
				></div>
			</div>
		</transition>
		<popup></popup>
		<transition name="ap-menu">
			<div
				v-if="showMenu"
				class="fixed w-full h-full left-0 select-none flex z-50 md:hidden"
			>
				<div class="w-40 bg-white h-full flex flex-col z-20">
					<div
						class="flex flex-row w-full h-13 bg-green-900 text-white justify-between items-center px-2"
					>
						<span class="font-semibold"></span>
						<button @click="showMenu = !showMenu">
							<icon name="arrow_left" class="cursor-pointer" />
						</button>
					</div>
					<div class="p-2 border-t flex flex-col text-gray-700">
						<router-link
							:to="{ name: 'ap-index' }"
							class="py-2 cursor-pointer hover:bg-gray-200 font-semibold text-sm flex flex-col relative"
						>
							<span>Home</span>
						</router-link>
						<div
							class="py-2 cursor-pointer hover:bg-gray-200 font-semibold text-sm flex flex-col relative"
						>
							<router-link :to="{ name: 'ap-products' }">
								<span>Products</span></router-link
							>
						</div>
						<div class="flex flex-col text-xs">
							<router-link
								:to="{ name: 'ap-categories' }"
								class="py-2 pl-4 hover:bg-gray-200 cursor-pointer"
								>Category</router-link
							>
							<router-link
								:to="{ name: 'ap-brands' }"
								class="py-2 pl-4 hover:bg-gray-200 cursor-pointer"
								>Brand</router-link
							>
						</div>
						<router-link
							:to="{ name: 'ap-orders' }"
							class="py-2 cursor-pointer hover:bg-gray-200 font-semibold text-sm flex flex-col relative"
						>
							<span>Order Requests</span>
						</router-link>
						<span
							class="py-2 cursor-pointer hover:bg-gray-200 font-semibold text-sm"
							>Inventory</span
						>
						<div class="flex flex-col border-t my-2"></div>
					</div>
				</div>
				<div
					@click="showMenu = !showMenu"
					class="absolute inset-0 w-full h-full z-10 bg-black opacity-25"
				></div>
			</div>
		</transition>
		<div
			class="header sticky top-0 bg-green-900 text-white w-full flex flex-row md:flex-col z-40"
		>
			<div class="flex flex-row w-full justify-between items-center p-2">
				<div class="flex flex-row">
					<svg
						@click="showMenu = !showMenu"
						class="md:hidden fill-current h-5 w-5 text-yellow-400 mr-2"
						viewBox="0 0 20 20"
						xmlns="http://www.w3.org/2000/svg"
					>
						<title>Menu</title>
						<path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
					</svg>
					<router-link
						:to="{ name: 'ap-index' }"
						class="flex flex-col"
					>
						<span class="font-semibold text-xl"
							>Rilan Tool Supply</span
						>
						<span class="text-xs text-white leading-normal"
							>Admin Panel</span
						>
					</router-link>
				</div>

				<div
					class="flex w-10 h-10 rounded-full items-center justify-center bg-yellow-400 cursor-pointer"
				>
					<transition name="fade-in">
						<div
							v-if="toggleUserOption"
							@click="toggleUserOption = !toggleUserOption"
							class="fixed inset-0 w-full h-full z-10 bg-black opacity-25"
						></div>
					</transition>
					<span @click="toggleUserOption = !toggleUserOption">
						<icon name="user" fillClass="text-green-900" />
					</span>
					<div class="relative">
						<transition name="slide">
							<div
								v-if="toggleUserOption"
								class="absolute top-0 right-0 bg-white rounded p-2 shadow-lg text-black text-xs mt-5 w-32 flex flex-col z-40"
							>
								<div
									class="pb-3 border-b flex flex-col leading-normal"
								>
									<span>Current User:</span
									><b>{{ user.name }}</b>
								</div>
								<div class="flex flex-col mt-2 leading-none">
									<span
										class="cursor-pointer py-2"
										@click="showSettings = !showSettings"
										>User Settings</span
									>
									<span
										@click="userLogout"
										class="cursor-pointer py-2"
										>Logout</span
									>
								</div>
							</div>
						</transition>
					</div>
				</div>
			</div>

			<!--
			<div
				@click="toggleUserOption = !toggleUserOption"
				class="flex md:fixed md:top-0 md:right-0 m-2 w-10 h-10 rounded-full items-center justify-center bg-yellow-400 z-20 cursor-pointer"
			>
				
					<div class="relative">
						
					</div>
				</transition>
			</div>-->
			<div
				class="hidden md:flex border-b flex-row w-full text-green-900 bg-yellow-400"
			>
				<div class="flex flex-row w-full">
					<router-link
						:to="{ name: 'ap-index' }"
						class="p-2 cursor-pointer hover:bg-green-900 hover:text-yellow-400 font-semibold text-sm flex flex-col relative"
					>
						<span>Home</span>
					</router-link>
					<router-link
						:to="{ name: 'ap-products' }"
						class="p-2 cursor-pointer hover:bg-green-900 hover:text-yellow-400 font-semibold text-sm flex flex-col relative"
					>
						<span>Product</span>
					</router-link>
					<router-link
						:to="{ name: 'ap-categories' }"
						class="p-2 cursor-pointer hover:bg-green-900 hover:text-yellow-400 font-semibold text-sm flex flex-col relative"
						>Category</router-link
					>
					<router-link
						:to="{ name: 'ap-brands' }"
						class="p-2 cursor-pointer hover:bg-green-900 hover:text-yellow-400 font-semibold text-sm flex flex-col relative"
						>Brand</router-link
					>
					<router-link
						:to="{ name: 'ap-orders' }"
						class="p-2 cursor-pointer hover:bg-green-900 hover:text-yellow-400 font-semibold text-sm flex flex-col relative"
					>
						<span>Order Request</span>
					</router-link>
					<span
						class="p-2 cursor-pointer hover:bg-green-900 hover:text-yellow-400 font-semibold text-sm"
						>Inventory</span
					>
				</div>
			</div>
		</div>
		<router-view class="p-2 md:px-20 md:py-10 z-10"></router-view>
	</div>
</template>
<script>
export default {
	data() {
		return {
			toggleUserOption: false,
			activeMenu: "",
			showProductSubMenu: false,
			showMenu: false,
			productList: false,
			orderList: false,
			showSettings: false,
			settings: {
				product: {
					alert: true,
					min_stock_alert: 1
				}
			}
		};
	},
	watch: {
		$route(to, from) {
			this.showMenu = false;
			this.activeMenu = to.name;
		},
		showMenu: function(e) {
			if (this.showMenu) {
				this.bodyOverflow(true);
			} else {
				this.bodyOverflow(false);
			}
		},
		toggleUserOption: function(e) {
			if (this.toggleUserOption) {
				this.bodyOverflow(true);
			} else {
				this.bodyOverflow(false);
			}
		}
	},
	mounted() {
		if (!this.$route.name) this.$router.push({ name: "ap-index" });
		if (this.$route.name) this.activeMenu = this.$route.name;
		this.assertSettings();

		//console.log(this.user);
	},
	props: ["user"],
	methods: {
		bodyOverflow(bool) {
			if (bool) {
				document.body.classList.add("overflow-hidden");
				this.$eventBus.$emit("main_menu", true);
			} else {
				document.body.classList.remove("overflow-hidden");
				this.$eventBus.$emit("main_menu", false);
			}
		},
		userLogout() {
			let userLogout = $(document).find("#logout-form");
			userLogout.submit();
		},
		assertSettings() {
			if (this.user.user_settings) {
				let productSettings = this.user.user_settings.product;
				this.settings.product.alert = productSettings.alert;
				this.settings.product.min_stock_alert =
					productSettings.min_stock_alert;
			}
		},
		saveSettings() {
			this.processing = true;
			let vm = this;
			axios
				.post("/api/user_settings", {
					setting: this.settings,
					user_id: this.user.id
				})
				.then(res => {
					//console.log(res);
					if (res) {
						vm.showSettings = false;
						vm.$eventBus.$emit("popup", {
							message: "Setting has been saved."
						});
						vm.settings.product = res.data.product;
					}
				})
				.catch(err => console.log(err))
				.finally(() => {
					this.toggleUserOption = false;
					this.processing = false;
				});
		}
	}
};
</script>
