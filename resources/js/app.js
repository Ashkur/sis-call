
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import Snotify from 'vue-snotify'
import 'vue-snotify/styles/material.css'

Vue.use(VueRouter)
Vue.use(Vuetify)
Vue.use(Snotify, {timeout: 5000})

//  TELAS
import App from './screens/App'
import Home from './screens/users/Home'
import Dashboard from './screens/adms/dashboard'
import Ticket from './screens/Ticket'


//  ROTA
const router = new VueRouter ({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'Home',
            component: Dashboard
        },
        {
            path: '/adm',
            name: 'Dashboard',
            component: Dashboard
        },
        {
            path: 'tickets/:id',
            name: 'TicketDetail',
            component: Ticket
        }
    ]
})



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default)

// Vue.component('tickets', require('./components/Tickets.vue').default)



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});
