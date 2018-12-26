window.Vue = require('vue');
import { setCookie, getCookie } from '../api/api.js'
import Router from 'vue-router';
import AdminHome from '../components/AdminHome.vue';
import AdminLogin from '../components/AdminLogin.vue';
Vue.use(Router)
const router = new Router({
    routes:[
    { path: '/login', component: AdminLogin },
    {
        path: '/',
        component: AdminHome,
        children: [
            {
                path: '/',
                component: (resolve) => {
                    require(['../components/AdminAccounts.vue'], resolve)
                }
            },
            {
                path: '/Users',
                component: (resolve) => {
                    require(['../components/AdminUsers.vue'], resolve)
                }
            },
            {
                path: '/Order',
                component: (resolve) => {
                    require(['../components/AdminOrder.vue'], resolve)
                }
            },
            {
                path: '/Release',
                component: (resolve) => {
                    require(['../components/AdminRelease.vue'], resolve)
                }
            },
            {
                path: '/List',
                component: (resolve) => {
                    require(['../components/AdminInfoList.vue'], resolve)
                }
            },
            {
                path: '/Cost',
                component: (resolve) => {
                    require(['../components/AdminCost.vue'], resolve)
                }
            },
            {
                path: '/Review',
                component: (resolve) => {
                    require(['../components/AdminReview.vue'], resolve)
                }
            },
            {
                path: '/Draft',
                component: (resolve) => {
                    require(['../components/AdminDraft.vue'], resolve)
                }
            },
            {
                path: '/Deteail',
                component: (resolve) => {
                    require(['../components/AdminDeteail.vue'], resolve)
                }
            },
            {
                path: '/Deteails/:id',
                component: (resolve) => {
                    require(['../components/AdminDateails.vue'], resolve)
                }
            },
            {
                path: '/Draft/:id',
                component: (resolve) => {
                    require(['../components/AdminRelease.vue'], resolve)
                }
            },
            {
                path: '/View/:id',
                component: (resolve) => {
                    require(['../components/AdminView.vue'], resolve)
                }
            },
            {
                path: '/TermReview',
                component: (resolve) => {
                    require(['../components/AdminTermReview.vue'], resolve)
                }
            }
        ]
    },
]})
router.beforeEach((to, from, next) => {
    if (to.matched.some(res => res.meta.requireAuth)) {// 判断是否需要登录权限
        let token = getCookie('token')
        if (token) {// 判断是否登录
            setCookie('token',token,1)
            next()
        } else {// 没登录则跳转到登录界面
            next({
                path: '/login'
            })
        }
    } else {
        next()
    }
})
export default router