<template>
	<div
		:class="[
			'fixed lg:static inset-0 z-10 lg:z-0',
			{
				flex: isMobileOpen,
				hidden: !isMobileOpen,
				'lg:flex': isDesktopOpen,
				'lg:hidden': !isDesktopOpen,
			},
		]"
	>
		<div
			class="
				bg-white
				text-gray-600
				sm:border-r
				px-4
				py-6
				flex flex-col
				gap-6
				flex-1
				sm:flex-none sm:min-w-72
				lg:min-w-64
				relative
				h-screen'
			"
		>
			<button
				class="
					absolute
					right-2
					top-2
					z-20
					rounded-full
					border
					p-1
					focus:outline-none
					hover:bg-transparent
					duration-200
					focus:bg-transparent
					lg:hidden
				"
				@click="mobileToggle"
			>
				<x-icon aria-hidden="true" class="h-7 w-7 stroke-current" />
			</button>
			<router-link to="/">
				<Logo class="fill-current mx-auto max-w-44 text-primary" />
			</router-link>
			<nav class="overflow-y-auto py-2">
				<ul class="select-none space-y-2">
					<li v-for="(menu, key) of menus" :key="key" class="overflow-hidden">
						<router-link
							v-slot="{ href, navigate, isActive }"
							:to="{ path: menu.path }"
							custom
						>
							<disclosure
								v-if="menu.sub"
								v-slot="{ open }"
								:default-open="isActive"
							>
								<disclosure-button
									class="
										appearance-none
										w-full
										focus:outline-none
										flex
										gap-2
										items-center
										py-2
										px-3
										hover:bg-indigo-50 hover:text-gray-800
										rounded-md
										duration-200
									"
								>
									<component
										:is="menu.icon"
										aria-hidden="true"
										class="w-6 h-6 stroke-current"
									/>
									<span class="flex-1 text-left">{{ menu.title }}</span>
									<chevron-down-icon
										:class="{ 'transform rotate-180': open }"
										class="w-4 h-4"
									/>
								</disclosure-button>
								<transition
									enter-active-class="transition duration-100 ease-out"
									enter-from-class="transform scale-95 opacity-0"
									enter-to-class="transform scale-100 opacity-100"
									leave-active-class="transition duration-75 ease-out"
									leave-from-class="transform scale-100 opacity-100"
									leave-to-class="transform scale-95 opacity-0"
								>
									<disclosure-panel
										as="ul"
										class="
											text-sm
											border-l-2
											ml-5.5
											pl-2.5
											py-1
											space-y-1
											border-gray-100
										"
									>
										<li v-for="(subMenu, subKey) of menu.sub" :key="subKey">
											<router-link
												v-slot="{
													href: subHref,
													navigate: subNavigate,
													isActive: subIsActive,
												}"
												:to="{ path: subMenu.path }"
												custom
											>
												<a
													:href="subHref"
													class="
														hover:bg-indigo-50 hover:text-gray-800
														py-2
														px-3
														block
														rounded-md
													"
													:class="{
														'bg-indigo-100 text-indigo-800 duration-200':
															subIsActive,
													}"
													@click="subNavigate"
												>
													{{ subMenu.title }}
												</a>
											</router-link>
										</li>
									</disclosure-panel>
								</transition>
							</disclosure>
							<a
								v-else
								:href="href"
								class="
									gap-2
									flex
									items-center
									py-2
									px-3
									rounded-md
									hover:bg-indigo-50 hover:text-gray-800
									duration-200
								"
								:class="{ 'bg-indigo-100 text-indigo-800': isActive }"
								@click="navigate"
							>
								<component
									:is="menu.icon"
									aria-hidden="true"
									class="w-6 h-6 stroke-current"
								/>
								<span>{{ menu.title }}</span>
							</a>
						</router-link>
					</li>
				</ul>
				<div class="border-t border-gray-200 mt-4 pt-4">
					<button
						class="
							w-full
							py-2
							px-3
							duration-200
							inline-flex
							items-center
							rounded-md
							appearance-none
							focus:outline-none
							gap-1
							hover:bg-indigo-50 hover:text-gray-800
						"
						@click="signOut"
					>
						<logout-icon class="w-6 h-6" />
						<span>Sign out</span>
					</button>
				</div>
			</nav>
		</div>
		<div
			class="
				flex-1
				cursor-pointer
				bg-gray-500 bg-opacity-75
				hidden
				sm:block
				lg:hidden
				h-full
			"
			@click="mobileToggle"
		></div>
	</div>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import {
	ArchiveIcon,
	ChevronDownIcon,
	ClipboardCheckIcon,
	CogIcon,
	HomeIcon,
	LogoutIcon,
	XIcon,
	UserCircleIcon,
	UserGroupIcon,
} from '@heroicons/vue/outline';

import useMenu from '@/composable/useMenu';
import useSignOut from '@/composable/useSignOut';
import Logo from '@/components/Logo.vue';

const { isMobileOpen, mobileToggle, isDesktopOpen } = useMenu();
const { signOut } = useSignOut();

const menus = [
	{
		path: '/',
		title: 'Dashboard',
		icon: HomeIcon,
	},
	{
		path: '/portfolio',
		title: 'Portfolio',
		icon: ArchiveIcon,
		sub: [
			{
				path: '/portfolio/work',
				title: 'Works',
			},
			{
				path: '/portfolio/category',
				title: 'Category',
			},
			{
				path: '/portfolio/tag',
				title: 'Tag',
			},
		],
	},
	{
		path: '/blog',
		title: 'Blog',
		icon: ClipboardCheckIcon,
		sub: [
			{
				path: '/blog/post',
				title: 'Post',
			},
			{
				path: '/blog/category',
				title: 'Category',
			},
			{
				path: '/blog/tag',
				title: 'Tag',
			},
			{
				path: '/blog/page',
				title: 'Page',
			},
		],
	},
	{
		path: '/social-media',
		title: 'Social Media',
		icon: UserGroupIcon,
	},
	{
		path: '/setting',
		title: 'Setting',
		icon: CogIcon,
		sub: [
			{
				path: '/setting',
				title: 'General',
			},
			{
				path: '/setting/profile',
				title: 'Profile',
			},
			{
				path: '/setting/token',
				title: 'API Token',
			},
		],
	},
	{
		path: '/account',
		title: 'Account',
		icon: UserCircleIcon,
	},
];
</script>
