<template>
  <div class="flex w-full justify-center p-5 flex-col">
    <form @submit.prevent="testInput">
      <inputbox
        v-model="input_text"
        label="Basic Text"
        name="input_text"
        required="true"
      ></inputbox>
      <div class="flex flex-row">
        <inputbox
          v-model="input_email"
          type="email"
          label="Email"
          name="input_email"
          required="true"
          class="w-1/2"
        ></inputbox>
        <inputbox
          v-model="input_password"
          type="password"
          label="Password"
          name="input_password"
          required="true"
          class="w-1/2"
        ></inputbox>
      </div>

      <div class="flex flex-row items-center justify-center">
        <selectbox
          v-model="select_box"
          label="Select"
          name="select_box"
          required="true"
          class="w-1/2"
        >
          <option value="1">Option 1</option>
          <option value="2">Option 2</option>
          <option value="3">Option 3</option>
          <option value="4">Option 4</option>
        </selectbox>
        <inputbox
          v-model="input_number"
          type="number"
          label="Number"
          name="input_number"
          required="true"
          class="w-1/2"
        ></inputbox>
      </div>
      <inputlist
        v-model="input_list"
        label="Input List"
        name="input_list"
        required="true"
      >
        <option value="List 1"></option>
        <option value="List 2"></option>
        <option value="List 3"></option>
        <option value="List 4"></option>
      </inputlist>

      <tagbox v-model="tags" label="Tags" name="tags"></tagbox>
      <textbox
        v-model="textarea"
        label="Text Area"
        name="textarea"
        required="true"
        class="mt-20"
      ></textbox>
      <button class="bg-blue-600 px-3 py-2 rounded text-white">Submit</button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      input_text: "",
      input_email: "",
      input_password: "",
      input_number: "",
      input_list: "",
      select_box: "",
      textarea: "",
      tags: []
    };
  },
  mounted() {
    //
  },
  methods: {
    testInput() {
      console.log(this.test);
    }
  }
};
</script>
