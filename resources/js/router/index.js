import Vue from 'vue'
import Router from 'vue-router'
import store from '@/js/store'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  linkActiveClass: 'is-active',
  routes: [
    {
      path: '/login',
      component: () => import('@/js/pages/auth/login.vue'),
      name: 'login',
    },
    {
      path: '/',
      component: () => import('@/js/layouts/default.vue'),
      children: [
        {
          path: '',
          component: () => import('@/js/pages/index.vue'),
          name: 'home',
        },
        {
          path: '/account',
          children: [
            {
              path: '',
              component: () => import('@/js/pages/account/index.vue'),
              name: 'account',
            },
            {
              path: 'me',
              component: () => import('@/js/pages/account/me.vue'),
              name: 'account-me',
            },
          ],
        },
      ],
    },
  ],
})

router.beforeEach((to, from, next) => {
  if (to.name !== 'login' && !store.getters['auth/authenticated'])
    next({ name: 'login' })
  else next()
})

export default router
