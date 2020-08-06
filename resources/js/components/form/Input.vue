<template>
  <ValidationProvider v-slot="{ errors }" :name="name">
    <b-field
      :label="label"
      :type="{ 'is-danger': errors[0] }"
      :message="errors[0]"
    >
      <b-input
        :value="value"
        :type="type"
        :icon="icon"
        :placeholder="placeholder ? placeholder : label"
        :password-reveal="type == 'password'"
        @input="$emit('input', $event)"
      ></b-input>
    </b-field>
  </ValidationProvider>
</template>

<script>
export default {
  name: 'FormInput',
  props: {
    label: {
      type: String,
      default: 'Name',
    },
    name: {
      type: String,
      default: 'name',
    },
    type: {
      type: String,
      default: 'text',
    },
    value: {
      type: String,
      default: '',
    },
    icon: {
      type: String,
      default: '',
    },
    placeholder: {
      type: String,
      default: '',
    },
  },
}
</script>
