<template>
  <div class="input-group input-group-sm">
    <div class="input-group-prepend">
      <span class="input-group-text">Year</span>
    </div>
    <select
      name="select_year"
      id="select_year"
      class="form-control"
      v-model="selectedYear"
      @change="changeYear"
    >
      <option
        :value="null"
        disabled
      >--- Select Year ---</option>
      <option
        v-for="item in years"
        :key="item"
        :value="item"
      >{{item}}</option>
    </select>
  </div>
</template>

<script>
export default {
  name: 'YearSelector',
  props: ['year'],
  data() {
    return {
      years: [],
      selectedYear: null
    };
  },

  methods: {
    async getYears() {
      try {
        const years = await this.$axios.get('entry/player_division/find_all_year');
        this.years = years.data.data.map(i => i.year);
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Fetch Years');
      }
    },

    async changeYear() {
      try {
        await this.$axios.post('setting/update', {
          year: this.selectedYear
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
    this.getYears();

    // populate city, ambil dari session di parent
    if (this.year) {
      this.selectedYear = this.year;
    }
  }
};
</script>