import ExampleComponent from '../components/ExampleComponent.vue';
import AdminHome from '../components/AdminHome.vue';
import AdminLogin from '../components/AdminLogin.vue';

export default [
    { path: '/login', component: AdminLogin },
    { path: '/',
        component: AdminHome,
        children:[
            { path: '/home', component: ExampleComponent },
        ]
    },
]