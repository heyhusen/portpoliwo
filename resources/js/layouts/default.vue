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
              <div class="columns is-multiline">
                <div class="column is-12">
                  <b-tooltip
                    v-if="$route.name !== 'home'"
                    label="Back to previous page"
                    type="is-dark"
                  >
                    <b-button
                      tag="a"
                      type="is-white"
                      size="is-small"
                      icon-left="arrow-left"
                      title="Back"
                      rounded
                      @click="$router.back()"
                    >
                      <span>Back</span>
                    </b-button>
                  </b-tooltip>
                </div>
                <div class="column is-12">
                  <slot />
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </section>

    <Footer />
  </div>
</template>

<script>
export default {
  name: 'Layout',
  components: {
    Navbar: () => import('@/js/components/themes/Navbar'),
    Sidebar: () => import('@/js/components/themes/Sidebar'),
    Footer: () => import('@/js/components/themes/Footer'),
  },
}
</script>
