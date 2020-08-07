<template>
  <div>
    <Navbar />

    <section class="main-content">
      <div class="container is-fullhd">
        <div class="columns">
          <aside class="column is-3">
            <Sidebar />
          </aside>
          <div class="column is-9">
            <section class="section">
              <router-view />
            </section>
          </div>
        </div>
      </div>
    </section>

    <Footer />
  </div>
</template>

<script>
import Navbar from '@/js/components/themes/Navbar.vue'
import Sidebar from '@/js/components/themes/Sidebar.vue'
import Footer from '@/js/components/themes/Footer.vue'

export default {
  name: 'AppLayout',
  components: {
    Navbar,
    Sidebar,
    Footer,
  },
}
</script>
