/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/NewBillComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import NotificationComponent from "./components/NotificationComponent";
import InfoCardComponent from "./components/InfoCards/InfoCardComponent";
import CalendarComponent from "./components/CalendarComponent";
import OverviewComponent from "./components/OverviewComponent";
import PaymentComponent from "./components/PaymentComponent";
import NewBillComponent from "./components/NewBillComponent";
import ChartsComponent from "./components/Home/ChartsComponent";
import IncomingBillComponent from "./components/Home/IncomingBillComponent";
import FullScreenLoadingComponent from "./components/Loading/FullScreenLoadingComponent";
import AdminComponent from "./components/Admin/AdminComponent";
import ExpenditureComponent from "./components/Expenditure/ExpenditureComponent";
import MessagesComponent from "./components/MessagesComponent";
const app = new Vue({
    el: '#app',
    components: {
        NotificationComponent,
        CalendarComponent,
        OverviewComponent,
        PaymentComponent,
        NewBillComponent,
        ChartsComponent,
        InfoCardComponent,
        IncomingBillComponent,
        FullScreenLoadingComponent,
        AdminComponent,
        ExpenditureComponent,
        MessagesComponent,
    }
});


//Login
const inputs = document.querySelectorAll('.input');

function addFocus(){
    let parent = this.parentNode.parentNode;
    parent.classList.add('focus');
}

function removeFocus(){
    let parent = this.parentNode.parentNode;
    if(this.value == ""){
        parent.classList.remove('focus');
    }
}

inputs.forEach(input =>{
    input.addEventListener('focus', addFocus);
    input.addEventListener('blur', removeFocus);
});

//sidemenu-toggle
let sidemenuToggler = document.querySelector('.menu-toggle');
let sidemenuClose = document.querySelector('.menu-close');
let body = document.querySelector('body');

if (sidemenuToggler) {
    sidemenuToggler.addEventListener('click', function () {
        body.classList.toggle('open');
    });
}

if(sidemenuClose) {
    sidemenuClose.addEventListener('click', function () {
        body.classList.toggle('open');
    });
}

