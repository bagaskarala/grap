<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Setting</span>
          </div>

          <div class="card-body">
            <form method="post">
              <div class="form-group">
                <label for="year">Year</label>
                <input
                  id="year"
                  v-model.number="form.year"
                  type="number"
                  class="form-control"
                  placeholder="Enter year"
                  min="1900"
                  max="2100"
                >
              </div>

              <div class="form-group">
                <label for="month">Month</label>
                <input
                  id="month"
                  v-model.number="form.month"
                  type="number"
                  class="form-control"
                  placeholder="Enter month"
                  max="12"
                  min="1"
                >
              </div>

              <div class="form-group">
                <label for="city">City</label>
                <select
                  name="city"
                  id="city"
                  class="form-control"
                  v-model.number="form.city"
                >
                  <option :value="null">Select City</option>
                  <option
                    v-for="item in cities"
                    :key="item.value"
                    :value="item.value"
                  >{{item.text}}</option>
                </select>
              </div>

              <div class="d-flex justify-content-end">
                <div
                  class="btn-group"
                  role="group"
                >
                  <button
                    class="btn btn-primary"
                    @click.prevent="updateData()"
                  >Update</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getCities } from '../shared';
export default {
  name: 'Setting',
  data() {
    return {
      form: {
        year: null,
        month: null,
        city: null
      },
      modalState: null,
      errorValidation: null,
      cities: []
    };
  },

  methods: {
    getCities,

    async getSetting() {
      try {
        const setting = await this.$axios.get('setting/get');
        this.form = setting.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Get Data');
      }
    },

    async updateData() {
      if (this.form.year < new Date().getFullYear() || this.form.year > 2100) {
        this.$noty.error('Invalid year');
        return;
      }

      if (this.form.montgh < 1 || this.form.month > 12) {
        this.$noty.error('Invalid month');
        return;
      }

      try {
        await this.$axios.post('setting/update', {
          year: this.form.year,
          month: this.form.month,
          city: this.form.city
        });

        this.$noty.success('Success Update Data');
        this.getSetting();

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Update Data');
      }
    }
  },

  created() {
    this.getSetting();
    this.cities = this.getCities();
  }
};
</script>
