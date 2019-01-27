require('./bootstrap');

import Vue from 'vue';
import iView from 'iview';
import VueQuillEditor from 'vue-quill-editor';
import xayah from 'xayah';

import 'quill/dist/quill.snow.css';
import 'iview/dist/styles/iview.css';

import ADMIN from './utils/admin';

Vue.use(iView);
Vue.use(VueQuillEditor);
Vue.use(xayah);

Vue.prototype.$admin = ADMIN;

Vue.directive('can', {
    bind: function (el, binding) {
        let action = binding.expression.split('.');
        if (!Vue.prototype[action[0]][action[1]](true)) {
            //默认删除元素 失败则隐藏
            try {
                el.parentNode.removeChild(el);
            } catch (e) {
                el.style.display = 'none';
            }
        }
    }
});

Vue.component('AdminUpload', require('./components/AdminUpload'));
Vue.component('WebUpload', require('./components/WebUpload'));

const files = require.context('./pages', true, /\.vue$/i);

// 示例 user/index => user-index
files.keys().map(key => {
    const pageName = _.camelCase(key.replace(/^\.\/(.*)\.\w+$/, '$1'));

    if (pageName !== 'breadCrumb') {
        Vue.component(pageName, files(key));
    }
});

const app = new Vue({
    el: '#app',
});