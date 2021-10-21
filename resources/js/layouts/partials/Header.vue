<template>
	<header class="bg-white px-4 pt-4">
		<div class="border-b flex justify-between items-center pb-4">
			<button
				aria-hidden="true"
				class="appearance-none focus:outline-none lg:hidden"
				@click="mobileToggle"
			>
				<span class="sr-only">Open side menu</span>
				<menu-icon
					aria-hidden="true"
					class="stroke-current text-gray-500 w-8 h-8"
				/>
			</button>
			<button
				aria-hidden="true"
				class="appearance-none focus:outline-none hidden lg:block text-gray-500 hover:text-gray-900"
				@click="desktopToggle"
			>
				<span class="sr-only">Open side menu</span>
				<menu-alt-1-icon
					aria-hidden="true"
					:class="[
						'stroke-current w-8 h-8',
						{
							block: isDesktopOpen,
							hidden: !isDesktopOpen,
						},
					]"
				/>
				<menu-icon
					aria-hidden="true"
					:class="[
						'stroke-current w-8 h-8',
						{
							hidden: isDesktopOpen,
							block: !isDesktopOpen,
						},
					]"
				/>
			</button>
			<headless-menu v-slot="{ open }" as="div" class="relative">
				<headless-menu-button
					class="inline-flex items-center gap-1 focus:outline-none"
				>
					<img
						:src="user.avatar"
						:alt="user.name"
						class="rounded-full w-10 h-10 object-cover"
					/>
					<span class="text-sm hidden md:block ml-1">{{ user.name }}</span>
					<chevron-down-icon
						aria-hidden="true"
						:class="[
							'w-4 h-4 stroke-current text-gray-400 hidden md:block duration-200',
							{ 'transform -rotate-180': open },
						]"
					/>
				</headless-menu-button>
				<transition
					enter-active-class="transition duration-100 ease-out"
					enter-from-class="transform scale-95 opacity-0"
					enter-to-class="transform scale-100 opacity-100"
					leave-active-class="transition duration-75 ease-in"
					leave-from-class="transform scale-100 opacity-100"
					leave-to-class="transform scale-95 opacity-0"
				>
					<headless-menu-items
						as="ul"
						class="
							absolute
							right-0
							origin-top-right
							rounded-md
							ring-1 ring-black ring-opacity-5
							shadow-lg
							flex flex-col
							text-sm
							bg-white
							overflow-hidden
							min-w-36
							gap-1
							focus:outline-none
							p-1
							z-10
						"
					>
						<headless-menu-item as="li" v-slot="{ active }">
							<router-link
								:to="{ path: '/setting/profile' }"
								:class="[
									'py-1.5 px-3 rounded-md block',
									{ 'bg-primary text-indigo-100': active },
								]"
							>
								Profile
							</router-link>
						</headless-menu-item>
						<headless-menu-item as="li" v-slot="{ active }">
							<router-link
								:to="{ path: '/setting' }"
								:class="[
									'py-1.5 px-3 rounded-md block',
									{ 'bg-primary text-indigo-100': active },
								]"
							>
								Setting
							</router-link>
						</headless-menu-item>
						<headless-menu-item
							as="li"
							v-slot="{ active }"
							class="border-t pt-1"
						>
							<button
								:class="[
									'appearance-none py-1.5 px-3 rounded-md text-left w-full',
									{ 'bg-primary text-indigo-100': active },
								]"
								@click="signOut"
							>
								Sign out
							</button>
						</headless-menu-item>
					</headless-menu-items>
				</transition>
			</headless-menu>
		</div>
	</header>
</template>

<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';
import {
	Menu as HeadlessMenu,
	MenuButton as HeadlessMenuButton,
	MenuItems as HeadlessMenuItems,
	MenuItem as HeadlessMenuItem,
} from '@headlessui/vue';
import {
	ChevronDownIcon,
	MenuIcon,
	MenuAlt1Icon,
} from '@heroicons/vue/outline';

import useMenu from '@/composable/useMenu';
import useSignOut from '@/composable/useSignOut';

const store = useStore();
const user = computed(() => store.getters['auth/user']);
const { isMobileOpen, isDesktopOpen, mobileToggle, desktopToggle } = useMenu();
const { signOut } = useSignOut();
</script>

<script>
import { defineComponent } from 'vue';

export default defineComponent({
	name: 'Header',
});
</script>
