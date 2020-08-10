<template>
  <b-field grouped>
    <p class="control">
      <b-tooltip label="Save to database" type="is-dark">
        <b-button type="is-success" native-type="submit" rounded
          ><slot>Save</slot></b-button
        >
      </b-tooltip>
    </p>
  </b-field>
</template>

<script>
export default {
  name: 'SaveButton',
}
</script>
