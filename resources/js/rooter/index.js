window.Vue = require('vue');
import { setCookie, getCookie } from '../api/api.js'
import Router from 'vue-router';
import AdminHome from '../components/AdminHome.vue';
import AdminLogin from '../components/AdminLogin.vue';
Vue.use(Router)
const router = new Router({
    routes: [
        { path: '/login', component: AdminLogin },
        {
            path: '/',
            component: AdminHome,
            children: [
                {
                    path: '/',
                    meta: {
                        requireAuth: true
                    },
                    component: (resolve) => {
                        require(['../components/AdminAccounts.vue'], resolve)
                    }
                },
                {
                    path: '/Users',
                    meta: {
                        requireAuth: true
                    },
                    component: (resolve) => {
                        require(['../components/AdminUsers.vue'], resolve)
                    }
                },
                {
                    path: '/Order',
                    meta: {
                        requireAuth: true
                    },
                    component: (resolve) => {
                        require(['../components/AdminOrder.vue'], resolve)
                    }
                },
                {
                    path: '/Release',
                    meta: {
                        requireAuth: true
                    },
                    component: (resolve) => {
                        require(['../components/AdminRelease.vue'], resolve)
                    }
                },
                {
                    path: '/List',
                    meta: {
                        requireAuth: true
                    },
                    component: (resolve) => {
                        require(['../components/AdminInfoList.vue'], resolve)
                    }
                },
                {
                    path: '/Cost',
                    meta: {
                        requireAuth: true
                    },
                    component: (resolve) => {
                        require(['../components/AdminCost.vue'], resolve)
                    }
                },
                {
                    path: '/Review',
                    meta: {
                        requireAuth: true
                    },
                    component: (resolve) => {
                        require(['../components/AdminReview.vue'], resolve)
                    }
                },
                {
                    path: '/Draft',
                    meta: {
                        requireAuth: true
                    },
                    component: (resolve) => {
                        require(['../components/AdminDraft.vue'], resolve)
                    }
                },
                {
                    path: '/Deteail',
                    meta: {
                        requireAuth: true
                    },
                    component: (resolve) => {
                        require(['../components/AdminDeteail.vue'], resolve)
                    }
                },
                {
                    path: '/Deteails/:id',
                    meta: {
                        requireAuth: true
                    },
                    component: (resolve) => {
                        require(['../components/AdminDateails.vue'], resolve)
                    }
                },
                {
                    path: '/Draft/:id',
                    meta: {
                        requireAuth: true
                    },
                    component: (resolve) => {
                        require(['../components/AdminRelease.vue'], resolve)
                    }
                },
                {
                    path: '/View/:id',
                    meta: {
                        requireAuth: true
                    },
                    component: (resolve) => {
                        require(['../components/AdminView.vue'], resolve)
                    }
                },
                {
                    path: '/TermReview',
                    meta: {
                        requireAuth: true
                    },
                    component: (resolve) => {
                        require(['../components/AdminTermReview.vue'], resolve)
                    }
                }
            ]
        },
    ]
})
router.beforeEach((to, from, next) => {
    let token = getCookie('token')
    if (to.matched.some(res => res.meta.requireAuth)) {// 判断是否需要登录权限

        if (token) {// 判断是否登录
            console.log('token')
            setCookie('token', token, 1)
            next()
        } else {// 没登录则跳转到登录界面
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            })
        }
    } else {
        next()
    }
})
export default router