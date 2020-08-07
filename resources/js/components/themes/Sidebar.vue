<template>
  <section class="section">
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
          label="Log out"
          @click="signOut()"
        ></b-menu-item>
      </b-menu-list>
    </b-menu>
  </section>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

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
  computed: {
    ...mapGetters({
      authenticated: 'auth/authenticated',
      user: 'auth/user',
    }),
  },
  methods: {
    ...mapActions({
      logOut: 'auth/logOut',
    }),

    async signOut() {
      await this.logOut()
      this.$router.push({ name: 'login' })
    },
  },
}
</script>
