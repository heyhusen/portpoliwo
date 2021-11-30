import { createRouter, createWebHistory } from 'vue-router';
import store from '../store';

const router = createRouter({
	history: createWebHistory(),
	routes: [
		{
			name: 'login',
			path: '/login',
			component: () => import('@/pages/login.vue'),
			meta: {
				public: true,
			},
		},
		{
			name: 'home',
			path: '/',
			component: () => import('@/pages/index.vue'),
		},
		{
			path: '/account',
			component: () => import('@/pages/account.vue'),
			children: [
				{
					name: 'account-index',
					path: '',
					component: () => import('@/pages/account/index.vue'),
				},
				{
					name: 'account-create',
					path: 'create',
					component: () => import('@/pages/account/create.vue'),
				},
				{
					name: 'account-id',
					path: ':id',
					component: () => import('@/pages/account/_id/index.vue'),
				},
			],
		},
		{
			path: '/setting',
			component: () => import('@/pages/setting.vue'),
			children: [
				{
					name: 'setting-index',
					path: '',
					component: () => import('@/pages/setting/index.vue'),
				},
				{
					name: 'setting-profile',
					path: 'profile',
					component: () => import('@/pages/setting/profile.vue'),
				},
				{
					name: 'setting-token',
					path: 'token',
					component: () => import('@/pages/setting/token.vue'),
				},
			],
		},
		{
			path: '/social-media',
			component: () => import('@/pages/social-media.vue'),
			children: [
				{
					name: 'social-media-index',
					path: '',
					component: () => import('@/pages/social-media/index.vue'),
				},
				{
					name: 'social-media-create',
					path: 'create',
					component: () => import('@/pages/social-media/create.vue'),
				},
				{
					name: 'social-media-id',
					path: ':id',
					component: () => import('@/pages/social-media/_id/index.vue'),
				},
			],
		},
		{
			path: '/blog',
			component: () => import('@/pages/blog.vue'),
			redirect: '/blog/post',
			children: [
				{
					path: 'post',
					component: () => import('@/pages/blog/post.vue'),
					children: [
						{
							name: 'blog-post-index',
							path: '',
							component: () => import('@/pages/blog/post/index.vue'),
						},
						{
							name: 'blog-post-trash',
							path: 'trash',
							component: () => import('@/pages/blog/post/trash.vue'),
						},
						{
							name: 'blog-post-create',
							path: 'create',
							component: () => import('@/pages/blog/post/create.vue'),
						},
						{
							name: 'blog-post-id',
							path: ':id',
							component: () => import('@/pages/blog/post/_id/index.vue'),
						},
					],
				},
				{
					path: 'category',
					component: () => import('@/pages/blog/category.vue'),
					children: [
						{
							name: 'blog-category-index',
							path: '',
							component: () => import('@/pages/blog/category/index.vue'),
						},
						{
							name: 'blog-category-create',
							path: 'create',
							component: () => import('@/pages/blog/category/create.vue'),
						},
						{
							name: 'blog-category-id',
							path: ':id',
							component: () => import('@/pages/blog/category/_id/index.vue'),
						},
					],
				},
				{
					path: 'tag',
					component: () => import('@/pages/blog/tag.vue'),
					children: [
						{
							name: 'blog-tag-index',
							path: '',
							component: () => import('@/pages/blog/tag/index.vue'),
						},
						{
							name: 'blog-tag-create',
							path: 'create',
							component: () => import('@/pages/blog/tag/create.vue'),
						},
						{
							name: 'blog-tag-id',
							path: ':id',
							component: () => import('@/pages/blog/tag/_id/index.vue'),
						},
					],
				},
				{
					path: 'page',
					component: () => import('@/pages/blog/page.vue'),
					children: [
						{
							name: 'blog-page-index',
							path: '',
							component: () => import('@/pages/blog/page/index.vue'),
						},
						{
							name: 'blog-page-trash',
							path: 'trash',
							component: () => import('@/pages/blog/page/trash.vue'),
						},
						{
							name: 'blog-page-create',
							path: 'create',
							component: () => import('@/pages/blog/page/create.vue'),
						},
						{
							name: 'blog-page-id',
							path: ':id',
							component: () => import('@/pages/blog/page/_id/index.vue'),
						},
					],
				},
			],
		},
		{
			path: '/portfolio',
			component: () => import('@/pages/portfolio.vue'),
			redirect: '/portfolio/work',
			children: [
				{
					path: 'work',
					component: () => import('@/pages/portfolio/work.vue'),
					children: [
						{
							name: 'portfolio-work-index',
							path: '',
							component: () => import('@/pages/portfolio/work/index.vue'),
						},
						{
							name: 'portfolio-work-trash',
							path: 'trash',
							component: () => import('@/pages/portfolio/work/trash.vue'),
						},
						{
							name: 'portfolio-work-create',
							path: 'create',
							component: () => import('@/pages/portfolio/work/create.vue'),
						},
						{
							name: 'portfolio-work-id',
							path: ':id',
							component: () => import('@/pages/portfolio/work/_id/index.vue'),
						},
					],
				},
				{
					path: 'category',
					component: () => import('@/pages/portfolio/category.vue'),
					children: [
						{
							name: 'portfolio-category-index',
							path: '',
							component: () => import('@/pages/portfolio/category/index.vue'),
						},
						{
							name: 'portfolio-category-create',
							path: 'create',
							component: () => import('@/pages/portfolio/category/create.vue'),
						},
						{
							name: 'portfolio-category-id',
							path: ':id',
							component: () =>
								import('@/pages/portfolio/category/_id/index.vue'),
						},
					],
				},
				{
					path: 'tag',
					component: () => import('@/pages/portfolio/tag.vue'),
					children: [
						{
							name: 'portfolio-tag-index',
							path: '',
							component: () => import('@/pages/portfolio/tag/index.vue'),
						},
						{
							name: 'portfolio-tag-create',
							path: 'create',
							component: () => import('@/pages/portfolio/tag/create.vue'),
						},
						{
							name: 'portfolio-tag-id',
							path: ':id',
							component: () => import('@/pages/portfolio/tag/_id/index.vue'),
						},
					],
				},
			],
		},
	],
});

router.beforeEach((to) => {
	if (!to.meta.public && !store.getters['auth/authenticated']) {
		return '/login';
	}
	return true;
});

export default router;
