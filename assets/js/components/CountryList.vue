<template>
  <div>
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Country List</span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              data-toggle="modal"
              data-target="#modal-country"
              @click="modalState = 'add'"
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
                  <p class="small text-muted m-0">
                    img: {{item.img}}
                  </p>
                </div>
                <div>
                  <button
                    class="btn btn-sm btn-warning"
                    data-toggle="modal"
                    data-target="#modal-country"
                    @click="loadData(item)"
                  ><i class="fa fa-edit fa-fw"></i></button>
                  <button
                    class="btn btn-sm btn-danger"
                    @click="deleteData(item)"
                  ><i class="fa fa-trash fa-fw"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- modal add division -->
    <div
      class="modal fade"
      id="modal-country"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div
        class="modal-dialog"
        role="document"
      >
        <div class="modal-content">
          <div class="modal-header">
            <h5
              class="modal-title"
              id="exampleModalLabel"
            >{{modalState == 'add'? 'Add Item' : 'Update Item'}}</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post">
            <div class="modal-body">
              <div class="form-group">
                <label for="country">Country</label>
                <input
                  id="country"
                  v-model="form.country"
                  type="text"
                  class="form-control"
                  placeholder="Enter country"
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
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >Close</button>
              <button
                v-if="modalState=='add'"
                class="btn btn-primary"
                @click.prevent="addData()"
              >Add</button>
              <button
                v-else
                class="btn btn-primary"
                @click.prevent="updateData()"
              >Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
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
        img: null,
        description: null
      },
      modalState: null
    };
  },
  methods: {
    async getAllCountries() {
      const countries = await this.$axios.get('master/country/get_all');
      this.countries = countries.data.data;
    },

    async addData() {
      const result = await this.$axios.post('master/country/insert', {
        country: this.form.country,
        img: this.form.img,
        description: this.form.description
      });

      this.triggerAlert(result.data.status, 'Insert');
    },

    async updateData() {
      const result = await this.$axios.post(`master/country/update/${this.form.id}`, {
        country: this.form.country,
        img: this.form.img,
        description: this.form.description
      });

      this.triggerAlert(result.data.status, 'Update');
    },

    async deleteData(item) {
      const result = await this.$axios.post('master/country/delete', {
        id: item.id
      });

      this.triggerAlert(result.data.status, 'Delete');
    },

    loadData(item) {
      this.modalState = 'update';
      let { id, country, img, description } = item;
      this.form.id = id;
      this.form.country = country;
      this.form.img = img;
      this.form.description = description;
    },

    triggerAlert(status, type = 'Action') {
      if (status) {
        alert(`Success ${type} Data`);
        location.reload();
      } else {
        alert(`Failed ${type} Data`);
      }
    },

    resetData() {
      this.form = {
        country: null,
        img: null,
        description: null
      };
    }
  },

  created() {
    this.getAllCountries();
  }
};
</script>
