require('./bootstrap');

// 导入扩展包
window.Vue = require('vue');

import App from './app.vue';
import iView from 'iview';
import 'iview/dist/styles/iview.css';
import rooter from './rooter/index';
import axios from 'axios';

// 导入vue
Vue.use(iView);
Vue.prototype.$axios = axios;

// 路由配置


const router = rooter;

const app = new Vue({
    el: '#app',
    router: router,
    render: h => h(App)
});