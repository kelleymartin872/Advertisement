import {createWebHistory, createRouter} from "vue-router";

import Home from '../pages/Home';
import Login from '../pages/Login';
import Advertisement from '../components/Advertisement';
import AddAdvertisement from '../components/AddAdvertisement';
import EditAdvertisement from '../components/EditAdvertisement';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'advertisement',
        path: '/advertisement/show/:id',
        component: Advertisement
    },
    {
        name: 'addadvertisement',
        path: '/advertisement/add',
        component: AddAdvertisement
    },
    {
        name: 'editadvertisement',
        path: '/advertisement/edit/:id',
        component: EditAdvertisement
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
