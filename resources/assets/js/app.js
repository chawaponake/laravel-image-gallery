
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import Home from './components/HomeComponent.vue';
import Gallery from './components/GalleryComponent.vue';

Vue.use(VueRouter);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('home-component', require('./components/HomeComponent.vue'));
Vue.component('gallery-component', require('./components/GalleryComponent.vue'));
Vue.component('menu-component', require('./components/MenuComponent.vue'));

const router = new VueRouter({
    routes: [
        {
            path: '/home',
            name: 'home',
            component: Home
        },
        {
            path: '/gallery',
            name: 'gallery',
            component: Gallery,
        },
    ],
})

const app = new Vue({
    el: '#app',
    router,
});

