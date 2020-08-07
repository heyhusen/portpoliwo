<template>
  <div class="columns is-multiline">
    <div class="column is-12">
      <section class="hero is-primary is-info">
        <div class="hero-body">
          <div class="container">
            <h1 class="title">Hello, {{ user.name }}.</h1>
            <h2 class="subtitle">
              I hope you are having a great day!
            </h2>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Home',
  computed: {
    ...mapGetters({
      user: 'auth/user',
    }),
  },
}
</script>
