<template>
  <div>
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Player List</span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              data-toggle="modal"
              data-target="#modal-player"
              @click="modalState = 'add'"
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
                    data-toggle="modal"
                    data-target="#modal-player"
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
      id="modal-player"
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
      modalState: null
    };
  },
  methods: {
    async getCountries() {
      const countries = await this.$axios.get('master/country/get_all');
      this.countries = countries.data.data;
    },

    async getAllPlayers() {
      const players = await this.$axios.get('master/player/get_all');
      this.players = players.data.data;
    },

    async addData() {
      const result = await this.$axios.post('master/player/insert', {
        country_id: this.form.country_id,
        name: this.form.name,
        img: this.form.img,
        height: this.form.height,
        weight: this.form.weight,
        achievement: this.form.achievement
      });

      this.triggerAlert(result.data.status, 'Insert');
    },

    async updateData() {
      const result = await this.$axios.post(`master/player/update/${this.form.id}`, {
        country_id: this.form.country_id,
        name: this.form.name,
        img: this.form.img,
        height: this.form.height,
        weight: this.form.weight,
        achievement: this.form.achievement
      });

      this.triggerAlert(result.data.status, 'Update');
    },

    async deleteData(item) {
      const result = await this.$axios.post('master/player/delete', {
        id: item.id
      });

      this.triggerAlert(result.data.status, 'Delete');
    },

    loadData(item) {
      this.modalState = 'update';
      let { id, country_id, name, img, height, weight, achievement } = item;
      this.form.id = id;
      this.form.country_id = country_id;
      this.form.name = name;
      this.form.img = img;
      this.form.height = height;
      this.form.weight = weight;
      this.form.achievement = achievement;
    },

    triggerAlert(status, type = 'Action') {
      if (status) {
        alert(`Success ${type} Data`);
        location.reload();
      } else {
        alert(`Failed ${type} Data`);
      }
    }
  },

  created() {
    this.getAllPlayers();
    this.getCountries();
  }
};
</script>
