import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  routes: [
    {
      path: '/login',
      component: () => import('../pages/auth/login.vue'),
      name: 'login',
    },
    {
      path: '/',
      component: () => import('../layouts/default.vue'),
      children: [
        {
          path: '',
          component: () => import('../pages/index.vue'),
          name: 'home',
        },
      ],
    },
  ],
})

router.beforeEach((to, from, next) => {
  if (to.name !== 'login' && !window.Laravel.isLoggedin) next({ name: 'login' })
  else next()
})

export default router
