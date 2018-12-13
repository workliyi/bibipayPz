import AdminHome from '../components/AdminHome.vue';
import AdminLogin from '../components/AdminLogin.vue';

import AdminAccounts from '../components/AdminAccounts.vue';
import AdminUsers from '../components/AdminUsers.vue';
import AdminOrder from '../components/AdminOrder.vue';
import AdminRelease from '../components/AdminRelease.vue';
import AdminInfoList from '../components/AdminInfoList.vue';
import AdminCost from '../components/AdminCost.vue';
import AdminDraft from '../components/AdminDraft.vue';
import AdminReview from '../components/AdminReview.vue';
import AdminDeteail from '../components/AdminDeteail.vue';
import AdminDateails from '../components/AdminDateails.vue';
import AdminTermReview from '../components/AdminTermReview.vue';
import AdminView from '../components/AdminView.vue';
export default [
    { path: '/login', component: AdminLogin },
    { path: '/',
        component: AdminHome,
        children:[
            { path: '/', component: AdminAccounts },
            { path: '/Users', component: AdminUsers },
            { path: '/Order', component: AdminOrder },
            { path: '/Release', component: AdminRelease },
            { path: '/List', component: AdminInfoList },
            { path: '/Cost', component: AdminCost },
            { path: '/Review', component: AdminReview },
            { path: '/Draft', component: AdminDraft },
            { path: '/Deteail', component: AdminDeteail },
            { path: '/Deteails/:id', component: AdminDateails },
            { path: '/Draft/:id', component: AdminRelease },
            { path: '/View/:id', component: AdminView },
            { path: '/TermReview', component: AdminTermReview }
        ]
    },
]