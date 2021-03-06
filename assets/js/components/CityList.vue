<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>City List <span class="badge badge-secondary">{{cities.length}}</span></span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              @click.prevent="addData()"
            >
              Add city
            </button>
          </div>

          <div class="card-body">
            <div
              v-show="cities.length == 0"
              class="my-3 text-center"
            >Empty Data</div>
            <div class="list-group">
              <div
                v-for="item in cities"
                :key="item.id"
                class="list-group-item d-flex justify-content-between align-items-center"
              >
                <div>
                  <p class="font-weight-bold m-0">
                    {{item.city}}
                  </p>
                </div>
                <div>
                  <button
                    class="btn btn-sm btn-warning"
                    @click.prevent="loadData(item)"
                  ><i class="fa fa-edit fa-fw"></i></button>
                  <button
                    class="btn btn-sm btn-danger"
                    @click.prevent="confirmDelete(item)"
                  ><i class="fa fa-trash fa-fw"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- modal add city -->
    <b-modal
      id="modal-city"
      hide-footer
      :title="modalState == 'add'? 'Add Item' : 'Update Item'"
    >
      <div
        v-if="errorValidation"
        class="alert alert-danger"
        v-html="errorValidation"
      ></div>

      <form method="post">
        <div class="form-group">
          <label for="city">City *</label>
          <input
            id="city"
            v-model="form.city"
            type="text"
            class="form-control"
            placeholder="Enter city"
          >
        </div>

        <div class="d-flex justify-content-end">
          <div
            class="btn-group"
            role="group"
          >
            <button
              type="button"
              class="btn btn-secondary"
              @click="resetData()"
            >Reset</button>
            <button
              v-if="modalState=='add'"
              class="btn btn-primary"
              @click.prevent="insertData()"
            >Add</button>
            <button
              v-else
              class="btn btn-primary"
              @click.prevent="updateData()"
            >Update</button>
          </div>
        </div>
      </form>
    </b-modal>
  </div>
</template>

<script>
export default {
  name: 'CityList',
  data() {
    return {
      cities: [],
      form: {
        city: null
      },
      modalState: null,
      errorValidation: null
    };
  },
  methods: {
    async getAllCities() {
      try {
        const cities = await this.$axios.get('master/city/get_all');
        this.cities = cities.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Get Data');
      }

    },

    async insertData() {
      try {
        await this.$axios.post('master/city/insert', {
          city: this.form.city
        });

        this.$noty.success('Success Insert Data');
        this.getAllCities();
        this.$bvModal.hide('modal-city');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Insert Data');
      }
    },

    async updateData() {
      try {
        await this.$axios.post(`master/city/update/${this.form.id}`, {
          city: this.form.city
        });

        this.$noty.success('Success Update Data');
        this.getAllCities();
        this.$bvModal.hide('modal-city');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Update Data');
      }
    },

    async deleteData(item) {
      try {
        await this.$axios.post('master/city/delete', {
          id: item.id
        });

        this.$noty.success('Success Delete Data');
        this.getAllCities();
        this.$bvModal.hide('modal-city');

      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Delete Data');
      }
    },

    confirmDelete(item) {
      this.$bvModal.msgBoxConfirm(`Please confirm that you want to delete ${item.city}`, {
        title: 'Delete Data',
        size: 'md',
        okVariant: 'danger',
        centered: true
      })
        .then(value => {
          if (value) {
            this.deleteData(item);
          }
        })
        .catch(err => {
          console.log('Error ', err);
        });
    },

    addData() {
      this.resetData();
      this.$bvModal.show('modal-city');
      this.modalState = 'add';
    },

    loadData(item) {
      this.resetData();
      this.$bvModal.show('modal-city');
      this.modalState = 'update';
      // populate form
      let { id, city } = item;
      this.form.id = id;
      this.form.city = city;
    },

    resetData() {
      this.errorValidation = null;
      this.form.city = null;
    }
  },

  created() {
    this.getAllCities();
  }
};
</script>
