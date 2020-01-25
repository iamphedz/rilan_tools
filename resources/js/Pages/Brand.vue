<template>
	<div class="py-6 flex flex-col">
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
		<div class="flex w-full justify-between">
			<span class="font-semibold text-xl">Product Brands</span>
			<button
				@click="showBrandForm = true"
				class="bg-green-900 text-white p-1 rounded"
				title="New Brand"
			>
				<icon name="plus" />
			</button>
		</div>
		<transition name="fade-up">
			<div
				class="flex flex-col fixed inset-0 w-full h-full z-40 md:items-center md:justify-center"
				v-if="showBrandForm"
			>
				<div
					class="absolute md:static flex w-full md:w-1/3 bottom-0 bg-white flex-col rounded-lg md:rounded overflow-hidden md:bottom-auto z-50"
				>
					<div
						class="font-semibold p-2 flex justify-center uppercase border-b bg-gray-200"
					>
						<span v-if="!editMode">New Brand</span>
						<span v-else>Edit Brand</span>
					</div>
					<form
						@submit.prevent="saveBrand"
						class="flex flex-col text-sm justify-start overflow-hidden"
					>
						<inputbox
							label="Brand Name"
							required="true"
							v-model="brand.brand_name"
							v-bind:error="errors.brand_name"
						></inputbox>

						<div
							class="flex flex-row justify-center bg-gray-200 p-2 mt-2"
						>
							<button
								class="bg-green-900 p-2  text-white w-1/3 rounded shadow"
							>
								Save
							</button>
							<input
								type="reset"
								class="bg-green-900 p-2 mx-2 text-white w-1/3 rounded shadow"
								value="Clear"
							/>

							<button
								@click.prevent="showBrandForm = false"
								class="bg-green-900 p-2 text-white w-1/3 rounded shadow"
							>
								Back
							</button>
						</div>
					</form>
				</div>

				<div
					class="absolute inset-0 w-full h-full bg-black opacity-25"
					@click="showBrandForm = !showBrandForm"
				></div>
			</div>
		</transition>
		<div class="flex flex-col my-4 text-sm">
			<transition-group name="fade-in">
				<button
					class="flex flex-row px-2 py-3 w-full border border-transparent text-gray-600 hover:text-black"
					v-bind:class="{
						'bg-gray-200 border-gray-400 ss-box-shadow':
							selected_brand.id == brand.id
					}"
					v-for="brand in brands"
					v-bind:key="brand.id"
					@click="selectBrand(brand)"
				>
					<span class="w-1/2 font-semibold text-black text-left">{{
						brand.brand_name
					}}</span>
					<div
						class="flex flex-wrap justify-end w-1/2 text-xs text-gray-600"
					>
						{{ brand.total_product | countProduct }}
					</div>
				</button>
			</transition-group>
		</div>
		<div v-if="!processing" class="flex w-full mb-2">
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
		<transition name="fade-up">
			<div
				class="flex flex-row fixed bottom-0 left-0 border-t bg-white w-full justify-between text-xs text-black z-20 items-start"
				v-if="hasSelectedBrand"
			>
				<button
					class="flex items-center flex-col py-2 w-1/2 justify-center"
					@click="editBrand"
				>
					<icon class="mb-1" name="edit" />Edit
				</button>
				<button
					class="flex items-center flex-col py-2 w-1/2 justify-center"
					@click="confirmDeleteBrand"
				>
					<icon class="mb-1" name="delete" />Delete
				</button>
			</div>
		</transition>
	</div>
</template>
<script>
import NProgress from "nprogress";
export default {
	data() {
		return {
			brand: {
				id: "",
				brand_name: ""
			},
			processing: false,
			showBrandForm: false,
			editMode: false,
			showModals: false,
			selected_brand: {},
			hasSelectedBrand: false,
			brands: [],
			pagination: {},
			errors: []
		};
	},
	mounted() {
		this.fetchBrands();
		this.$eventBus.$on("popup-confirmation", this.deleteBrand);
	},
	watch: {
		showBrandForm: function(e) {
			if (!this.showBrandForm) {
				this.clearForm();
			}
		}
	},
	filters: {
		countProduct: function(value) {
			let count = value > 0 ? value : "No";
			return (
				count +
				" product" +
				(value > 1 ? "s" : "") +
				(value > 0 ? "" : " yet")
			);
		}
	},
	methods: {
		fetchBrands(url) {
			let vm = this;
			let page_url = url || "/api/brands";
			this.processing = true;
			NProgress.start();
			axios
				.get(page_url)
				.then(res => {
					console.log(res);
					vm.brands = res.data.brands.data;
					vm.makePagination(res.data.brands);
				})
				.catch(err => console.log(err))
				.finally(() => {
					this.processing = false;
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
				this.fetchBrands(page_url);
			}
			return false;
		},
		editBrand() {
			this.brand.id = this.selected_brand.id;
			this.brand.brand_name = this.selected_brand.brand_name;
			this.showBrandForm = true;
			this.editMode = true;
		},
		clearForm() {
			this.brand = {
				id: "",
				brand_name: ""
			};
			this.editMode = false;
		},
		saveBrand() {
			let vm = this;
			let formData = new FormData();
			formData.append("brand_name", vm.brand.brand_name);
			if (this.editMode) {
				formData.append("id", vm.brand.id);
				this.processing = true;
				NProgress.start();
				axios
					.post("/api/brands/update", formData)
					.then(res => {
						if (res) {
							this.$eventBus.$emit("popup", {
								message: "Brand has been updated."
							});
							vm.showBrandForm = false;
							vm.fetchBrands();
						}
					})
					.catch(err => this.errorHandle(err))
					.finally(() => {
						NProgress.done();
						this.processing = false;
					});
				//
			} else {
				this.processing = true;
				NProgress.start();
				axios
					.post("/api/brands", formData)
					.then(res => {
						if (res) {
							this.$eventBus.$emit("popup", {
								message: "Brand has been added."
							});
							vm.showBrandForm = false;
							vm.fetchBrands();
						}
					})
					.catch(err => {
						this.errorHandle(err);
					})
					.finally(() => {
						NProgress.done();
						this.processing = false;
					});
			}
		},
		confirmDeleteBrand() {
			this.$eventBus.$emit("popup", {
				header: "Warning!",
				message:
					"Make sure all related products have changed their brand before deleting this or it will cause rendering error.",
				type: "danger"
			});
		},
		deleteBrand(confirmation) {
			if (confirmation) {
				this.processing = true;
				NProgress.start();
				axios
					.delete(`/api/brands/${this.selected_brand.id}`)
					.then(res => {
						this.$eventBus.$emit("popup", {
							message: "Brand has been deleted."
						});
						this.clearSelectedBrand();
						this.fetchBrands();
					})
					.catch(err => console.log(err))
					.finally(() => {
						NProgress.done();
						this.processing = false;
					});
			}
			return;
		},
		selectBrand(brand) {
			if (this.selected_brand.id != brand.id) {
				this.selected_brand = brand;
				this.hasSelectedBrand = true;
			} else {
				this.clearSelectedBrand();
			}
		},
		clearSelectedBrand() {
			this.selected_brand = {};
			this.hasSelectedBrand = false;
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
