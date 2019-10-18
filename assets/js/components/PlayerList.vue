<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Player List</span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              @click.prevent="addData()"
            >
              Add player
            </button>
          </div>

          <div class="card-body">
            <div
              v-show="players.length == 0"
              class="my-3 text-center"
            >Empty Data</div>
            <div class="list-group">
              <div
                v-for="item in players"
                :key="item.id"
                class="list-group-item d-flex justify-content-between align-items-center"
              >
                <div>
                  <p class="font-weight-bold m-0">
                    {{item.name}}
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

    <!-- modal add player -->
    <b-modal
      id="modal-player"
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
          <label for="name">Name</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            class="form-control"
            placeholder="Enter name"
          >
        </div>
        <div class="form-group">
          <label for="country_id">Country</label>
          <select
            name="country_id"
            id="country_id"
            class="form-control"
            v-model.number="form.country_id"
          >
            <option :value="null">Select Country</option>
            <option
              v-for="item in countries"
              :key="item.id"
              :value="item.id"
            >{{item.country}}</option>
          </select>
        </div>
        <div class="form-group">
          <label for="img">Image</label>
          <input
            id="img"
            v-model="form.img"
            type="text"
            class="form-control"
            placeholder="Enter img"
          >
        </div>
        <div class="form-group">
          <label for="height">Height</label>
          <input
            id="height"
            v-model.number="form.height"
            type="number"
            class="form-control"
            placeholder="Enter height"
          >
        </div>
        <div class="form-group">
          <label for="weight">Weight</label>
          <input
            id="weight"
            v-model.number="form.weight"
            type="number"
            class="form-control"
            placeholder="Enter weight"
          >
        </div>
        <div class="form-group">
          <label for="achievement">Achievement</label>
          <input
            id="achievement"
            v-model.number="form.achievement"
            type="number"
            class="form-control"
            placeholder="Enter achievement"
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
  name: 'PlayerList',
  data() {
    return {
      players: [],
      countries: [],
      form: {
        country_id: null,
        name: null,
        img: null,
        height: null,
        weight: null,
        achievement: null
      },
      modalState: null,
      errorValidation: null
    };
  },
  methods: {
    async getCountries() {
      try {
        const countries = await this.$axios.get('master/country/get_all');
        this.countries = countries.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Fetch Countries');
      }

    },

    async getAllPlayers() {
      try {
        const players = await this.$axios.get('master/player/get_all');
        this.players = players.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Get Data');
      }

    },

    async insertData() {
      try {
        await this.$axios.post('master/player/insert', {
          country_id: this.form.country_id,
          name: this.form.name,
          img: this.form.img,
          height: this.form.height,
          weight: this.form.weight,
          achievement: this.form.achievement
        });

        this.$noty.success('Success Insert Data');
        this.getAllPlayers();
        this.$bvModal.hide('modal-player');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Insert Data');
      }

    },

    async updateData() {
      try {
        await this.$axios.post(`master/player/update/${this.form.id}`, {
          country_id: this.form.country_id,
          name: this.form.name,
          img: this.form.img,
          height: this.form.height,
          weight: this.form.weight,
          achievement: this.form.achievement
        });

        this.$noty.success('Success Update Data');
        this.getAllPlayers();
        this.$bvModal.hide('modal-player');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Update Data');
      }
    },

    async deleteData(item) {
      try {
        await this.$axios.post('master/player/delete', {
          id: item.id
        });

        this.$noty.success('Success Delete Data');
        this.getAllPlayers();
        this.$bvModal.hide('modal-player');

      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Delete Data');
      }
    },

    confirmDelete(item) {
      this.$bvModal.msgBoxConfirm(`Please confirm that you want to delete ${item.name}`, {
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
      this.$bvModal.show('modal-player');
      this.modalState = 'add';
    },

    loadData(item) {
      this.resetData();
      this.$bvModal.show('modal-player');
      this.modalState = 'update';
      // populate form
      let { id, country_id, name, img, height, weight, achievement } = item;
      this.form.id = id;
      this.form.country_id = country_id;
      this.form.name = name;
      this.form.img = img;
      this.form.height = height;
      this.form.weight = weight;
      this.form.achievement = achievement;
    },

    resetData() {
      this.errorValidation = null;
      this.form.country_id = null;
      this.form.name = null;
      this.form.img = null;
      this.form.height = null;
      this.form.weight = null;
      this.form.achievement = null;
    }
  },

  created() {
    this.getAllPlayers();
    this.getCountries();
  }
};
</script>
