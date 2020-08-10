<template>
  <b-tooltip label="Add data" type="is-dark">
    <b-button
      tag="router-link"
      :to="to"
      type="is-success"
      size="is-small"
      icon-left="plus"
      rounded
    >
      <span><slot>Add</slot></span>
    </b-button>
  </b-tooltip>
</template>

<script>
export default {
  name: 'AddButton',
  props: {
    to: {
      type: [String, Object],
      default: '/',
    },
  },
}
</script>
