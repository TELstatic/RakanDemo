require('./bootstrap');

window.Vue = require('vue');

import iView from 'iview';

Vue.use(iView);
import 'iview/dist/styles/iview.css';

import xayah from 'xayah';

Vue.use(xayah);

Vue.component('example-component', require('./components/ExampleComponent.vue'));

const app = new Vue({
    el: '#app'
});
