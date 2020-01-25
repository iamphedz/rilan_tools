<template>
  <div class="flex flex-row items-center cursor-pointer" v-on:click="$emit('input', !value)">
    <input
      type="hidden"
      v-bind:name="name ? name : id"
      v-bind:checked="value"
      class="opacity-0 z-50"
    />
    <div
      class="custom-checkbox flex w-4 h-4 border rounded items-center justify-center"
      v-bind:class="{'border-blue-500': value, 'border-gray-500': !value}"
    >
      <transition name="fade-in">
        <span
          v-bind:id="id"
          class="flex w-1 h-2 border-b-2 border-r-2 border-blue-500"
          style="transform: rotate(45deg);"
          v-if="value"
        ></span>
      </transition>
    </div>
    <span
      class="ml-1 text-sm"
      v-bind:class="{'text-black': value, 'text-gray-500': !value}"
    >{{label}}</span>
  </div>
</template>
<script>
export default {
  data() {
    return {
      id: ""
    };
  },
  computed: {
    hasValue() {
      return this.value;
    }
  },
  methods: {
    checkValue(e) {
      console.log(e);
    }
  },
  mounted() {
    this.id = Math.random()
      .toString(36)
      .substr(2);
  },
  props: ["value", "name", "label"]
};
</script>