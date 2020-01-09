<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Country List</span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              @click="addData()"
            >
              Add country
            </button>
          </div>

          <div class="card-body">
            <div
              v-show="countries.length == 0"
              class="my-3 text-center"
            >Empty Data</div>
            <div class="list-group">
              <div
                v-for="item in countries"
                :key="item.id"
                class="list-group-item d-flex justify-content-between align-items-center"
              >
                <div>
                  <p class="font-weight-bold m-0">
                    {{item.country}}
                  </p>
                  <span>{{item.alias}}</span>
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

    <!-- modal add country -->
    <b-modal
      id="modal-country"
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
          <label for="country">Country *</label>
          <input
            id="country"
            v-model="form.country"
            type="text"
            class="form-control"
            placeholder="Enter country"
          >
        </div>
        <div class="form-group">
          <label for="alias">Alias</label>
          <input
            id="alias"
            v-model="form.alias"
            type="text"
            class="form-control"
            placeholder="Enter Alias"
            maxlength="3"
          >
        </div>
        <div class="form-group">
          <label for="img">Img</label>
          <input
            id="img"
            v-model="form.img"
            type="text"
            class="form-control"
            placeholder="Enter img"
          >
        </div>
        <div class="form-group">
          <label for="description">Description</label>
          <textarea
            id="description"
            v-model="form.description"
            class="form-control"
            cols="30"
            rows="4"
            placeholder="Enter description"
          ></textarea>
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
  name: 'CountryList',
  data() {
    return {
      countries: [],
      form: {
        country: null,
        alias: null,
        img: null,
        description: null
      },
      modalState: null,
      errorValidation: null
    };
  },
  methods: {
    async getAllCountries() {
      try {
        const countries = await this.$axios.get('master/country/get_all');
        this.countries = countries.data.data;
      } catch (error) {
        this.$noty.error('Failed Get Data');
        console.log(error.response);
      }
    },

    async insertData() {
      try {
        await this.$axios.post('master/country/insert', {
          country: this.form.country,
          alias: this.form.alias,
          img: this.form.img,
          description: this.form.description
        });
        this.$noty.success('Success Insert Data');
        this.getAllCountries();
        this.$bvModal.hide('modal-country');
      } catch (error) {
        this.$noty.error('Failed Insert Data');
        this.errorValidation = error.response.data.message;
        console.log(error.response);
      }
    },

    async updateData() {
      try {
        await this.$axios.post(`master/country/update/${this.form.id}`, {
          country: this.form.country,
          alias: this.form.alias,
          img: this.form.img,
          description: this.form.description
        });

        this.$noty.success('Success Update Data');
        this.getAllCountries();
        this.$bvModal.hide('modal-country');
      } catch (error) {
        this.$noty.error('Failed Update Data');
        this.errorValidation = error.response.data.message;
        console.log(error.response);
      }
    },

    async deleteData(item) {
      try {
        await this.$axios.post('master/country/delete', {
          id: item.id
        });

        this.$noty.success('Success Delete Data');
        this.getAllCountries();
        this.$bvModal.hide('modal-country');
      } catch (error) {
        this.$noty.error('Failed Delete Data');
        console.log(error.response);
      }
    },

    confirmDelete(item) {
      this.$bvModal.msgBoxConfirm(`Please confirm that you want to delete ${item.country}`, {
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
      this.$bvModal.show('modal-country');
      this.modalState = 'add';
    },

    loadData(item) {
      this.resetData();
      this.$bvModal.show('modal-country');
      this.modalState = 'update';
      let { id, country, alias, img, description } = item;
      this.form.id = id;
      this.form.country = country;
      this.form.alias = alias;
      this.form.img = img;
      this.form.description = description;
    },

    resetData() {
      this.errorValidation = null;
      this.form.country = null;
      this.form.alias = null;
      this.form.img = null;
      this.form.description = null;
    }
  },

  created() {
    this.getAllCountries();
  }
};
</script>
