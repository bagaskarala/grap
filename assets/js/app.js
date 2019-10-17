import Vue from 'vue';

// notifikasi toast
import VueNoty from 'vuejs-noty';
import 'vuejs-noty/dist/vuejs-noty.css';
Vue.use(VueNoty, {
   layout: 'topRight',
   timeout: 2000,
   theme: 'bootstrap-v4',
   progressBar: false,
   closeWith: ['click', 'button']
});

// modal bootstrap-vue
import { ModalPlugin } from 'bootstrap-vue';
Vue.use(ModalPlugin);
import 'bootstrap-vue/dist/bootstrap-vue.css';

// membuat instance axios
import Axios from 'axios';
Vue.prototype.$axios = Axios.create({
   baseURL: 'http://localhost/grap',
   headers: {
      'Content-Type': 'application/json'
   }
});

// deklarasi komponen
Vue.component('UsersList', require('./components/UsersList.vue').default);
Vue.component('DivisionList', require('./components/DivisionList.vue').default);
Vue.component('CountryList', require('./components/CountryList.vue').default);
Vue.component('ClubList', require('./components/ClubList.vue').default);
Vue.component('PlayerList', require('./components/PlayerList.vue').default);
Vue.component('WinningList', require('./components/WinningList.vue').default);

new Vue({
   el: '#app'
});
