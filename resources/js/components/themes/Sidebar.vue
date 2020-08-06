<template>
  <b-sidebar
    position="static"
    mobile="reduce"
    type="is-light"
    open
    fullheight
    expand-on-hover
  >
    <div class="px-1 py-1">
      <div class="block">
        <img
          src="https://raw.githubusercontent.com/buefy/buefy/dev/static/img/buefy-logo.png"
          alt="Lightweight UI components for Vue.js based on Bulma"
        />
      </div>
      <b-menu>
        <b-menu-list label="Menu">
          <b-menu-item
            v-for="(menu, key) of menus"
            :key="key"
            :icon="menu.icon"
            tag="router-link"
            :to="menu.to"
            :label="menu.title"
          >
          </b-menu-item>
        </b-menu-list>
        <b-menu-list label="Action">
          <b-menu-item
            icon="logout"
            label="Logout"
            @click="logout()"
          ></b-menu-item>
        </b-menu-list>
      </b-menu>
    </div>
  </b-sidebar>
</template>

<script>
import $axios from '../../api'

export default {
  data() {
    return {
      menus: [
        {
          title: 'Home',
          icon: 'home',
          to: { name: 'home' },
        },
      ],
    }
  },
  methods: {
    async logout() {
      await $axios.get('/sanctum/csrf-cookie').then(() => {
        $axios.post('/logout').then((response) => {
          if (response.data.success) {
            this.$buefy.toast.open({
              message: response.data.message,
              type: 'is-success',
            })
          }
          this.$router.push({ name: 'login' })
        })
      })
    },
  },
}
</script>
