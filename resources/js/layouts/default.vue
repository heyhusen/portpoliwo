<template>
  <div>
    <Navbar />

    <section class="main-content">
      <div class="container is-fullhd">
        <div class="columns">
          <aside class="column is-2">
            <Sidebar />
          </aside>
          <div class="column is-10">
            <section class="section">
              <router-view />
            </section>
          </div>
        </div>
      </div>
    </section>

    <footer class="footer">
      <div class="container is-fullhd">
        <div class="content has-text-centered">
          Bulma
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
import Navbar from '../components/themes/Navbar.vue'
import Sidebar from '../components/themes/Sidebar.vue'

export default {
  name: 'AppLayout',
  components: {
    Navbar,
    Sidebar,
  },
}
</script>
