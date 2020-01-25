<template>
  <div class="w-full md:p-5 p-3 my-3">
    <form @submit.prevent="sendContactForm">
      <inputbox label="Name" type="text" v-model="contact_form.name" v-bind:error="errors.name">
        <span slot="helper">Optional</span>
      </inputbox>
      <inputbox
        label="Email"
        type="email"
        required="true"
        v-model="contact_form.email"
        v-bind:error="errors.email"
      ></inputbox>
      <textbox
        label="Message"
        required="true"
        v-model="contact_form.message"
        v-bind:error="errors.message"
      ></textbox>
      <div class="flex flex-col items-center w-full">
        <button
          class="bg-green-800 text-gray-200 font-semibold py-2 px-4 w-1/2 rounded mt-2 hover:bg-green-700"
        >SEND</button>
        <div class="p-2" v-if="sending">
          <i class="text-base fa fa-spinner fa-pulse fa-3x fa-fw"></i>
          Sending...
        </div>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      contact_form: {
        name: "",
        email: "",
        message: ""
      },
      errors: [],
      sending: false
    };
  },
  methods: {
    sendContactForm() {
      let vm = this;
      this.sending = true;
      fetch("/api/contact_form", {
        method: "post",
        body: JSON.stringify(vm.contact_form),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          alert(data);
          vm.resetFields();
          this.sending = false;
        })
        .catch(err => errorHandle(err));
    },
    resetFields() {
      this.contact_form.name = "";
      this.contact_form.email = "";
      this.contact_form.message = "";
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
