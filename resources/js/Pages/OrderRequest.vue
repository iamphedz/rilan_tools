<template>
	<div class="py-6 flex flex-col">
		<transition name="fade-in">
			<div
				v-if="processing"
				class="fixed inset-0 w-full h-full justify-center items-center flex z-50"
			>
				<div
					class="bg-white text-gray-900 px-3 py-2 text-xs z-20 border rounded"
				>
					<span>Processing...</span>
				</div>
				<div
					class="absolute bg-black inset-0 w-full h-full opacity-25"
				></div>
			</div>
		</transition>
		<div class="flex w-full mb-5 justify-between">
			<span class="font-semibold text-xl">Order Request</span>
			<div class="flex flex-col text-xs">
				<div class="flex flex-row items-center justify-center">
					<button
						@click="showFilter = !showFilter"
						class="bg-green-900 text-white px-2 py-1 rounded font-semibold"
					>
						Show {{ search.filter_name }}
					</button>
				</div>
				<div class="relative">
					<transition name="slide">
						<div
							v-if="showFilter"
							class="absolute top-0 right-0 flex flex-col w-24 rounded shadow-lg border bg-white text-sm text-right z-30"
						>
							<span
								@click="searchFilter()"
								class="py-2 px-3 hover:bg-gray-200"
								v-bind:class="{
									'font-semibold bg-gray-200':
										search.filter_name === 'All'
								}"
								>All</span
							>
							<span
								v-for="status in orderStatus"
								v-bind:key="status.id"
								class="py-2 px-3 hover:bg-gray-200"
								v-bind:class="{
									'font-semibold bg-gray-200':
										status.status === search.filter_name
								}"
								@click="searchFilter(status)"
								>{{ status.status }}</span
							>
							<span
								@click="searchFilter('expired')"
								class="py-2 px-3 hover:bg-gray-200"
								v-bind:class="{
									'font-semibold bg-gray-200':
										search.filter_name === 'Expired'
								}"
								>Expired</span
							>
						</div>
					</transition>
				</div>
			</div>
		</div>
		<transition-group name="fade-in" class="w-full flex flex-col">
			<button
				class="order-container flex text-xs border-b border-t md:border border-transparent overflow-hidden"
				v-for="order_request in order_requests"
				v-bind:key="order_request.id"
				v-bind:class="{
					'bg-gray-200 border-gray-400 ss-box-shadow selected':
						selected_order.id == order_request.id
				}"
				@click="selectOrderRequest(order_request)"
			>
				<div class="flex flex-col w-full mb-2">
					<div
						class="flex flex-row justify-between text-sm items-center p-2"
						v-bind:class="{
							'border-b': selected_order.id == order_request.id
						}"
					>
						<div class="flex flex-col items-start">
							<span
								class="font-semibold"
								v-bind:class="{
									'text-red-600': order_request.has_expired
								}"
								>ORT# {{ order_request.tracking_no }}</span
							>
							<span class="text-gray-500 text-xs mt-1">{{
								order_request.created_at
									| formatDate("MMMM DD, YYYY - h:mm:ss a")
							}}</span>
						</div>
						<span
							class="order-status rounded text-xs px-2 border shadow py-1 font-semibold text-gray-900"
							v-bind:class="
								orderRequestStatusMarker(order_request)
							"
							>{{ order_request.status_details.status }}</span
						>
					</div>
					<div
						class="flex flex-col text-xs text-gray-600 leading-normal text-gray-500 px-1"
					>
						<transition name="slide">
							<div
								class="flex flex-col bg-white p-2"
								v-if="selected_order.id == order_request.id"
							>
								<div
									class="flex flex-row justify-between items-center leading-tight text-sm my-2"
								>
									<div
										class="flex flex-row items-center text-gray-500"
									>
										<icon name="cart" />
										<span class="ml-2"
											><b class="text-gray-900">{{
												order_request.order_data.items
													| orderItemCount
											}}</b>
											Items</span
										>
									</div>
									<div
										class="flex flex-row items-center text-gray-500"
									>
										<icon name="cash_register" />
										<span class="ml-2"
											><b class="text-gray-900"
												>&#8369;
												{{ finalTotal | currency }}</b
											></span
										>
									</div>
								</div>
								<div
									class="flex flex-col w-full items-start mb-2"
								>
									<span>Mode of Payment</span>
									<div class="flex flex-row items-center">
										<icon
											name="cash"
											class="mr-1 w-4 text-gray-500"
										/>
										<span class="text-gray-900">{{
											order_request.status_order.name
										}}</span>
									</div>
								</div>
								<div class="flex flex-col w-full items-start">
									<span>Requestor's Information</span>
									<div
										class="flex flex-col text-gray-900 items-start leading-snug"
									>
										<div
											class="flex flex-row items-center justify-center"
										>
											<icon
												name="user"
												class="mr-1 w-4 text-gray-500"
											/>
											<span>{{
												order_request.name
											}}</span>
										</div>
										<div
											class="flex flex-row items-center justify-center"
										>
											<icon
												name="address"
												class="mr-1 w-4 text-gray-500"
											/>
											<span>{{
												order_request.address
											}}</span>
										</div>
										<div
											class="flex flex-row items-center justify-center"
										>
											<icon
												name="email"
												class="mr-1 w-4 text-gray-500"
											/>
											<span>{{
												order_request.email
											}}</span>
										</div>
										<div
											class="flex flex-row items-center justify-center"
										>
											<icon
												name="phone"
												class="mr-1 w-4 text-gray-500"
											/>
											<span>{{
												order_request.contact_no
											}}</span>
										</div>
									</div>
								</div>
							</div>
						</transition>
					</div>
				</div>
			</button>
		</transition-group>
		<div v-if="order_requests.length > 0" class="flex w-full mb-10 mt-2">
			<div
				class="flex flex-row mx-auto text-xs bg-white rounded overflow-hidden shadow"
			>
				<span
					@click="navigatePagination(pagination.prev_page_url)"
					class="p-2 cursor-pointer"
					v-bind:class="{
						'bg-gray-300 text-gray-900': !pagination.prev_page_url,
						'bg-green-900 text-white': pagination.prev_page_url
					}"
					>Prev</span
				>
				<span class="p-2"
					>Page {{ pagination.current_page }} of
					{{ pagination.last_page }}</span
				>
				<span
					@click="navigatePagination(pagination.next_page_url)"
					class="p-2 cursor-pointer"
					v-bind:class="{
						'bg-gray-300 text-gray-900': !pagination.next_page_url,
						'bg-green-900 text-white': pagination.next_page_url
					}"
					>Next</span
				>
			</div>
		</div>
		<div
			class="flex w-full mb-10 p-2 items-center justify-center text-gray-500 text-sm italic"
			v-else
		>
			<span>No records found.</span>
		</div>
		<transition name="fade-up">
			<div
				class="flex flex-col fixed inset-0 w-full h-full z-40 md:items-center md:justify-center"
				v-if="editStatus"
			>
				<div
					class="absolute md:static flex w-full md:w-1/3 bottom-0 bg-white flex-col rounded-lg md:rounded overflow-hidden md:bottom-auto z-50"
				>
					<span
						class="border-b pb-2 font-semibold p-2 block text-center uppercase bg-gray-200"
						>Status</span
					>

					<select
						class="update-status p-2 rounded focus:outline-none border-2 m-2 text-sm bg-white"
						@change="changeSelectedStatus($event.target.value)"
					>
						<option
							v-for="status in orderStatus"
							v-bind:key="status.id"
							v-bind:value="status.id"
							:selected="
								status.id === selected_order.status
									? 'selected'
									: ''
							"
							>{{ status.status }}</option
						>
					</select>

					<div
						class="flex flex-row w-full justify-between p-2 border-t bg-gray-200 text-sm"
					>
						<button
							class="p-2 w-1/2 bg-green-900 text-white rounded shadow"
							@click="confirmupdateOrderStatus"
						>
							Save
						</button>
						<button
							class="p-2 w-1/2 ml-2 bg-green-900 text-white rounded shadow"
							@click="
								showModals = false;
								editStatus = false;
							"
						>
							Close
						</button>
					</div>
				</div>
				<div
					class="absolute bg-black opacity-25 inset-0 w-full h-full"
					@click="
						showModals = false;
						editStatus = !editStatus;
					"
				></div>
			</div>
			<div
				class="flex flex-col fixed inset-0 w-full h-full z-40 md:justify-center md:items-center"
				v-if="editShippingFee"
			>
				<div
					class="absolute md:static flex w-full md:w-1/3 bottom-0 bg-white flex-col rounded-lg md:rounded overflow-hidden md:bottom-auto z-50"
				>
					<span
						class="border-b pb-2 font-semibold p-2 block text-center uppercase bg-gray-200"
						>Shipping Fee</span
					>
					<div
						class="flex flex-col w-full p-2 justify-center items-center"
					>
						<span class="text-sm text-gray-700 text-center"
							>Make sure you input the required amount of shipping
							fee for this order. Refer to Products tab to
							calculate it.</span
						>

						<money
							v-model="shippingFee"
							v-bind="currency"
							maxlength="12"
							class="w-full px-2 py-1 rounded border-2 my-2 text-center bg-white"
							v-bind:placeholder="
								selected_order.order_data.fees.shipping
							"
						></money>
					</div>
					<div
						class="flex flex-row w-full justify-between p-2 border-t bg-gray-200 text-sm"
					>
						<button
							class="p-2 w-1/2 bg-green-900 text-white rounded shadow"
							@click="updateShippingFee"
						>
							Save
						</button>
						<button
							class="p-2 w-1/2 ml-2 bg-green-900 text-white rounded shadow"
							@click="
								showModals = false;
								editShippingFee = false;
							"
						>
							Close
						</button>
					</div>
				</div>
				<div
					class="absolute bg-black opacity-25 inset-0 w-full h-full"
					@click="
						showModals = false;
						editShippingFee = !editShippingFee;
					"
				></div>
			</div>
			<div
				class="flex flex-col fixed inset-0 w-full h-full z-40 md:justify-center md:items-center"
				v-if="editDiscount"
			>
				<div
					class="absolute md:static flex w-full md:w-1/3 bottom-0 bg-white flex-col rounded-lg md:rounded overflow-hidden md:bottom-auto z-50"
				>
					<span
						class="border-b pb-2 font-semibold p-2 block text-center uppercase bg-gray-200"
						>Discount</span
					>
					<div
						class="flex flex-col w-full p-2 justify-center items-center"
					>
						<div class="flex flex-row w-full text-sm">
							<div
								class="flex flex-row w-1/2 items-center justify-center"
							>
								<input
									type="radio"
									name="discount_type"
									value="flat"
									v-model="discountType"
								/>
								<span class="ml-1">Flat Rate</span>
							</div>
							<div
								class="flex flex-row w-1/2 items-center justify-center"
							>
								<input
									type="radio"
									name="discount_type"
									value="percent"
									v-model="discountType"
								/>
								<span class="ml-1">% Rate</span>
							</div>
						</div>
						<p
							v-if="discountType === 'flat'"
							class="text-sm text-center my-1"
						>
							Add a flat discount amount to the order.
						</p>
						<p v-else class="text-sm text-center my-1">
							Add a discount based on a % of subtotal.
						</p>
						<money
							v-if="discountType == 'flat'"
							v-model="discount"
							v-bind="currency"
							maxlength="12"
							class="w-full px-2 py-1 rounded border-2 my-2 text-center bg-white"
						></money>
						<money
							v-else
							v-model="discount"
							v-bind="percent"
							maxlength="5"
							class="w-full px-2 py-1 rounded border-2 my-2 text-center bg-white"
						></money>
					</div>
					<div
						class="flex flex-row w-full justify-between p-2 border-t bg-gray-200 text-sm"
					>
						<button
							class="p-2 w-1/2 bg-green-900 text-white rounded shadow"
							@click="updateDiscount"
						>
							Save
						</button>
						<button
							class="p-2 w-1/2 ml-2 bg-green-900 text-white rounded shadow"
							@click="
								showModals = false;
								editDiscount = false;
							"
						>
							Close
						</button>
					</div>
				</div>
				<div
					class="absolute bg-black opacity-25 inset-0 w-full h-full"
					@click="
						showModals = false;
						editDiscount = !editDiscount;
					"
				></div>
			</div>
		</transition>
		<transition name="fade-up">
			<div
				class="flex flex-col fixed inset-0 w-full h-full z-40 md:justify-center md:items-center"
				v-if="viewDetails"
			>
				<div
					class="absolute md:static flex w-full max-h-screen md:w-1/3 bottom-0 md:bottom-auto z-50"
				>
					<div
						class="flex flex-col bg-white rounded-lg w-full md:rounded overflow-hidden"
					>
						<span
							class="border-b pb-2 font-semibold p-2 block text-center uppercase bg-gray-200"
							>Products</span
						>
						<div class="p-2 w-full overflow-y-auto">
							<table class="text-sm w-full leading-snug">
								<thead>
									<th class="font-semibold text-left">
										Product
									</th>
									<th class="font-semibold text-right">
										Amount
									</th>
								</thead>
								<tbody>
									<tr
										v-for="item in selected_order.order_data
											.items"
										v-bind:key="item.id"
									>
										<td class="text-left pb-1">
											<div class="flex flex-col">
												<span>
													{{ item.name }} x
													{{ item.qty }}</span
												>
												<span
													class="text-xs text-gray-500"
													>Unit Price:
													{{
														item.price | currency
													}}</span
												>
											</div>
										</td>
										<td class="text-right pb-1">
											{{
												(item.qty * item.price)
													| currency
											}}
										</td>
									</tr>
									<tr>
										<td
											colspan="2"
											class="py-1 border-t"
										></td>
									</tr>
									<tr>
										<td class="font-semibold text-right">
											Subtotal
										</td>
										<td class="text-right">
											{{
												selected_order.order_data
													.sub_total | currency
											}}
										</td>
									</tr>
									<tr>
										<td class="font-semibold text-right">
											Shipping Fee
										</td>
										<td class="text-right">
											{{
												selected_order.order_data.fees
													.shipping | currency
											}}
										</td>
									</tr>
									<tr>
										<td class="font-semibold text-right">
											Discount
										</td>
										<td class="text-right">
											-{{
												selected_order.order_data.fees
													.discount | currency
											}}
										</td>
									</tr>
									<tr>
										<td class="font-semibold text-right">
											Total
										</td>
										<td class="text-right">
											{{ finalTotal | currency }}
										</td>
									</tr>
								</tbody>
							</table>
						</div>

						<div
							class="flex flex-row w-full justify-center p-2 border-t bg-gray-200 text-sm"
						>
							<button
								class="p-2 w-32 bg-green-900 text-white rounded shadow"
								@click="
									showModals = false;
									viewDetails = false;
								"
							>
								Close
							</button>
						</div>
					</div>
				</div>

				<div
					class="absolute bg-black opacity-25 inset-0 w-full h-full"
					@click="
						showModals = false;
						viewDetails = !viewDetails;
					"
				></div>
			</div>
		</transition>
		<transition name="fade-up">
			<div
				class="flex flex-col fixed bottom-0 left-0 border-t bg-white w-full text-gray-900 text-xs items-start z-30"
				v-if="hasSelectedOrder"
			>
				<span
					v-if="selected_order.has_expired"
					class="bg-red-200 text-red-900 flex w-full p-2 justify-center"
					>Expiration period already passed.</span
				>
				<div class="flex flex-row w-full">
					<button
						class="flex items-center flex-col p-2 justify-center w-1/6"
						@click="handleAction('viewDetails')"
					>
						<icon class="mb-1" name="dolly_flatbed" />Products
					</button>
					<button
						class="flex items-center flex-col p-2 justify-center w-1/6"
						v-bind:class="{
							'cursor-not-allowed text-gray-400':
								selected_order.status == 5
						}"
						@click="
							selected_order.status == 5
								? handleAction()
								: handleAction('editShippingFee')
						"
					>
						<icon class="mb-1" name="shipping_fast" />Shipping
					</button>
					<button
						class="flex items-center flex-col p-2 justify-center w-1/6"
						@click="handleAction('editDiscount')"
					>
						<svg
							version="1.1"
							id="Layer_1"
							xmlns="http://www.w3.org/2000/svg"
							xmlns:xlink="http://www.w3.org/1999/xlink"
							x="0px"
							y="0px"
							viewBox="0 0 512.001 512.001"
							class="w-4 h-4 fill-current text-gray-900"
							xml:space="preserve"
						>
							<g>
								<g>
									<path
										d="M405.997,261.916V0H106.005v261.916H22.661l233.34,250.086L489.34,261.916H405.997z M98.336,294.807h40.561V32.892
			h234.209v261.916h40.561l-157.665,168.98L98.336,294.807z"
									/>
								</g>
							</g>
							<g>
								<g>
									<path
										d="M205.044,108.25c-22.146,0-40.164,18.018-40.164,40.165c0,22.146,18.018,40.164,40.164,40.164
			c22.146,0,40.164-18.018,40.164-40.164C245.208,126.268,227.19,108.25,205.044,108.25z M205.043,155.687
			c-4.009,0-7.272-3.263-7.272-7.272c0-4.011,3.263-7.273,7.272-7.273c4.01,0,7.272,3.263,7.272,7.273
			C212.315,152.425,209.053,155.687,205.043,155.687z"
									/>
								</g>
							</g>
							<g>
								<g>
									<path
										d="M306.958,206.121c-22.147,0-40.164,18.018-40.164,40.164c0,22.147,18.018,40.165,40.164,40.165
			s40.164-18.018,40.164-40.165C347.123,224.139,329.105,206.121,306.958,206.121z M306.958,253.559
			c-4.011,0-7.272-3.263-7.272-7.273c0-4.009,3.263-7.272,7.272-7.272c4.01,0,7.272,3.263,7.272,7.272
			C314.232,250.296,310.969,253.559,306.958,253.559z"
									/>
								</g>
							</g>
							<g>
								<g>
									<rect
										x="173.718"
										y="180.82"
										transform="matrix(0.6281 -0.7781 0.7781 0.6281 -58.2981 272.5502)"
										width="164.542"
										height="32.891"
									/>
								</g>
							</g>
							<g></g>
							<g></g>
							<g></g>
							<g></g>
							<g></g>
							<g></g>
							<g></g>
							<g></g>
							<g></g>
							<g></g>
							<g></g>
							<g></g>
							<g></g>
							<g></g>
							<g></g>
						</svg>
						<span>Discount</span>
					</button>
					<button
						class="flex items-center flex-col p-2 justify-center w-1/6"
						@click="
							handleAction('editStatus');
							selectedStatus = selected_order.status;
						"
					>
						<icon class="mb-1" name="tasks" />Status
					</button>
					<button
						@click="printPDF"
						class="flex items-center flex-col p-2 justify-center w-1/6"
					>
						<icon class="mb-1" name="pdf" />PDF
					</button>
					<button
						class="flex items-center flex-col p-2 justify-center w-1/6"
					>
						<icon class="mb-1" name="delete" />Delete
					</button>
				</div>
			</div>
		</transition>
		<div
			v-if="showSearch"
			class="absolute inset-0 w-full h-full"
			@click="showSearch = !showSearch"
		></div>
		<div
			class="product-settings fixed bottom-0 right-0 overflow-hidden bg-gray-200 border-t flex flex-row z-20"
			v-bind:class="{
				'active rounded-none': showSearch,
				'rounded-tl-lg': !showSearch
			}"
		>
			<button
				class="h-10 w-10 bg-green-900 text-white"
				@click="showSearch = !showSearch"
			>
				<icon v-if="!showSearch" name="search" />
				<icon v-else name="arrow_right" />
			</button>
			<div
				class="flex flex-row w-full text-xs items-center"
				v-if="showSearch"
			>
				<input
					type="text"
					v-model="search.search_term"
					class="w-full h-full px-2 py-1 text-center"
					placeholder="Type to search..."
				/>
			</div>
		</div>
		<div
			class="fixed bottom-0 left-0 overflow-hidden bg-gray-200 border-t flex flex-row z-10 rounded-tr-lg"
		>
			<button
				@click="refreshData()"
				class="w-10 h-10 flex items-center justify-center text-white bg-green-900"
			>
				<icon name="refresh" />
			</button>
		</div>
	</div>
</template>
<script>
import NProgress from "nprogress";
import _ from "lodash";
import { Money } from "v-money";
export default {
	components: { Money },
	data() {
		return {
			mainMenu: false,
			processing: false,
			order_requests: [],
			pagination: {},
			selected_order: {},
			hasSelectedOrder: false,
			orderStatus: [],
			selectedStatus: "1",
			editMode: false,
			editStatus: false,
			viewDetails: false,
			editShippingFee: false,
			shippingFee: "",
			editDiscount: false,
			discount: "",
			discountType: "flat",
			showModals: false,
			selectedOrderUpdated: false,
			showSearch: false,
			search: {
				filter: "all",
				filter_name: "All",
				search_term: ""
			},
			showFilter: false,
			showSettings: false,
			ignoreExpiration: false,
			currency: {
				decimal: ".",
				thousands: ",",
				prefix: "₱ ",
				suffix: "",
				precision: 2,
				masked: false
			},
			number: {
				decimal: "",
				thousands: "",
				prefix: "",
				suffix: "",
				precision: 0,
				masked: false
			},
			percent: {
				decimal: "",
				thousands: "",
				prefix: "",
				suffix: " %",
				precision: 0,
				masked: false
			}
		};
	},
	mounted() {
		this.fetchOrderRequests();
		this.fetchOrderStatus();
		this.$eventBus.$on("popup-confirmation", this.updateOrderStatus);
		//this.$eventBus.$on("main_menu", this.handleMainMenuEvent);
	},
	filters: {
		orderItemCount(orderProducts) {
			var productArray = Object.keys(orderProducts).map(function(key) {
				return [Number(key), orderProducts[key]];
			});

			return productArray.reduce((sum, val) => {
				return sum + +val[1].qty;
			}, 0);
		}
	},
	computed: {
		search_term_watch() {
			return this.search.search_term;
		},
		finalTotal() {
			let fees = this.selected_order.order_data.fees,
				subTotal = this.selected_order.order_data.sub_total;

			return subTotal + fees.shipping + -fees.discount;
		}
	},
	watch: {
		showModals: function(e) {
			if (this.showModals) {
				document.body.classList.add("overflow-hidden");
			} else {
				document.body.classList.remove("overflow-hidden");
				if (this.selectedOrderUpdated) this.fetchOrderRequests();
			}
		},
		search: {
			handler: _.debounce(function(e) {
				this.fetchOrderRequests();
			}, 500),
			deep: true
		},
		discountType: function(oldVal, newVal) {
			if (oldVal != newVal) this.discount = "";
		}
	},
	methods: {
		fetchOrderRequests(page_url) {
			let vm = this;
			var search_param = (page_url ? "&" : "?") + $.param(this.search);
			page_url = page_url || "/api/order_request";
			page_url = page_url + search_param;
			this.processing = true;
			NProgress.start();
			axios
				.get(page_url)
				.then(res => {
					console.log(res);
					vm.order_requests = res.data.data;
					vm.makePagination(res.data);
				})
				.catch(err => console.log(err))
				.finally(() => {
					this.processing = false;
					if (this.showFilter) this.showFilter = false;
					NProgress.done();
				});
		},
		makePagination(data) {
			let pagination = {
				prev_page_url: data.prev_page_url,
				next_page_url: data.next_page_url,
				current_page: data.current_page,
				last_page: data.last_page
			};
			this.pagination = pagination;
		},
		navigatePagination(page_url) {
			if (page_url) {
				this.fetchOrderRequests(page_url);
			}
			return false;
		},
		selectOrderRequest(order) {
			if (this.selected_order.id != order.id) {
				this.selected_order = order;
				this.hasSelectedOrder = true;
			} else {
				this.clearSelectedOrder();
			}
		},
		clearSelectedOrder() {
			this.selected_order = {};
			this.hasSelectedOrder = false;
			this.showFilter = false;
			this.showSearch = false;
		},
		handleMainMenuEvent(showMenu) {
			this.mainMenu = showMenu;
			this.showSearch = this.showSearch ? !showMenu : this.showSearch;
			if (showMenu) {
				this.clearSelectedOrder();
			}
		},
		fetchOrderStatus() {
			axios
				.get("/api/order_status")
				.then(res => {
					this.orderStatus = res.data;
				})
				.catch(err => console.log(err));
		},
		changeSelectedStatus(status) {
			this.selectedStatus = status;
			//console.log(this.selectedStatus);
		},
		confirmupdateOrderStatus() {
			if (this.selected_order.has_expired) {
				this.ignoreExpiration = true;
				this.$eventBus.$emit("popup", {
					message:
						"Order request already has expired. Are you sure you still want to update its status?",
					type: "confirm"
				});
			} else {
				this.ignoreExpiration = false;
				this.updateOrderStatus(true);
			}
		},
		updateOrderStatus(confirmation) {
			if (confirmation) {
				let vm = this;
				/*if (
					this.orderStatusRule(
						this.selected_order,
						this.selectedStatus,
						this.ignoreExpiration
					)
				) {*/
				this.processing = true;
				NProgress.start();
				axios
					.post("/api/order_request/update", {
						id: this.selected_order.id,
						status: this.selectedStatus,
						update_type: "status"
					})
					.then(res => {
						if (res) {
							vm.$eventBus.$emit("popup", {
								message: "Order status has been updated."
							});
							vm.fetchOrderRequests();
							vm.selected_order = res.data;
							vm.editStatus = false;
							vm.showModals = false;
						}
					})
					.catch(err => console.log(err))
					.finally(() => {
						this.processing = false;
						NProgress.done();
					});
				/*} else
					vm.$eventBus.$emit("popup", {
						message:
							"Status change failed. Please refer to order status rule.",
						type: "danger"
					}); */
			} else {
				this.ignoreExpiration = false;
			}
		},
		orderStatusRule(
			order_request,
			selected_status,
			ignore_expiration = true
		) {
			let status = +selected_status;
			switch (status) {
				case 1: // pending
					return [2, 5].includes(order_request.status) &&
						!order_request.has_expired &&
						!ignore_expiration
						? true
						: false;
					break;
				case 2: // confirmed
					return [1, 3].includes(order_request.status) &&
						(!order_request.has_expired && !ignore_expiration)
						? true
						: false;
					break;
				case 3: // shipped
					return [2, 4].includes(order_request.status) &&
						!order_request.has_expired &&
						!ignore_expiration
						? true
						: false;
					break;
				case 4: // delivered
					return order_request.status == 3 &&
						!order_request.has_expired &&
						!ignore_expiration
						? true
						: false;
					break;
				case 5: // cancelled
					return [1, 2].includes(order_request.status) ? true : false;
					break;
				default:
					return false;
					break;
			}
		},
		orderRequestStatusMarker(order_request) {
			if (order_request.has_expired) return "bg-red-200 border-red-300";
			else if ([1, 2].includes(order_request.status))
				return "bg-yellow-200 border-yellow-400";
			else if (order_request.status == 3)
				return "bg-blue-200 border-blue-300";
			else if (order_request.status == 4)
				return "bg-green-200 border-green-300";
			else if ([5].includes(order_request.status))
				return "bg-red-200 border-red-300";
			else return "bg-gray-200 border-gray-300";
		},
		updateShippingFee() {
			let shippingFee = this.shippingFee < 1 ? 0 : +this.shippingFee,
				vm = this;
			this.processing = true;
			NProgress.start();
			axios
				.post("/api/order_request/update", {
					id: this.selected_order.id,
					column: "shipping_fee",
					value: this.shippingFee,
					update_type: "order_data"
				})
				.then(res => {
					//console.log(res);
					if (res.data.status) {
						vm.$eventBus.$emit("popup", {
							message: res.data.message
						});
						vm.fetchOrderRequests();
						vm.selected_order = res.data.order_request;
						vm.editShippingFee = false;
						vm.showModals = false;
						vm.shippingFee = "";
					}
				})
				.catch(err => console.log(err))
				.finally(() => {
					this.processing = false;
					NProgress.done();
				});
		},
		updateDiscount() {
			let discount = this.discount < 1 ? 0 : +this.discount,
				vm = this;
			this.processing = true;
			NProgress.start();
			axios
				.post("/api/order_request/update", {
					id: this.selected_order.id,
					column: "discount",
					discount_type: this.discountType,
					value: this.discount,
					update_type: "order_data"
				})
				.then(res => {
					//console.log(res);
					if (res.data.status) {
						vm.$eventBus.$emit("popup", {
							message: res.data.message
						});
						vm.fetchOrderRequests();
						vm.selected_order = res.data.order_request;
						vm.editDiscount = false;
						vm.showModals = false;
						vm.discount = "";
					}
				})
				.catch(err => console.log(err))
				.finally(() => {
					this.processing = false;
					NProgress.done();
				});
		},
		searchFilter(status = null) {
			this.clearSelectedOrder();
			if (status == "expired") {
				this.search.filter = "expired";
				this.search.filter_name = "Expired";
			} else if (status) {
				this.search.filter = status.id;
				this.search.filter_name = status.status;
			} else {
				this.search.filter = "all";
				this.search.filter_name = "All";
			}
		},
		printPDF() {
			let base_uri = window.location.origin;
			window.open(
				base_uri +
					"/order_request/print/" +
					this.selected_order.tracking_no,
				"_blank"
			);
		},
		refreshData() {
			this.search.search_term = "";
			this.search.filter = "all";
			this.search.filter_name = "All";
		},
		handleAction(method) {
			if (method) {
				this.showModals = true;
				this[method] = !this[method];
			} else return;
		}
	}
};
</script>
