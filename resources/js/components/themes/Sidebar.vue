<template>
  <section class="section">
    <b-menu>
      <b-menu-list label="Menu">
        <b-menu-item
          v-for="(menu, key) of menus"
          :key="key"
          :icon="menu.icon"
          :tag="menu.to ? 'router-link' : 'a'"
          :to="menu.to"
          :label="menu.to === '' ? '' : menu.title"
        >
          <b-menu-item
            v-for="(subMenu, subKey) of menu.sub"
            :key="subKey"
            :icon="subMenu.icon"
            tag="router-link"
            :to="subMenu.to"
            :label="subMenu.title"
          >
          </b-menu-item>
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
import { userMixin } from '@/js/mixins/user'
import { logOutMixin } from '@/js/mixins/logOut'

export default {
  name: 'Sidebar',
  mixins: [userMixin, logOutMixin],
  data() {
    return {
      menus: [
        {
          title: 'Home',
          icon: 'home',
          to: '/',
        },
        {
          title: 'Work',
          icon: 'archive',
          sub: [
            {
              title: 'All Works',
              to: '/work',
            },
            {
              title: 'Category',
              to: '/category',
            },
            {
              title: 'Tag',
              to: '/tag',
            },
          ],
        },
        {
          title: 'Social Media',
          icon: 'account-network',
          to: '/social-media',
        },
        {
          title: 'Setting',
          icon: 'cog',
          sub: [
            {
              title: 'Site',
              to: '/setting',
            },
            {
              title: 'API Token',
              to: '/setting/token',
            },
          ],
        },
        {
          title: 'Account',
          icon: 'account',
          to: '/account',
        },
      ],
    }
  },
}
</script>
