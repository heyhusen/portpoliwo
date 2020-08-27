import Vue from 'vue'
import Router from 'vue-router'
import routes from 'vue-auto-routing'
import store from '@/js/store'

Vue.use(Router)

const router = new Router({
  mode: 'history',
  linkExactActiveClass: 'is-active',
  routes: [
    ...routes,
    {
      path: '*',
      component: () => import('@/js/pages/404'),
      name: '404-error',
    },
  ],
})

router.beforeEach((to, from, next) => {
  if (to.name !== 'login' && !store.getters['auth/authenticated'])
    next({ name: 'login' })
  else next()
})

export default router
