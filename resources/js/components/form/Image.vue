<template>
  <ValidationProvider v-slot="{ errors, valid }" :name="name">
    <figure v-if="value" class="image is-128x128">
      <img class="is-rounded" :src="value._url" />
    </figure>
    <b-field
      :label="label"
      :type="{ 'is-danger': errors[0], 'is-success': valid }"
      :message="[message, ...errors]"
      :horizontal="horizontal"
    >
      <b-field class="file">
        <b-upload :ref="value" :value="value" @input="$emit('input', $event)">
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
      type: [File],
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
  },
  watch: {
    value(val) {
      val._url = URL.createObjectURL(val)
    },
  },
}
</script>
