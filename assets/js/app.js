import Vue from 'vue';

// notifikasi toast
import VueNoty from 'vuejs-noty';
import 'vuejs-noty/dist/vuejs-noty.css';
Vue.use(VueNoty, {
   layout: 'topRight',
   timeout: 3000,
   theme: 'bootstrap-v4',
   progressBar: false,
   closeWith: ['click', 'button']
});

// bootstrap-vue css
import 'bootstrap-vue/dist/bootstrap-vue.css';

// modal bootstrap-vue
import { ModalPlugin } from 'bootstrap-vue';
Vue.use(ModalPlugin);

// table bootstrap-vue
import { TablePlugin } from 'bootstrap-vue';
Vue.use(TablePlugin);

// table bootstrap-vue
import { TabsPlugin } from 'bootstrap-vue';
Vue.use(TabsPlugin);

import { BSpinner } from 'bootstrap-vue';
Vue.component('b-spinner', BSpinner);

// membuat instance axios
import Axios from 'axios';
Vue.prototype.$axios = Axios.create({
   baseURL: 'http://localhost/grap',
   headers: {
      'Content-Type': 'application/json'
   }
});

// deklarasi komponen
Vue.component('UserList', require('./components/UserList.vue').default);
Vue.component('DivisionList', require('./components/DivisionList.vue').default);
Vue.component('CountryList', require('./components/CountryList.vue').default);
Vue.component('ClubList', require('./components/ClubList.vue').default);
Vue.component('PlayerList', require('./components/PlayerList.vue').default);
Vue.component('WinningList', require('./components/WinningList.vue').default);
Vue.component('RefereeList', require('./components/RefereeList.vue').default);
Vue.component('PlayerDivision', require('./components/PlayerDivision.vue').default);
Vue.component('LogMatch', require('./components/LogMatch.vue').default);
Vue.component('LogMatchDetail', require('./components/LogMatchDetail.vue').default);
Vue.component('StopWatch', require('./components/StopWatch.vue').default);
Vue.component('Setting', require('./components/Setting.vue').default);
Vue.component('MenuList', require('./components/MenuList.vue').default);
Vue.component('SubMenuList', require('./components/SubMenuList.vue').default);
Vue.component('RoleList', require('./components/RoleList.vue').default);
Vue.component('CityList', require('./components/CityList.vue').default);

new Vue({
   el: '#app'
});
