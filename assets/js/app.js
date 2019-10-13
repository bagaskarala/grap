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

new Vue({
   el: '#app'
});
