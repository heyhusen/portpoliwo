<template>
  <div>
    <div class="has-background-primary pt-1"></div>
    <b-navbar wrapper-class="container is-fullhd" type="is-white">
      <template slot="brand">
        <b-navbar-item tag="router-link" :to="{ name: 'home' }">
          <strong>{{ title }}</strong>
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
                  <img class="is-rounded" :src="user.avatar" />
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
          <b-dropdown-item has-link aria-role="menuitem">
            <router-link :to="{ name: 'setting' }">
              <b-icon icon="cog"></b-icon>
              Setting
            </router-link>
          </b-dropdown-item>
          <b-dropdown-item aria-role="menuitem" @click="signOut()">
            <b-icon icon="logout"></b-icon>
            Logout
          </b-dropdown-item>
        </b-dropdown>
      </template>
    </b-navbar>
  </div>
</template>

<script>
import { userMixin } from '@/js/mixins/user'
import { logOutMixin } from '@/js/mixins/logOut'

export default {
  name: 'Navbar',
  mixins: [userMixin, logOutMixin],
  data() {
    return {
      title: process.env.MIX_APP_NAME,
    }
  },
}
</script>
