<template>
  <div class="absolute inset-0 flex items-start justify-center z-10">
    <div class="flex flex-col bg-white w-full m-2 md:m-0 md:mt-20 md:w-1/2 rounded shadow-lg z-10">
      <div class="flex w-full justify-between bg-green-900 rounded-t font-semibold text-white">
        <span v-if="sendPayment" class="p-2">Send Payment Details</span>
        <span v-else class="p-2">Payment Details</span>
        <i class="fa fa-times p-2 cursor-pointer" @click="$emit('close')" aria-hidden="true"></i>
      </div>
      <div v-if="loading" class="p-3 flex w-full items-center justify-center">
        <div class="flex flex-row">
          <i class="fa fa-spinner fa-spin fa-3x fa-fw text-sm"></i>
          <span>Loading...</span>
        </div>
      </div>
      <div v-if="showPayment" class="p-3">
        <div class="md:flex my-2">
          <label
            for="tracking_no"
            class="md:w-1/3 font-semibold text-teal-700 flex items-start md:py-1"
          >ORT#</label>
          <span
            type="text"
            id="tracking_no"
            class="md:w-2/3 py-2 px-3 w-full mt-2 md:m-0 focus:outline-none"
          >{{payment.trackingNo}}</span>
        </div>
        <div class="md:flex my-2">
          <label
            for="paid_at"
            class="md:w-1/3 font-semibold text-teal-700 flex items-start md:py-1"
          >Date Sent</label>
          <span
            type="text"
            id="paid_at"
            class="md:w-2/3 py-2 px-3 w-full mt-2 md:m-0 focus:outline-none"
          >{{ payment.created_at | formatDate('dddd, MMMM DD, YYYY h:mm:ss a') }}</span>
        </div>
        <div class="md:flex my-2">
          <label
            for="payment_detail"
            class="md:w-1/3 font-semibold text-teal-700 flex items-start md:py-1"
          >Payment Detail</label>
          <div
            type="text"
            id="payment_detail"
            class="md:w-2/3 py-2 px-3 w-full mt-2 md:m-0 focus:outline-none"
          >{{ payment.details }}</div>
        </div>
        <div
          v-if="payment.order_request.status == 3"
          class="flex md:justify-end justify-between p-3 w-full"
        >
          <button
            @click="editPayment"
            class="py-2 px-3 md:w-auto w-1/2 rounded bg-green-900 hover:bg-green-800 text-white font-semibold md:ml-2"
          >Edit</button>
          <button
            @click.prevent="$emit('close')"
            class="py-2 px-3 md:w-auto w-1/2 rounded bg-red-800 hover:bg-red-700 text-white font-semibold ml-2"
          >Close</button>
        </div>
      </div>
      <form
        v-if="showForm"
        id="paymentForm"
        @submit.prevent="sendPaymentDetails"
        enctype="multipart/form-data"
      >
        <div class="p-3">
          <div class="md:flex my-2">
            <label
              for="tracking_no"
              class="md:w-1/3 font-semibold text-teal-700 flex items-start md:py-1"
            >ORT#</label>
            <span
              type="text"
              id="tracking_no"
              class="md:w-2/3 py-2 px-3 w-full mt-2 md:m-0 focus:outline-none"
            >{{payment.trackingNo}}</span>
          </div>
          <div class="md:flex my-2">
            <label
              for="payment_details"
              class="md:w-1/3 font-semibold text-teal-700 flex items-start md:py-1"
            >Payment Details</label>
            <textarea
              id="payment_details"
              rows="8"
              style="resize: none;"
              v-model="payment.details"
              class="md:w-2/3 py-2 px-3 w-full mt-2 md:m-0 focus:outline-none rounded border bg-gray-200 focus:bg-white focus:border-green-500"
              placeholder="Payment Details"
            ></textarea>
          </div>
          <div class="md:flex my-2">
            <label
              for="image"
              class="md:w-1/3 font-semibold text-teal-700 flex items-start md:py-1"
            >Image(Optional)</label>
            <input
              type="file"
              id="image"
              @change="setImage"
              class="md:w-2/3 py-2 px-3 w-full mt-2 md:m-0 focus:outline-none rounded border bg-gray-200"
            />
          </div>
        </div>
        <div class="flex md:justify-end justify-between p-3 w-full">
          <button
            type="submit"
            class="py-2 px-3 md:w-auto w-1/2 rounded bg-green-900 hover:bg-green-800 text-white font-semibold md:ml-2"
          >Send</button>
          <button
            @click.prevent="$emit('close')"
            class="py-2 px-3 md:w-auto w-1/2 rounded bg-red-800 hover:bg-red-700 text-white font-semibold ml-2"
          >Close</button>
        </div>
      </form>
    </div>
    <div class="absolute w-full h-full bg-black opacity-50 md:rounded-lg"></div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      payment: {
        id: "",
        order_request_id: "",
        trackingNo: "",
        details: "",
        images: [],
        created_at: "",
        order_request: {}
      },
      loading: false,
      showForm: false,
      sendPayment: false,
      showPayment: false,
      paymentDone: false,
      onEdit: false
    };
  },
  mounted() {
    this.$eventBus.$on("payment_details", this.handlePaymentEvents);
  },
  methods: {
    handlePaymentEvents(id, trackingNo) {
      this.payment.order_request_id = id;
      this.payment.trackingNo = trackingNo;
      this.sendPayment = false;
      this.showPayment = false;
      this.showForm = false;
      this.fetchPaymentDetails(id);
    },
    setImage(e) {
      this.payment.images = e.target.files[0];
    },
    editPayment() {
      this.onEdit = true;
      this.showForm = true;
      this.showPayment = false;
    },
    fetchPaymentDetails(order_request_id) {
      this.loading = true;
      fetch(`/api/payments/get_or_payment/${order_request_id}`)
        .then(res => res.json())
        .then(res => {
          console.log(res);
          if (res) {
            this.showPayment = true;
            this.payment.id = res.id;
            this.payment.order_request_id = res.order_request_id;
            this.payment.details = res.payment_details;
            this.payment.created_at = res.created_at;
            this.payment.trackingNo = res.order_request.tracking_no;
            this.payment.order_request = res.order_request;
          } else {
            this.sendPayment = true;
            this.showForm = true;
          }
          this.loading = false;
        })
        .catch(err => console.log(err));
    },
    sendPaymentDetails(e) {
      if (this.onEdit == false) {
        let formData = new FormData();
        let vm = this;

        formData.append("order_request_id", this.payment.order_request_id);
        formData.append("payment_details", this.payment.details);
        formData.append("images", this.payment.images);

        axios
          .post("/api/payments", formData, {
            "content-type": "multipart/form-data"
          })
          .then(res => {
            if (res) {
              alert(
                "Payment detail has been sent. Your order request status will be updated soon."
              );
              this.sendPayment = false;
              this.$emit("close");
            }
            this.paymentDone = false;
          })
          .catch(err => console.log(err));
      } else {
        console.log(this.payment);
        let formData = new FormData();
        let vm = this;
        formData.append("id", this.payment.id);
        formData.append("order_request_id", this.payment.order_request_id);
        formData.append("payment_details", this.payment.details);
        formData.append("images", this.payment.images);

        axios
          .post("/api/payments/update", formData, {
            "content-type": "multipart/form-data"
          })
          .then(res => {
            if (res) {
              alert("Payment detail has been updated.");
              this.onEdit = false;
              this.showForm = false;
              this.showPayment = true;
            }
          })
          .catch(err => console.log(err));
      }
    }
  }
};
</script>