<template>
  <div class="input-group input-group-sm">
    <div class="input-group-prepend">
      <span class="input-group-text">City</span>
    </div>
    <select
      name="select_city"
      id="select_city"
      class="form-control"
      v-model="selectedCity"
      @change="changeCity"
    >
      <option
        :value="null"
        disabled
      >--- Select City ---</option>
      <option
        v-for="item in cities"
        :key="item.id"
        :value="item.id"
      >{{item.city}}</option>
    </select>
  </div>
</template>

<script>
export default {
  name: 'CitySelector',
  props: ['cityId'],
  data() {
    return {
      cities: [],
      selectedCity: null
    };
  },

  methods: {
    async getCities() {
      try {
        const cities = await this.$axios.get('master/city/get_all');
        this.cities = cities.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Fetch Cities');
      }
    },

    async changeCity() {
      try {
        await this.$axios.post('setting/update', {
          city_id: this.selectedCity
        });
        this.$noty.success('Success Update Data');
        location.reload();
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Update Data');
      }
    }
  },

  created() {
    this.getCities();

    // populate city, ambil dari session di parent
    if (this.cityId) {
      this.selectedCity = this.cityId;
    }
  }
};
</script>