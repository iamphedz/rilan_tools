<template>
	<transition name="fade-in">
		<div class="popup-container" v-if="hasMessage">
			<div class="wrapper w-full md:w-64">
				<div
					class="header"
					v-bind:class="{
						danger: popup.type === 'danger',
						default: popup.type === 'default',
						confirm: popup.type === 'confirm'
					}"
				>
					<span>{{ popup.header }}</span>
					<button @click="hasMessage = !hasMessage">
						<icon name="times" />
					</button>
				</div>
				<p class="content">
					{{ popup.message }}
				</p>
				<div
					class="buttons"
					v-if="popup.type === 'default'"
					v-bind:class="{
						danger: popup.type === 'danger',
						default: popup.type === 'default',
						confirm: popup.type === 'confirm'
					}"
				>
					<button @click="hasMessage = false">
						OK
					</button>
				</div>
				<div
					class="buttons"
					v-else
					v-bind:class="{
						danger: popup.type === 'danger',
						default: popup.type === 'default',
						confirm: popup.type === 'confirm'
					}"
				>
					<button @click="emitPopupConfirmation(true)">
						Yes
					</button>
					<button @click="emitPopupConfirmation(false)">
						No
					</button>
				</div>
			</div>
			<div class="bg-fader" @click="hasMessage = false"></div>
		</div>
	</transition>
</template>
<script>
export default {
	data() {
		return {
			popup: {
				header: "Header",
				message: "Message",
				type: "default"
			},
			hasMessage: false
		};
	},
	watch: {
		hasMessage: _.debounce(function(e) {
			if (this.hasMessage && this.popup.type !== "confirm") {
				this.hasMessage = false;
				this.popup = {
					header: "",
					message: "",
					type: ""
				};
			}
		}, 5000),
		popup: {
			handler: function(val, oldVal) {
				if (this.popup.message.length > 0) {
					this.hasMessage = true;
				} else {
					this.hasMessage = false;
				}
			},
			deep: true
		}
	},
	mounted() {
		this.$eventBus.$on("popup", this.renderPopupMessage);
	},
	methods: {
		renderPopupMessage(message) {
			this.popup = {
				header: message.header ? message.header : "Message",
				message: message.message,
				type: message.type ? message.type : "default"
			};
		},
		emitPopupConfirmation(action) {
			console.log(action);
			this.$eventBus.$emit("popup-confirmation", action);
			this.hasMessage = false;
		}
	}
};
</script>
