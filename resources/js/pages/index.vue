<template>
  <Layout>
    <div class="columns is-multiline">
      <div class="column is-12">
        <section class="hero is-success is-bold">
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
      <div class="column is-12">
        <div v-if="!loading" class="tile is-ancestor">
          <div class="tile is-parent">
            <div class="tile is-child box has-text-centered">
              <router-link to="/portfolio" title="Portfolio">
                <p class="heading">Portfolio</p>
                <p class="title">{{ dashboard.portfolioWork.count }}</p>
              </router-link>
            </div>
          </div>
          <div class="tile is-parent">
            <div class="tile is-child box has-text-centered">
              <router-link to="/blog" title="Blog">
                <p class="heading">Blog</p>
                <p class="title">{{ dashboard.blogPost.count }}</p>
              </router-link>
            </div>
          </div>
          <div class="tile is-parent">
            <div class="tile is-child box has-text-centered">
              <router-link to="/social-media" title="Social Media">
                <p class="heading">Social Media</p>
                <p class="title">{{ dashboard.socialMedia.count }}</p>
              </router-link>
            </div>
          </div>
          <div class="tile is-parent">
            <div class="tile is-child box has-text-centered">
              <router-link to="/account" title="User">
                <p class="heading">User</p>
                <p class="title">{{ dashboard.user.count }}</p>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script>
import { mapGetters } from 'vuex'
import { api } from '@/js/api'

export default {
  name: 'Home',
  metaInfo: {
    title: 'Home',
  },
  data() {
    return {
      dashboard: {},
      loading: false,
    }
  },
  computed: {
    ...mapGetters({
      user: 'auth/user',
    }),
  },
  created() {
    this.fetchData()
  },
  methods: {
    async fetchData() {
      this.loading = true
      await api
        .get(`/`)
        .then(({ data: { data } }) => {
          this.dashboard = data
          this.loading = false
        })
        .catch(() => {
          this.dashboard = {}
          this.loading = false
        })
    },
  },
}
</script>
