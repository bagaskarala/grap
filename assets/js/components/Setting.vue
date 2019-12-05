<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Setting Kota</span>
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
                <label for="city_id">City</label>
                <select
                  name="city_id"
                  id="city_id"
                  class="form-control"
                  v-model.number="form.city_id"
                >
                  <option
                    :value="null"
                    disabled
                  >Select City</option>
                  <option
                    v-for="item in cities"
                    :key="item.id"
                    :value="item.id"
                  >{{item.city}}</option>
                </select>
              </div>

              <div class="form-group">
                <label for="month">Waktu Regular</label>
                <input
                  id="month"
                  v-model.number="form.regular_time"
                  type="number"
                  class="form-control"
                  placeholder="Enter Regular Time Match"
                  max="20"
                  min="1"
                >
              </div>

              <div class="form-group">
                <label for="month">Waktu Semi Final</label>
                <input
                  id="month"
                  v-model.number="form.semifinal_time"
                  type="number"
                  class="form-control"
                  placeholder="Enter Semi Final Time Match"
                  max="20"
                  min="1"
                >
              </div>

              <div class="form-group">
                <label for="month">Waktu Final</label>
                <input
                  id="month"
                  v-model.number="form.final_time"
                  type="number"
                  class="form-control"
                  placeholder="Enter Final Time Match"
                  max="20"
                  min="1"
                >
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
export default {
  name: 'Setting',
  data() {
    return {
      form: {
        year: null,
        month: null,
        city_id: null
      },
      modalState: null,
      errorValidation: null,
      cities: []
    };
  },

  methods: {
    async getSetting() {
      try {
        const setting = await this.$axios.get('setting/get');
        this.form = setting.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Get Setting');
      }
    },

    async getCities() {
      try {
        const cities = await this.$axios.get('master/city/get_all');
        this.cities = cities.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Get Cities');
      }
    },

    async updateData() {
      if (this.form.year < new Date().getFullYear() || this.form.year > 2100) {
        this.$noty.error('Invalid year');
        return;
      }

      if (this.form.month < 1 || this.form.month > 12) {
        this.$noty.error('Invalid month');
        return;
      }
      if (this.form.regular_time < 1 || this.form.regular_time > 20) {
        this.$noty.error('Invalid regular time');
        return;
      }
      if (this.form.semifinal_time < 1 || this.form.semifinal_time > 20) {
        this.$noty.error('Invalid semifinal time');
        return;
      }
      if (this.form.final_time < 1 || this.form.final_time > 20) {
        this.$noty.error('Invalid final time');
        return;
      }

      try {
        await this.$axios.post('setting/update', {
          year: this.form.year,
          month: this.form.month,
          city_id: this.form.city_id,
          regular_time: this.form.regular_time,
          semifinal_time: this.form.semifinal_time,
          final_time: this.form.final_time
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
    this.getCities();
  }
};
</script>
