<template>
  <b-navbar wrapper-class="container is-fullhd" type="is-white">
    <template slot="brand">
      <b-navbar-item tag="router-link" :to="{ name: 'home' }">
        <strong>Portpoliwo</strong>
      </b-navbar-item>
    </template>
    <template slot="end">
      <b-dropdown position="is-bottom-left" append-to-body aria-role="menu">
        <a slot="trigger" class="navbar-item" role="button">
          <span>Menu</span>
          <b-icon icon="menu-down"></b-icon>
        </a>

        <b-dropdown-item custom aria-role="menuitem">
          <div class="level">
            <div class="level-item">
              <figure class="image is-96x96">
                <img class="is-rounded" :src="user.photo" />
              </figure>
            </div>
          </div>
          Logged as <b>{{ user.name }}</b>
        </b-dropdown-item>
        <hr class="dropdown-divider" />
        <b-dropdown-item has-link aria-role="menuitem">
          <router-link :to="{ name: 'account-me' }">
            <b-icon icon="account"></b-icon>
            My Account
          </router-link>
        </b-dropdown-item>
        <hr class="dropdown-divider" aria-role="menuitem" />
        <b-dropdown-item value="settings">
          <b-icon icon="settings"></b-icon>
          Settings
        </b-dropdown-item>
        <b-dropdown-item value="logout" aria-role="menuitem">
          <b-icon icon="logout"></b-icon>
          Logout
        </b-dropdown-item>
      </b-dropdown>
    </template>
  </b-navbar>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'Navbar',
  computed: {
    ...mapGetters({
      user: 'auth/user',
    }),
  },
}
</script>
