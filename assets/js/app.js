import Vue from 'vue';
import Axios from 'axios';

// membuat instance axios
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
