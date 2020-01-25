<template>
	<div class="flex flex-col px-2 py-4 w-full bg-white text-sm justify-center">
		<div class="w-full rounded mt-10 flex flex-col items-center">
			<span class="text-3xl font-semibold text-green-900 leading-normal"
				>Rilan Tool Supply</span
			>
			<span class="text-green-900 font-semibold"
				>Administrator Panel</span
			>
			<div class="flex flex-col items-center mt-10 leading-normal">
				<span class="text-gray-600">Current User</span>
				<span class="text-black"
					>{{ $parent.user.name }} ({{ $parent.user.username }})</span
				>
				<span class="text-gray-600">Email</span>
				<span class="text-black">{{ $parent.user.email }}</span>
			</div>
			<div class="flex flex-col items-center mt-10 leading-normal">
				<span class="text-gray-600">Notifications</span>
				<div class="flex flex-col items-center">
					<span>3 new order requests</span>
					<span>1 product out of stock</span>
					<span>5 email messages</span>
				</div>
			</div>
		</div>
		<div
			class="absolute bottom-0 left-0 w-full flex flex-col md:flex-row text-xs items-center text-gray-600 bg-gray-200 p-1 leading-normal md:justify-between"
		>
			<span>&copy; Rilan Tool Supply 2019. All rights reserved.</span>
			<span>Developed by: phedz.dev@gmail.com</span>
		</div>
	</div>
</template>
<script>
export default {
	data() {
		return {};
	}
};
</script>
