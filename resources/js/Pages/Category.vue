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
			<span class="font-semibold text-xl">Product Categories</span>
			<button
				@click="showCategoryForm = true"
				class="bg-green-900 text-white p-1 rounded"
				title="New Category"
			>
				<icon name="plus" />
			</button>
		</div>
		<transition name="fade-up">
			<div
				class="flex flex-col fixed inset-0 w-full h-full z-40 md:items-center md:justify-center"
				v-if="showCategoryForm"
			>
				<div
					class="absolute md:static flex w-full md:w-1/3 bottom-0 bg-white flex-col rounded-lg md:rounded overflow-hidden md:bottom-auto z-50"
				>
					<div
						class="font-semibold p-2 flex justify-center uppercase border-b bg-gray-200"
					>
						<span v-if="!editMode">New Category</span>
						<span v-else>Edit Category</span>
					</div>
					<form
						@submit.prevent="saveCategory"
						class="flex flex-col text-sm justify-start overflow-hidden"
					>
						<inputbox
							label="Category Name"
							required="true"
							v-model="category.category_name"
							v-bind:error="errors.category_name"
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
								@click.prevent="showCategoryForm = false"
								class="bg-green-900 p-2 text-white w-1/3 rounded shadow"
							>
								Back
							</button>
						</div>
					</form>
				</div>

				<div
					class="absolute inset-0 w-full h-full bg-black opacity-25"
					@click="showCategoryForm = !showCategoryForm"
				></div>
			</div>
		</transition>
		<div class="flex flex-col my-4 text-sm">
			<transition-group name="fade-in">
				<button
					class="flex flex-row p-2 text-gray-600 border border-transparent hover:text-black w-full"
					v-bind:class="{
						'bg-gray-200 border-gray-400 ss-box-shadow':
							selected_category.id == category.id
					}"
					v-for="category in categories"
					v-bind:key="category.id"
					@click="selectCategory(category)"
				>
					<span class="w-1/2 font-semibold text-black text-left">{{
						category.category_name
					}}</span>
					<div
						class="flex flex-wrap justify-end w-1/2 text-xs text-gray-600"
					>
						{{ category.total_product | countProduct }}
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
				<span class="p-2 cursor-pointer"
					>Page {{ pagination.current_page }} of
					{{ pagination.last_page }}</span
				>
				<span
					@click="navigatePagination(pagination.next_page_url)"
					class="p-2"
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
				v-if="hasSelectedCategory"
			>
				<button
					class="flex items-center flex-col py-2 w-1/2 justify-center"
					@click="editCategory"
				>
					<icon class="mb-1" name="edit" />Edit
				</button>
				<button
					class="flex items-center flex-col py-2 w-1/2 justify-center"
					@click="confirmdeleteCategory"
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
			category: {
				id: "",
				category_name: ""
			},
			processing: false,
			showCategoryForm: false,
			editMode: false,
			showModals: false,
			selected_category: {},
			hasSelectedCategory: false,
			categories: [],
			pagination: {},
			errors: []
		};
	},
	mounted() {
		this.fetchCategorys();
		this.$eventBus.$on("popup-confirmation", this.deleteCategory);
	},
	watch: {
		showCategoryForm: function(e) {
			if (!this.showCategoryForm) {
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
		fetchCategorys(url) {
			let vm = this;
			let page_url = url || "/api/categories";
			this.processing = true;
			NProgress.start();
			axios
				.get(page_url)
				.then(res => {
					console.log(res);
					vm.categories = res.data.categories.data;
					vm.makePagination(res.data.categories);
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
				this.fetchCategories(page_url);
			}
			return false;
		},
		editCategory() {
			this.category.id = this.selected_category.id;
			this.category.category_name = this.selected_category.category_name;
			this.showCategoryForm = true;
			this.editMode = true;
		},
		clearForm() {
			this.category = {
				id: "",
				category_name: ""
			};
			this.editMode = false;
		},
		saveCategory() {
			let vm = this;
			let formData = new FormData();
			formData.append("category_name", vm.category.category_name);
			if (this.editMode) {
				formData.append("id", vm.category.id);
				this.processing = true;
				NProgress.start();
				axios
					.post("/api/categories/update", formData)
					.then(res => {
						if (res) {
							this.$eventBus.$emit("popup", {
								message: "Category has been updated."
							});
							vm.showCategoryForm = false;
							vm.fetchCategorys();
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
					.post("/api/categories", formData)
					.then(res => {
						if (res) {
							this.$eventBus.$emit("popup", {
								message: "Category has been added."
							});
							vm.showCategoryForm = false;
							vm.fetchCategorys();
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
		confirmdeleteCategory() {
			this.$eventBus.$emit("popup", {
				header: "Warning!",
				message:
					"Make sure all related products have changed their category before deleting this or it will cause rendering error.",
				type: "danger"
			});
		},
		deleteCategory(confirmation) {
			if (confirmation) {
				this.processing = true;
				NProgress.start();
				axios
					.delete(`/api/categories/${this.selected_category.id}`)
					.then(res => {
						this.$eventBus.$emit("popup", {
							message: "Category has been deleted."
						});
						this.fetchCategorys();
					})
					.catch(err => console.log(err))
					.finally(() => {
						NProgress.done();
						this.processing = false;
					});
			}
			return;
		},
		selectCategory(category) {
			if (this.selected_category.id != category.id) {
				this.selected_category = category;
				this.hasSelectedCategory = true;
			} else {
				this.clearSelectedCategory();
			}
		},
		clearSelectedCategory() {
			this.selected_category = {};
			this.hasSelectedCategory = false;
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
