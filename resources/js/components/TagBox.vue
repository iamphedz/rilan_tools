<template>
  <div class="relative flex flex-col mb-6 w-full">
    <div
      id="custom-input"
      class="flex flex-row items-center rounded-t px-1 border-b focus-within:bg-gray-200"
      v-bind:class="{
        'border-blue-400': active && inputError.length < 1,
        'border-red-600': active && inputError.length > 0
      }"
    >
      <slot name="leading-icon"></slot>
      <div class="relative flex flex-col w-full" @click="focusOnTag">
        <div class="relative flex flex-col p-1">
          <input
            style="display: none;"
            type="text"
            v-bind:name="name"
            v-bind:value="tags"
          />
          <label
            for="input"
            class="this-input-label absolute text-sm z-10 text-gray-500"
            v-bind:class="activeLabel"
            >{{ label }}{{ hasError }}</label
          >
          <div class="z-20 mt-6 flex flex-row flex-wrap items-center">
            <span
              v-bind:class="tagclass"
              v-for="(tag, index) in tags"
              v-bind:key="index"
            >
              <span class="mr-3">{{ tag }}</span>
              <span
                class="cursor-pointer text-xs font-semibold border-l border-blue-300 px-1"
                @click="removeTag(tag)"
              >
                x
              </span>
            </span>
            <div>
              <input
                id="tag_input"
                v-bind:type="
                  type !== 'text' || type !== 'number' ? 'text' : type
                "
                class="this-input w-full bg-transparent focus:outline-none p-1"
                v-bind:class="{ 'text-red-600': inputError.length > 0 }"
                v-model="tag_input"
                @focus="active = true"
                @blur="validateOnBlur()"
                @keyup="inputError = ''"
                v-on:keyup.188="addTag()"
                v-on:keydown.8="returnLastTag($event)"
              />
            </div>
          </div>
        </div>
      </div>
      <slot name="trailing-icon"></slot>
    </div>
    <div class="flex relative">
      <transition name="fade-right">
        <div
          v-if="active && inputError.length < 1"
          class="this-helper absolute top-0 text-gray-600 text-xs font-thin p-1 flex w-full"
        >
          <slot name="helper"></slot>
        </div>
      </transition>
      <transition name="fade-right">
        <div
          v-if="inputError.length > 0"
          class="this-error absolute top-0 text-red-600 text-xs font-thin p-1 flex w-full"
        >
          {{ inputError }}
        </div>
      </transition>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      tags: [],
      tag_input: "",
      active: false,
      hasInput: false,
      inputError: ""
    };
  },
  computed: {
    activeLabel() {
      return {
        "text-xs font-semibold mt-0": this.active,
        "text-base mt-8": !this.active && this.tags.length < 1,
        "text-red-600": this.inputError.length > 0,
        "text-blue-500": this.inputError.length < 1 && this.active,
        "text-gray-500": this.inputError.length < 1 && !this.active
      };
    },
    hasError() {
      return this.inputError.length > 0 ? "*" : "";
    }
  },
  watch: {
    error() {
      this.inputError = this.error ? this.error[0] : "";
    },
    tags(value) {
      this.$emit("input", value);
    }
  },
  methods: {
    validateOnBlur() {
      this.active = false;
      if (this.inputError.length > 0 && this.tag_input.length > 0) {
        this.inputError = "";
      }
    },
    addTag() {
      let value = this.tag_input;
      let index = this.tags.findIndex(tag => tag === value);
      if (index == -1) {
        setTimeout(() => {
          if (this.maxtag > this.tags.length) {
            this.tags.push(this.stripCommaTag(value));
            this.tag_input = "";
          } else {
            this.inputError = `Maximum tag count reached (${this.maxtag})`;
            this.tag_input = this.stripCommaTag(value);
          }
        }, 100);
      } else this.tag_input = this.stripCommaTag(this.tag_input);
    },
    removeTag(tag) {
      var index = this.tags.indexOf(tag);
      if (index > -1) this.tags.splice(index, 1);
    },
    stripCommaTag(value) {
      return value.substr(0, value.length - 1);
    },
    focusOnTag() {
      document.getElementById("tag_input").focus();
    },
    returnLastTag(e) {
      if (this.tags.length > 0 && this.tag_input.length < 1) {
        e.preventDefault();
        var tag = this.tags.slice(-1).pop();
        this.tag_input = tag;
        this.tags.pop();
      }
    }
  },
  props: {
    name: {
      type: String,
      default() {
        return Math.random()
          .toString(36)
          .substr(2);
      }
    },
    label: {
      type: String,
      default() {
        return "Input Label";
      }
    },
    type: {
      type: String,
      default() {
        return "text";
      }
    },
    error: Array,
    maxlength: {
      type: Number | String,
      default() {
        return 255;
      }
    },
    maxtag: {
      type: Number | String,
      default() {
        return 10;
      }
    },
    tagclass: {
      type: String,
      default() {
        return "bg-blue-500 text-white rounded p-1 mr-1 mb-1 flex cursor-default items-center justify-center";
      }
    },
    value: String | Number
  }
};
</script>
<style scoped>
#custom-input {
  transition: background-color 0.3s ease-out;
  transition: border-color 0.2s ease-in;
}
.this-input-label {
  transition: all 0.4s ease;
}

.this-tags {
  transition: all 0.3s ease;
}

/* Enter and leave animations can use different */
/* durations and timing functions.              */
.fade-left-enter-active {
  transition: all 0.3s ease-out;
}
.fade-left-leave-active {
  transition: all 0.2s ease-in;
}
.fade-left-enter, .fade-left-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateX(20px);
  opacity: 0;
}

/* Enter and leave animations can use different */
/* durations and timing functions.              */
.fade-right-enter-active {
  transition: all 0.3s ease-out;
}
.fade-right-leave-active {
  transition: all 0.2s ease-in;
}
.fade-right-enter, .fade-right-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateX(-20px);
  opacity: 0;
}
</style>
