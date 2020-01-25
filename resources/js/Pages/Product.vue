<template>
	<div class="py-6 flex flex-col" @click="clickEventHandle($event)">
		<div
			v-if="showSearch"
			class="absolute inset-0 w-full h-full"
			@click="showSearch = !showSearch"
		></div>
		<div
			class="product-settings fixed bottom-0 right-0 overflow-hidden bg-gray-200 border-t z-20 flex flex-row"
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
					v-model="search_term"
					class="w-full h-full px-2 py-1 text-center"
					placeholder="Type here to search..."
				/>
			</div>
		</div>
		<div
			class="fixed bottom-0 left-0 overflow-hidden bg-gray-200 border-t flex flex-row z-10 rounded-tr-lg"
		>
			<button
				@click="search_term = ''"
				class="w-10 h-10 flex items-center justify-center text-white bg-green-900"
			>
				<icon name="refresh" />
			</button>
		</div>
		<transition name="fade-in">
			<div
				v-if="processing"
				class="fixed inset-0 w-full h-full justify-center items-center flex z-50"
			>
				<div
					class="bg-white text-black px-3 py-2 text-xs z-20 border rounded"
				>
					<span>Processing...</span>
				</div>
				<div
					class="absolute bg-black inset-0 w-full h-full opacity-25"
				></div>
			</div>
		</transition>
		<div class="flex w-full justify-between mb-4">
			<span class="font-semibold text-xl">Products</span>
			<div class="flex flex-row">
				<button
					@click="
						showModals = !showModals;
						newProduct();
					"
					class="bg-green-900 text-white p-1 rounded"
					title="New Product"
				>
					<icon name="plus" />
				</button>
			</div>
		</div>
		<transition name="fade-up">
			<div
				class="flex flex-col fixed inset-0 w-full h-full z-40 md:items-center md:justify-center"
				v-if="restocking"
			>
				<div
					class="absolute md:static flex w-full md:w-1/3 bottom-0 bg-white flex-col rounded-lg md:rounded overflow-hidden md:bottom-auto z-50"
				>
					<span
						class="font-semibold p-2 block text-center border-b uppercase bg-gray-200"
						>Restock Product</span
					>
					<div
						class="flex flex-col w-full p-2 justify-center items-center"
					>
						<span class="text-sm text-gray-700"
							>Input number of items to be added in the
							stock</span
						>
						<money
							v-model="restock_qty"
							required
							maxlength="3"
							v-bind="number"
							class="w-full px-2 py-1 rounded border-2 my-2 text-center bg-white"
						></money>
					</div>
					<div
						class="flex flex-row w-full text-sm border-t bg-gray-200 p-2"
					>
						<button
							@click="updateStock"
							class="w-1/2 p-2 rounded bg-green-900 text-white"
						>
							Confirm
						</button>
						<button
							@click="
								showModals = false;
								restock_qty = '';
								restocking = false;
							"
							class="w-1/2 ml-2 p-2 rounded bg-green-900 text-white"
						>
							Close
						</button>
					</div>
				</div>
				<div
					class="absolute inset-0 w-full h-full bg-black opacity-25"
					@click="
						showModals = false;
						restock_qty = '';
						restocking = false;
					"
				></div>
			</div>
			<div
				class="flex flex-col fixed inset-0 w-full h-full z-40 md:items-center md:justify-center"
				v-if="showImages"
			>
				<div
					class="absolute md:static flex w-full md:w-1/2 bottom-0 bg-white flex-col rounded-lg md:rounded overflow-hidden md:bottom-auto z-50"
				>
					<span
						class="font-semibold p-2 text-center border-b w-full uppercase bg-gray-200"
						>Product Images</span
					>
					<div class="flex flex-wrap w-full p-2 justify-center">
						<div
							class="relative w-36 mb-3 md:mr-3"
							v-if="selected_product.has_images"
							v-for="(image,
							index) in selected_product.image_paths"
							v-bind:key="index"
						>
							<img
								v-bind:src="image | img_path"
								alt=""
								class="w-full"
							/>
							<div
								@click="removeImage(image)"
								class="absolute top-0 right-0 bg-red-800 text-white w-4 h-4 flex rounded-full -mt-1 -mr-1 text-xs items-center justify-center z-20"
							>
								<icon name="times" />
							</div>
						</div>
						<div
							v-if="
								!selected_product.image_paths ||
									selected_product.image_paths.length < 6
							"
							class="relative w-36 h-36 mb-3 flex flex-col items-center justify-center text-gray-400"
							onclick="document.getElementById('imageUpload').click();"
						>
							<svg
								version="1.1"
								xmlns="http://www.w3.org/2000/svg"
								xmlns:xlink="http://www.w3.org/1999/xlink"
								x="0px"
								y="0px"
								viewBox="0 0 1000 1000"
								class="w-12 h-12 fill-current"
								enable-background="new 0 0 1000 1000"
								xml:space="preserve"
							>
								<metadata>
									Svg Vector Icons :
									http://www.onlinewebfonts.com/icon
								</metadata>
								<g>
									<path
										d="M383.6,313.6c0-38.7-31.4-70-70.1-70c-38.7,0-70,31.4-70,70c0,38.7,31.4,70.1,70,70.1C352.3,383.6,383.6,352.3,383.6,313.6L383.6,313.6z"
									/>
									<path d="M383.6,313.6" />
									<path
										d="M616.5,671.3h57.9v-57.9c0-42.6,25.6-79.3,62.1-95.9l-9.7-33.2V482h0.1l-63.3-191.4L442.4,576l-59.3-100.4L148,712.9h346.3h38.9C552.5,687.8,582.5,671.3,616.5,671.3z"
									/>
									<path
										d="M990,166.8C990,80.6,919.4,10,833.2,10H166.8C80.6,10,10,80.6,10,166.8v666.4C10,919.4,80.6,990,166.8,990h470.4v0c0.1,0,0.1,0,0.2,0c16.2,0,29.4-13.2,29.4-29.4c0-16.2-13.2-29.4-29.4-29.4c-0.1,0-0.1,0-0.2,0v0H166.8c-54.1,0-98-43.9-98-98V166.8c0-54.1,43.9-98,98-98h666.4c54.1,0,98,43.9,98,98v467.5c0,0.4-0.2,0.6-0.2,0.9c0,16.2,13.2,29.4,29.4,29.4c16.2,0,29.4-13.2,29.4-29.4c0-0.1-0.1-0.2-0.1-0.4h0.3V166.8z"
									/>
									<path
										d="M959.5,747.5H809.6V597.6c0-16.2-13.2-29.4-29.4-29.4c-16.2,0-29.4,13.2-29.4,29.4v149.9H600.9c-16.2,0-29.4,13.2-29.4,29.4c0,16.2,13.2,29.4,29.4,29.4h149.9v149.9c0,16.2,13.2,29.4,29.4,29.4c16.2,0,29.4-13.2,29.4-29.4V806.3h149.9c16.2,0,29.4-13.2,29.4-29.4C988.9,760.6,975.7,747.5,959.5,747.5z"
									/>
								</g>
							</svg>
							<span class="text-sm mt-2 mx-auto"
								>Upload Image</span
							>
							<div
								v-if="uploading"
								class="absolute inset-0 flex flex-row text-white text-sm items-center justify-center"
								style="background: rgba(0,0,0,0.5);"
							>
								<icon name="spinner_1" />
								<span class="ml-2">Uploading</span>
							</div>
						</div>
					</div>
					<div
						class="flex flex-row w-full text-sm justify-center p-2 border-t bg-gray-200"
					>
						<input
							type="file"
							id="imageUpload"
							class="hidden"
							@change="saveImage"
						/>
						<button
							@click="
								showModals = false;
								showImages = false;
							"
							class="w-36 p-2 rounded bg-green-900 text-white"
						>
							Close
						</button>
					</div>
				</div>
				<div
					class="absolute inset-0 w-full h-full bg-black opacity-25"
					@click="
						showModals = false;
						showImages = false;
					"
				></div>
			</div>
		</transition>
		<transition v-bind:name="editMode ? 'fade-up' : 'fade-left'">
			<div
				class="flex flex-col fixed inset-0 w-full h-full z-40 md:items-center md:justify-center"
				v-if="showProductForm"
			>
				<div
					class="absolute md:static flex flex-col w-full max-h-screen md:w-1/2 bottom-0 bg-white rounded-lg md:rounded overflow-hidden md:bottom-auto z-50"
				>
					<span
						v-if="editMode"
						class="font-semibold p-2 block text-center border-b uppercase bg-gray-200"
						>Edit Product</span
					>
					<span
						v-else
						class="font-semibold p-2 block text-center border-b uppercase bg-gray-200"
						>Add Product</span
					>
					<form>
						<div
							class="px-2 flex flex-col w-full max-h-screen overflow-y-auto text-sm justify-start overflow-x-hidden"
						>
							<selectbox
								label="Category"
								required="true"
								v-model="product.category_id"
								v-bind:error="errors.category_id"
							>
								<option
									v-bind:value="category.id"
									v-for="(category, index) in categories"
									v-bind:key="category.id"
									>{{ category.category_name }}</option
								>
							</selectbox>
							<selectbox
								label="Brand"
								required="true"
								v-model="product.brand_id"
								v-bind:error="errors.brand_id"
							>
								<option
									v-bind:value="brand.id"
									v-for="(brand, index) in brands"
									v-bind:key="brand.id"
									>{{ brand.brand_name }}</option
								>
							</selectbox>
							<inputbox
								label="Name"
								required="true"
								v-model="product.product_name"
								v-bind:error="errors.product_name"
							></inputbox>
							<inputbox
								label="Price"
								type="currency"
								required="true"
								maxlength="10"
								v-model="product.price"
								v-bind:error="errors.price"
							></inputbox>
							<inputbox
								label="Quantity"
								type="number"
								required="true"
								maxlength="3"
								v-model="product.qty"
								v-bind:error="errors.qty"
							></inputbox>
							<textbox
								label="Description"
								required="true"
								v-model="product.description"
								v-bind:error="errors.description"
							></textbox>
						</div>
						<div
							class="sticky bottom-0 flex flex-row justify-center w-full border-t bg-gray-200 p-2 text-sm"
						>
							<button
								@click.prevent="saveProduct"
								class="w-1/3 bg-green-900 py-2 px-3 rounded  text-white"
							>
								Save
							</button>
							<button
								class="w-1/3 bg-green-900 py-2 px-3 rounded mx-2 text-white"
								@click.prevent="clearFields()"
							>
								Clear
							</button>

							<button
								@click.prevent="
									showModals = false;
									showProductForm = false;
								"
								class="w-1/3 bg-green-900 py-2 px-3 rounded  text-white"
							>
								Close
							</button>
						</div>
					</form>
				</div>
				<div
					class="absolute inset-0 w-full h-full bg-black opacity-25"
					@click="
						showModals = false;
						showProductForm = false;
					"
				></div>
			</div>
		</transition>
		<transition-group name="fade-in">
			<div
				class="product-container flex flex-col w-full border-b border-t md:border text-sm mb-1 border-transparent overflow-hidden"
				v-for="product in products"
				v-bind:key="product.id"
				v-bind:class="{
					'bg-gray-200 border-gray-400 ss-box-shadow selected':
						selected_product.id == product.id
				}"
				@click="selectProduct(product)"
			>
				<span
					class="w-full font-semibold px-2 py-1"
					v-bind:class="{ 'py-2': selected_product.id == product.id }"
					>[#{{ product.id }}] {{ product.product_name }}</span
				>
				<div class="flex flex-col w-full text-xs bg-white px-2 pb-2">
					<div class="flex flex-row w-full">
						<div
							class="w-1/2 flex flex-col leading-tight text-left"
						>
							<span class="text-gray-500">Category/Brand</span>
							<span
								>{{ product.category_name }}/{{
									product.brand_name
								}}</span
							>
						</div>
						<div
							class="w-1/4 flex flex-col leading-tight text-center"
						>
							<span class="text-gray-500">Stock</span>
							<span>{{
								product.qty > 0 ? product.qty : "Out of Stock"
							}}</span>
						</div>
						<div
							class="w-1/4 flex flex-col leading-tight text-right"
						>
							<span class="text-gray-500">Unit Price</span>
							<span>{{ product.price | currency }}</span>
						</div>
					</div>
					<transition name="slide">
						<div
							class="flex flex-col w-full mt-1 leading-tight"
							v-if="selected_product.id == product.id"
						>
							<span class="text-gray-500">Description</span>
							<p>{{ product.description }}</p>
						</div>
					</transition>
				</div>
			</div>
		</transition-group>
		<div v-if="products.length > 0" class="flex w-full mb-10 mt-4">
			<div
				class="flex flex-row mx-auto text-xs bg-white rounded overflow-hidden shadow"
			>
				<span
					@click="navigatePagination(pagination.prev_page_url)"
					class="p-2 cursor-pointer"
					v-bind:class="{
						'bg-gray-300 text-black': !pagination.prev_page_url,
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
						'bg-gray-300 text-black': !pagination.next_page_url,
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
				class="flex flex-col fixed bottom-0 left-0 border-t bg-white w-full justify-between text-xs text-black z-20 items-start"
				v-if="hasSelectedProduct"
			>
				<span
					v-if="selected_product.qty < 1"
					class="bg-red-200 text-red-900 flex w-full p-2 justify-center"
					>Product out of stock.</span
				>
				<div class="flex flex-row w-full">
					<button
						class="flex items-center flex-col py-2 w-1/5 justify-center"
						@click="
							showModals = !showModals;
							putOnSale = true;
						"
					>
						<svg
							version="1.1"
							id="Capa_1"
							xmlns="http://www.w3.org/2000/svg"
							xmlns:xlink="http://www.w3.org/1999/xlink"
							x="0px"
							y="0px"
							viewBox="0 0 25.772 25.772"
							style="enable-background:new 0 0 25.772 25.772;"
							xml:space="preserve"
							class="w-4 h-4 fill-current text-black"
						>
							<g>
								<g>
									<path
										style="fill:#030104;"
										d="M19.135,12.068h1.651c-0.067-0.805-0.332-1.146-0.456-1.266
			C19.833,10.327,19.292,11.115,19.135,12.068z"
									/>
									<path
										style="fill:#030104;"
										d="M10.052,14.449c0,0.493,0.164,0.799,0.429,0.799c0.425,0,0.562-0.483,0.601-0.69
			c0.022-0.104,0.034-0.214,0.034-0.313v-0.939C10.052,13.445,10.052,14.189,10.052,14.449z"
									/>
									<path
										style="fill:#030104;"
										d="M25.646,13.741l-1.519-2.396l0.9-2.689c0.123-0.367-0.029-0.77-0.364-0.963l-2.458-1.416
			l-0.453-2.801c-0.061-0.381-0.383-0.666-0.77-0.682l-2.834-0.111l-1.703-2.27c-0.232-0.311-0.65-0.414-0.998-0.246l-2.561,1.219
			l-2.562-1.219C9.976,0.001,9.558,0.105,9.326,0.413l-1.703,2.27L4.789,2.794C4.403,2.81,4.08,3.095,4.02,3.476L3.567,6.277
			L1.109,7.693C0.774,7.886,0.621,8.288,0.744,8.656l0.9,2.689l-1.518,2.396c-0.207,0.326-0.155,0.754,0.124,1.021l2.047,1.963
			l-0.23,2.826c-0.031,0.387,0.213,0.74,0.585,0.848l2.725,0.785l1.11,2.611c0.15,0.355,0.532,0.561,0.91,0.479l2.779-0.57
			l2.195,1.797c0.149,0.121,0.332,0.184,0.515,0.184s0.365-0.063,0.515-0.184l2.195-1.797l2.779,0.57
			c0.377,0.08,0.76-0.123,0.91-0.479l1.11-2.611l2.725-0.785c0.372-0.107,0.616-0.461,0.585-0.848l-0.23-2.826l2.047-1.963
			C25.801,14.495,25.853,14.068,25.646,13.741z M5.269,16.789c-0.584,0-1.186-0.183-1.534-0.467c-0.12-0.099-0.167-0.26-0.119-0.407
			l0.26-0.791c0.036-0.111,0.122-0.199,0.231-0.237c0.109-0.04,0.231-0.025,0.329,0.039c0.209,0.136,0.545,0.281,0.859,0.281
			c0.382,0,0.593-0.206,0.593-0.581c0-0.309-0.049-0.56-0.68-0.986c-0.634-0.4-1.488-1.07-1.488-2.18
			c0-1.256,0.94-2.203,2.188-2.203c0.48,0,0.928,0.129,1.369,0.396c0.15,0.091,0.218,0.275,0.16,0.442l-0.272,0.791
			c-0.038,0.11-0.125,0.197-0.236,0.233C6.817,11.156,6.696,11.14,6.6,11.073c-0.245-0.167-0.441-0.235-0.677-0.235
			c-0.437,0-0.471,0.361-0.471,0.472c0,0.245,0.029,0.394,0.656,0.823c0.649,0.418,1.526,1.119,1.526,2.315
			C7.635,15.849,6.684,16.789,5.269,16.789z M12.592,16.707c-0.004,0-0.007,0-0.01,0h-0.941c-0.158,0-0.296-0.099-0.35-0.24
			c-0.312,0.228-0.683,0.35-1.083,0.35c-1.119,0-1.931-0.95-1.931-2.259c0-1.559,1.09-2.612,2.808-2.759
			c-0.03-0.883-0.335-0.96-0.673-0.96c-0.32,0-0.615,0.093-0.878,0.275c-0.098,0.067-0.224,0.084-0.335,0.047
			c-0.113-0.039-0.201-0.129-0.236-0.243l-0.231-0.737c-0.049-0.154,0.008-0.322,0.14-0.417c0.353-0.253,1.033-0.507,1.759-0.507
			c1.03,0,2.259,0.471,2.259,2.709v2.865c0,0.564,0.018,1.016,0.053,1.344c0.021,0.047,0.034,0.102,0.034,0.157
			C12.976,16.542,12.783,16.72,12.592,16.707z M16.109,16.332c0,0.207-0.168,0.375-0.375,0.375h-1.051
			c-0.207,0-0.375-0.168-0.375-0.375V7.954c0-0.207,0.168-0.375,0.375-0.375h1.051c0.207,0,0.375,0.168,0.375,0.375V16.332z
			 M22.14,13.155c-0.016,0.194-0.178,0.346-0.374,0.346h-2.673c0.098,1.707,0.868,1.707,1.17,1.707c0.439,0,0.741-0.11,0.917-0.203
			c0.102-0.054,0.225-0.058,0.33-0.01c0.106,0.048,0.184,0.144,0.21,0.257l0.178,0.764c0.039,0.168-0.04,0.34-0.192,0.418
			c-0.424,0.221-1.049,0.356-1.633,0.356c-1.738,0-2.776-1.38-2.776-3.69c0-2.298,1.05-3.842,2.612-3.842
			c1.372,0,2.259,1.283,2.259,3.269C22.167,12.798,22.153,12.984,22.14,13.155z"
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
							<g></g></svg
						>Sale
					</button>
					<button
						class="flex items-center flex-col py-2 w-1/5 justify-center"
						@click="
							showModals = !showModals;
							restocking = true;
						"
					>
						<icon class="mb-1" name="plus" />Restock
					</button>
					<button
						class="flex items-center flex-col py-2 w-1/5 justify-center"
						@click="
							showModals = !showModals;
							showImages = !showImages;
						"
					>
						<icon class="mb-1" name="photos" />Images
					</button>
					<button
						class="flex items-center flex-col py-2 w-1/5 justify-center"
						@click="
							showModals = !showModals;
							editProduct(selected_product);
						"
					>
						<icon class="mb-1" name="edit" />Edit
					</button>
					<button
						class="flex items-center flex-col py-2 w-1/5 justify-center"
						@click="confirmDeleteProduct"
					>
						<icon class="mb-1" name="delete" />Delete
					</button>
				</div>
			</div>
		</transition>
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
			product: {
				id: "",
				product_name: "",
				product_images: "",
				category_id: "",
				brand_id: "",
				description: "",
				price: "",
				qty: ""
			},
			mainMenu: false,
			showModals: false,
			selected_product: {},
			hasSelectedProduct: false,
			selectedProductUpdated: false,
			processing: false,
			showProductForm: false,
			editMode: false,
			searching: false,
			restocking: false,
			restock_qty: "",
			showImages: false,
			uploadedImages: [],
			uploading: false,
			removingImage: false,
			showSearch: false,
			search_term: "",
			putOnSale: false,
			products: [],
			brands: [],
			categories: [],
			pagination: {},
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
			errors: []
		};
	},
	mounted() {
		this.fetchProducts();
		this.$eventBus.$on("popup-confirmation", this.deleteProduct);
		//this.$eventBus.$on("main_menu", this.handleMainMenuEvent);
	},
	watch: {
		showProductForm: function(e) {
			if (!this.showProductForm) {
				if (!this.editMode) this.clearForm();
			}
		},
		showModals: function(e) {
			if (this.showModals) {
				document.body.classList.add("overflow-hidden");
			} else {
				document.body.classList.remove("overflow-hidden");
				if (this.selectedProductUpdated) this.fetchProducts();
			}
		},
		errorHandle(err) {
			switch (err.response.status) {
				case 422:
					console.log(err.response.data.errors);
					this.errors = err.response.data.errors;
					break;
				default:
					console.log(err);
					break;
			}
		},
		uploadedImages: function(e) {
			if (this.uploadedImages) this.uploadImage();
		},

		search_term: _.debounce(function(e) {
			this.fetchProducts();
		}, 500)
	},
	filters: {
		trim(value) {
			let trimmedString = value.substring(0, 50);
			trimmedString += value.length > 50 ? " ..." : "";
			return trimmedString;
		}
	},
	methods: {
		newProduct() {
			this.showProductForm = true;
			this.clearForm();
		},
		selectProduct(product) {
			if (this.selected_product.id != product.id) {
				this.selected_product = product;
				this.hasSelectedProduct = true;
			} else {
				this.clearSelectedProduct();
			}
		},
		clearSelectedProduct() {
			this.selected_product = {};
			this.hasSelectedProduct = false;
		},
		handleMainMenuEvent(showMenu) {
			this.mainMenu = showMenu;
			this.showSearch = this.showSearch ? !showMenu : this.showSearch;
			if (showMenu) {
				this.clearSelectedProduct();
			}
		},
		fetchProducts(url) {
			let vm = this;
			let page_url = url || "/api/product_search";
			page_url = page_url + (url ? "&" : "?") + "per_page=10";
			page_url = this.search_term
				? page_url + "&search_term=" + this.search_term
				: page_url;
			this.processing = true;
			NProgress.start();
			axios
				.get(page_url)
				.then(res => {
					//console.log(res);
					vm.products = res.data.products.data;
					vm.brands = res.data.brands;
					vm.categories = res.data.categories;
					vm.makePagination(res.data.products);
					vm.selectedProductUpdated = false;
				})
				.catch(err => console.log(err))
				.finally(() => {
					this.processing = false;
					this.selected_product = {};
					this.hasSelectedProduct = false;
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
				this.fetchProducts(page_url);
			}
			return false;
		},
		updateImage(e) {
			this.product.product_images = e.target.files;
			console.log(this.product.product_images);
		},
		editProduct(product) {
			this.product.id = product.id;
			this.product.product_name = product.product_name;
			this.product.category_id = product.category_id;
			this.product.brand_id = product.brand_id;
			this.product.price = product.price;
			this.product.qty = product.qty;
			this.product.description = product.description;
			this.showProductForm = true;
			this.editMode = true;
		},
		clearFields() {
			this.product = {
				id: "",
				product_name: "",
				category_id: "",
				brand_id: "",
				description: "",
				price: 0,
				qty: 0
			};
		},
		clearForm() {
			this.clearFields();
			this.editMode = false;
			this.selected_product = {};
			this.hasSelectedProduct = false;
		},
		saveProduct() {
			let vm = this;
			let formData = new FormData();
			formData.append("product_name", vm.product.product_name);
			formData.append("category_id", vm.product.category_id);
			formData.append("brand_id", vm.product.brand_id);
			formData.append("price", vm.product.price);
			formData.append("qty", vm.product.qty);
			formData.append("description", vm.product.description);

			if (this.editMode) {
				formData.append("id", vm.product.id);
				this.processing = true;
				NProgress.start();
				axios
					.post("/api/products/update", formData)
					.then(res => {
						if (res) {
							vm.$eventBus.$emit("popup", {
								message: "Product has been updated."
							});
							vm.showProductForm = false;
							vm.showModals = false;
							vm.fetchProducts();
						}
					})
					.catch(err => this.errorHandle(err))
					.finally(() => {
						this.processing = false;
						NProgress.done();
					});
				//
			} else {
				this.processing = true;
				NProgress.start();
				axios
					.post("/api/products", formData)
					.then(res => {
						if (res) {
							vm.$eventBus.$emit("popup", {
								message: "Product has been saved."
							});
							vm.showProductForm = false;
							vm.showModals = false;
							vm.fetchProducts();
						}
					})
					.catch(err => {
						this.errorHandle(err);
					})
					.finally(() => {
						this.processing = false;
						NProgress.done();
					});
			}
		},
		confirmDeleteProduct() {
			this.$eventBus.$emit("popup", {
				header: "Confirm Action",
				message: "Are you sure you want to delete this product?",
				type: "confirm"
			});
		},
		deleteProduct(confirmation) {
			if (confirmation) {
				this.processing = true;
				let vm = this;
				NProgress.start();
				axios
					.delete(`/api/products/${this.selected_product.id}`)
					.then(res => {
						vm.$eventBus.$emit("popup", {
							message: "Product hase been deleted."
						});
						this.fetchProducts();
					})
					.catch(err => console.log(err))
					.finally(() => {
						this.processing = false;
						NProgress.done();
					});
			}
			return;
		},
		updateStock() {
			if (+this.restock_qty > 0) {
				let vm = this;
				this.processing = true;
				NProgress.start();
				axios
					.post("/api/products/restock", {
						id: this.selected_product.id,
						qty: this.restock_qty
					})
					.then(res => {
						if (res) {
							vm.$eventBus.$emit("popup", {
								message: "Product has been restocked."
							});
							vm.restock_qty = "";
							vm.showModals = false;
							vm.selectedProductUpdated = true;
						}
					})
					.catch(err => console.log(err))
					.finally(() => {
						this.processing = false;
						this.restocking = false;
						NProgress.done();
					});
			}
		},
		searchProduct() {
			this.searching = true;
		},
		uploadImage() {
			this.uploading = true;
			let vm = this,
				product = this.selected_product,
				formData = new FormData();

			formData.append("id", product.id);
			formData.append("image", vm.uploadedImages);
			NProgress.start();
			axios
				.post("/api/products/upload_image", formData, {
					headers: {
						"content-type": "multipart/form-data"
					}
				})
				.then(res => {
					if (res) {
						vm.$eventBus.$emit("popup", {
							message: "Image has been uploaded."
						});
						vm.selected_product = res.data;
						vm.selectedProductUpdated = true;
					}
				})
				.catch(err => console.log(err))
				.finally(() => {
					this.uploading = false;
					NProgress.done();
				});
		},
		saveImage(e) {
			this.uploadedImages = e.target.files[0];
		},
		removeImage(image) {
			if (confirm("Remove image of the product?")) {
				this.removingImage = true;
				let vm = this,
					product = this.selected_product,
					formData = new FormData();
				formData.append("id", product.id);
				formData.append("image_path", image);
				NProgress.start();
				axios
					.post("/api/products/remove_image", formData)
					.then(res => {
						//console.log(res);
						if (res) {
							vm.$eventBus.$emit("popup", {
								message: "Image has been removed."
							});
							vm.selected_product = res.data;
							vm.selectedProductUpdated = true;
							document.getElementById("imageUpload").value = "";
						} else {
							vm.$eventBus.$emit("popup", {
								message:
									"Something went wrong while deleting image from storage.",
								type: "danger"
							});
						}
					})
					.catch(err => console.log(err))
					.finally(() => {
						this.removingImage = false;
						NProgress.done();
					});
			}
		},
		clickEventHandle(e) {
			//console.log(e);
		},
		errorHandle(err) {
			switch (err.response.status) {
				case 422:
					console.log(err.response.data.errors);
					this.errors = err.response.data.errors;
					break;
				default:
					console.log(err);
					break;
			}
		}
	}
};
</script>
