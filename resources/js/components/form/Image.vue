<template>
  <ValidationProvider v-slot="{ errors }" :name="name">
    <figure v-if="value || preview" class="image is-128x128">
      <img
        :class="{ 'is-rounded': rounded }"
        :src="file.url ? file.url : preview"
      />
    </figure>
    <b-field
      :label="label"
      :type="{ 'is-danger': errors[0] }"
      :message="[message, ...errors]"
      :horizontal="horizontal"
    >
      <b-field class="file">
        <b-upload :value="value" @input="$emit('input', $event)">
          <a class="button is-primary">
            <b-icon icon="upload"></b-icon>
            <span><slot>Select an image</slot></span>
          </a>
        </b-upload>
        <span v-if="value" class="file-name">{{ value.name }}</span>
      </b-field>
    </b-field>
  </ValidationProvider>
</template>

<script>
import { ValidationProvider } from 'vee-validate'

export default {
  name: 'FormImage',
  components: {
    ValidationProvider,
  },
  props: {
    label: {
      type: String,
      default: 'Photo',
    },
    name: {
      type: String,
      default: 'photo',
    },
    value: {
      type: [String, File],
      default: null,
    },
    message: {
      type: String,
      default: '',
    },
    horizontal: {
      type: Boolean,
      default: false,
    },
    rounded: {
      type: Boolean,
      default: true,
    },
    preview: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      file: {
        url: '',
      },
    }
  },
  watch: {
    value(v) {
      this.file.url = URL.createObjectURL(v)
    },
  },
}
</script>
