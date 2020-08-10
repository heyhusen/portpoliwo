<template>
  <div class="buttons">
    <b-tooltip label="Data detail" type="is-dark">
      <b-button
        tag="router-link"
        :to="detailTo"
        size="is-small"
        type="is-success"
        icon-left="eye"
        rounded
      >
        Show
      </b-button>
    </b-tooltip>
    <b-tooltip v-if="edit == true" label="Edit data" type="is-dark">
      <b-button
        tag="router-link"
        :to="editTo"
        size="is-small"
        type="is-warning"
        icon-left="pencil-box"
        rounded
      >
        Edit
      </b-button>
    </b-tooltip>
  </div>
</template>

<script>
export default {
  name: 'ActionButton',
  props: {
    detailTo: {
      type: [String, Object],
      default: '/',
    },
    editTo: {
      type: [String, Object],
      default: '/',
    },
    edit: {
      type: Boolean,
      default: true,
    },
  },
}
</script>
