<template>
	<div
		class="bg-yellow-400 p-2 w-full md:w-64 text-xs md:absolute md:top-0 md:right-0 rounded -mt-2 mr-2 z-50"
	>
		<form
			@submit.prevent
			class="flex flex-row rounded bg-white border-2 border-transparent focus-within:border-orange-300"
		>
			<input
				type="text"
				v-model="search_term"
				name="quick_search"
				class="ml-2 focus:outline-none w-full md:w-11/12 px-2 md:w-40 text-center"
				placeholder="Search Product"
				v-on:keyup.enter="quickSearch()"
			/>
			<button id="quickSearchSubmit" class="hidden absolute"></button>

			<img
				src="/images/magnifying-glass-308581_960_720.png"
				class="w-4 h-4 m-2"
			/>
		</form>
	</div>
</template>
<script>
export default {
	data() {
		return {
			search_term: "",
			searching: false
		};
	},
	methods: {
		quickSearch() {
			this.searching = true;

			let vm = this;
			let cURL = window.location.href;
			let qsearch = this.search_term;
			if (
				cURL.match(/products[?quick_search=][!(view|brand|category)]?/g)
			) {
				this.$eventBus.$emit("quick_search", this.search_term);
				this.searching = false;
			} else {
				fetch("/api/products/quick_search", {
					method: "post",
					body: JSON.stringify({ search_term: this.search_term }),
					headers: {
						"content-type": "application/json"
					}
				})
					.then(res => res.json())
					.then(res => {
						vm.searching = false;
						if (res) {
							if (res.count == 1) {
								window.location =
									window.location.origin + res.link;
							} else if (res.count > 1) {
								window.location =
									window.location.origin + res.link;
							} else {
								return;
							}
						}
					})
					.catch(err => console.log(err));
			}
		}
	}
};
</script>
