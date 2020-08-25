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
      name: '404',
    },
  ],
  /**
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
          path: '/category',
          component: () => import('@/js/pages/category.vue'),
          children: [
            {
              path: '',
              component: () => import('@/js/pages/category/index'),
              name: 'category',
            },
            {
              path: 'create',
              component: () => import('@/js/pages/category/create'),
              name: 'category-create',
            },
            {
              path: ':id',
              component: () => import('@/js/pages/category/_id'),
              name: 'category-show',
            },
          ],
        },
        {
          path: '/tag',
          component: () => import('@/js/pages/tag.vue'),
          children: [
            {
              path: '',
              component: () => import('@/js/pages/tag/index'),
              name: 'tag',
            },
            {
              path: 'create',
              component: () => import('@/js/pages/tag/create'),
              name: 'tag-create',
            },
            {
              path: ':id',
              component: () => import('@/js/pages/tag/_id'),
              name: 'tag-show',
            },
          ],
        },
        {
          path: '/work',
          component: () => import('@/js/pages/work.vue'),
          children: [
            {
              path: '',
              component: () => import('@/js/pages/work/index'),
              name: 'work',
            },
            {
              path: 'create',
              component: () => import('@/js/pages/work/create'),
              name: 'work-create',
            },
            {
              path: ':id',
              component: () => import('@/js/pages/work/_id'),
              name: 'work-show',
            },
          ],
        },
        {
          path: '/blog',
          component: () => import('@/js/pages/blog.vue'),
          children: [
            {
              path: '',
              component: () => import('@/js/pages/blog/post/index'),
              name: 'blog-post',
            },
            {
              path: '/blog/category',
              component: () => import('@/js/pages/blog/category.vue'),
              children: [
                {
                  path: '',
                  component: () => import('@/js/pages/blog/category/index'),
                  name: 'blog-category',
                },
                {
                  path: 'create',
                  component: () => import('@/js/pages/blog/category/create'),
                  name: 'blog-category-create',
                },
                {
                  path: ':id',
                  component: () => import('@/js/pages/blog/category/_id'),
                  name: 'blog-category-show',
                },
              ],
            },
            {
              path: '/blog/tag',
              component: () => import('@/js/pages/blog/tag.vue'),
              children: [
                {
                  path: '',
                  component: () => import('@/js/pages/blog/tag/index'),
                  name: 'blog-tag',
                },
                {
                  path: 'create',
                  component: () => import('@/js/pages/blog/tag/create'),
                  name: 'blog-tag-create',
                },
                {
                  path: ':id',
                  component: () => import('@/js/pages/blog/tag/_id'),
                  name: 'blog-tag-show',
                },
              ],
            },
          ],
        },
        {
          path: '/social-media',
          component: () => import('@/js/pages/social-media.vue'),
          children: [
            {
              path: '',
              component: () => import('@/js/pages/social-media/index'),
              name: 'social-media',
            },
            {
              path: 'create',
              component: () => import('@/js/pages/social-media/create'),
              name: 'social-media-create',
            },
            {
              path: ':id',
              component: () => import('@/js/pages/social-media/_id'),
              name: 'social-media-show',
            },
          ],
        },
        {
          path: '/setting',
          component: () => import('@/js/pages/setting.vue'),
          children: [
            {
              path: '',
              component: () => import('@/js/pages/setting/index'),
              name: 'setting',
            },
            {
              path: 'token',
              component: () => import('@/js/pages/setting/token'),
              name: 'setting-token',
            },
          ],
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
  **/
})

router.beforeEach((to, from, next) => {
  if (to.name !== 'login' && !store.getters['auth/authenticated'])
    next({ name: 'login' })
  else next()
})

export default router
