/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");
import { Form, HasError, AlertError } from "vform";
import moment from "moment"; //package.json file e moment dependency ace okhane check kortece

//importing sweet alert.js starts
import Swal from "sweetalert2";
window.Swal = Swal;

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000
});

window.Toast = Toast; //globally toaster define korci jeno jekono component e use krte pari

//defining sweetalert2 ends

//defining global component that can be used in any components for form validation starts
window.Form = Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);
//defining global component that can be used in any components for form validation ends

import VueRouter from "vue-router";

Vue.use(VueRouter);

//using vue progress bar starts
import VueProgressBar from "vue-progressbar";
const options = {
    color: "#bffaf3",
    failedColor: "#874b4b",
    thickness: "5px",
    transition: {
        speed: "0.2s",
        opacity: "0.6s",
        termination: 300
    },
    autoRevert: true,
    location: "top",
    inverse: false
};

Vue.use(VueProgressBar, options);

//using vue progress bar ends

//creating routes
let routes = [
    {
        path: "/dashboard",
        component: require("./components/DashboardComponent.vue").default
    },
    {
        path: "/profile",
        component: require("./components/ProfileComponent.vue").default
    },
    {
        path: "/users",
        component: require("./components/UsersComponent.vue").default
    }
];

const router = new VueRouter({
    mode: "history", ///localhost/home/profile evabe home hoye na giy just localhost/profile e jate jai sejonno history use korlam
    routes // short for `routes: routes`
    //routes gulake instantiate korlam
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);

//defining global filter jeta jekono component e use korte parbo.. starts
Vue.filter("upperCaseAnyText", function(textArgument) {
    return textArgument.charAt(0).toUpperCase() + textArgument.slice(1);
    //ei filter ta jekono text er 1st letter ke uppercase kore return korbe.
    //as eta global filter hishebe define korci(As app.js e likhci), so jekono vue component e ei filter call korte parbo ekhn.
});

Vue.filter("parseCreatedAtDateForUser", function(createdAt) {
    return moment(createdAt).format("MMMM Do YYYY, h:mm:ss a"); //moment.js use korlam.
});

//global filter definition ends

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    router
});
