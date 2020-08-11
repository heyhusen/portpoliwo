import Vue from 'vue'
import Router from 'vue-router'
import store from '@/js/store'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  linkExactActiveClass: 'is-active',
  routes: [
    {
      path: '/login',
      component: () => import('@/js/pages/auth/login'),
      name: 'login',
    },
    {
      path: '/',
      component: () => import('@/js/layouts/default'),
      children: [
        {
          path: '',
          component: () => import('@/js/pages/index'),
          name: 'home',
        },
        {
          path: '/account',
          component: () => import('@/js/pages/account.vue'),
          children: [
            {
              path: '',
              component: () => import('@/js/pages/account/index'),
              name: 'account',
            },
            {
              path: 'create',
              component: () => import('@/js/pages/account/create'),
              name: 'account-create',
            },
            {
              path: 'me',
              component: () => import('@/js/pages/account/me'),
              name: 'account-me',
            },
            {
              path: ':id',
              component: () => import('@/js/pages/account/_id'),
              name: 'account-show',
            },
          ],
        },
      ],
    },
    {
      path: '*',
      component: () => import('@/js/pages/404'),
      name: '404',
    },
  ],
})

router.beforeEach((to, from, next) => {
  if (to.name !== 'login' && !store.getters['auth/authenticated'])
    next({ name: 'login' })
  else next()
})

export default router
