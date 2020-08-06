<template>
  <b-navbar wrapper-class="container is-fullhd" type="is-primary" shadow>
    <template slot="brand">
      <b-navbar-item tag="router-link" :to="{ name: 'home' }">
        <h1 class="title has-text-white">Portpoliwo</h1>
      </b-navbar-item>
    </template>

    <template slot="end">
      <b-navbar-item tag="div">
        <div class="buttons">
          <a class="button is-primary">
            <strong>Sign up</strong>
          </a>
          <a class="button is-light">
            Log in
          </a>
        </div>
      </b-navbar-item>
    </template>
  </b-navbar>
</template>

<script>
export default {}
</script>
