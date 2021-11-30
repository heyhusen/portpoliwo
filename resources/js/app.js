import { createApp } from 'vue';
import {
	Config,
	Button,
	Field,
	Icon,
	Input,
	Inputitems,
	Loading,
	Modal,
	Notification,
	Pagination,
	Select,
	Skeleton,
	Table,
	Upload,
} from '@oruga-ui/oruga-next';
import { library } from '@fortawesome/fontawesome-svg-core';
import {
	faAngleDown,
	faAngleLeft,
	faAngleRight,
	faArrowUp,
	faBold,
	faCaretDown,
	faCaretUp,
	faCheck,
	faCheckCircle,
	faCircleNotch,
	faCode,
	faExclamationTriangle,
	faExclamationCircle,
	faEye,
	faEyeSlash,
	faInfoCircle,
	faItalic,
	faListOl,
	faListUl,
	faParagraph,
	faQuoteRight,
	faRedo,
	faStrikethrough,
	faTimes,
	faUndo,
	faUserCircle,
} from '@fortawesome/free-solid-svg-icons';
import {
	faBehance,
	faDribbble,
	faFacebook,
	faGithub,
	faGitlab,
	faLinkedin,
	faTwitter,
} from '@fortawesome/free-brands-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import '@oruga-ui/oruga-next/dist/oruga.css';
import 'virtual:windi.css';

import store from '@/store';
import router from '@/router';
import App from '@/App.vue';
import Layout from '@/layouts/Default.vue';

library.add(
	// Solid
	faAngleDown,
	faAngleLeft,
	faAngleRight,
	faArrowUp,
	faBold,
	faCaretDown,
	faCaretUp,
	faCheck,
	faCheckCircle,
	faCircleNotch,
	faCode,
	faExclamationTriangle,
	faExclamationCircle,
	faEye,
	faEyeSlash,
	faInfoCircle,
	faItalic,
	faListOl,
	faListUl,
	faParagraph,
	faQuoteRight,
	faRedo,
	faStrikethrough,
	faTimes,
	faUndo,
	faUserCircle,
	// Brand
	faBehance,
	faDribbble,
	faFacebook,
	faGithub,
	faGitlab,
	faLinkedin,
	faTwitter,
);

store.dispatch('auth/me').then(() => {
	const app = createApp(App);
	app.component('Layout', Layout);
	app.component('VueFontawesome', FontAwesomeIcon);
	app.use(Config, {
		iconComponent: 'vue-fontawesome',
		iconPack: 'fas',
	});
	app.use(Button);
	app.use(Field);
	app.use(Icon);
	app.use(Input);
	app.use(Inputitems);
	app.use(Loading);
	app.use(Modal);
	app.use(Notification);
	app.use(Pagination);
	app.use(Select);
	app.use(Skeleton);
	app.use(Table);
	app.use(Upload);
	app.use(store);
	app.use(router);
	app.mount('#app');
});
