<template>
  <b-field grouped>
    <p class="control">
      <b-tooltip label="Save to database" type="is-dark">
        <b-button :type="type" native-type="submit" :rounded="rounded"
          ><slot>Save</slot></b-button
        >
      </b-tooltip>
    </p>
  </b-field>
</template>

<script>
export default {
  name: 'SaveButton',
  props: {
    rounded: {
      type: Boolean,
      default: true,
    },
    type: {
      type: String,
      default: 'is-success',
    },
  },
}
</script>
