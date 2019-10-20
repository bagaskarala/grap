<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Player Division List</span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              @click.prevent="addData()"
            >
              Add player division
            </button>
          </div>

          <div class="mx-3 mt-3">
            <div class="input-group input-group-sm">
              <select
                name="filter_division"
                id="filter_division"
                class="form-control"
                v-model.number="filterDivisionId"
              >
                <option :value="null">All Division</option>
                <option
                  v-for="item in divisions"
                  :key="item.id"
                  :value="item.id"
                >{{item.division}}</option>
              </select>
              <div class="input-group-append">
                <button
                  class="btn btn-primary"
                  type="button"
                  @click.prevent="filterData(filterDivisionId)"
                >Filter</button>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div
              v-show="playerDivisions.length == 0"
              class="my-3 text-center"
            >Empty Data</div>

            <b-table
              v-if="playerDivisions.length != 0"
              striped
              hover
              :items="playerDivisions"
              :fields="fieldPlayerDivision"
            >
              <template v-slot:cell(action)="data">
                <div>
                  <button
                    class="btn btn-sm btn-warning"
                    @click.prevent="loadData(data.item)"
                  ><i class="fa fa-edit fa-fw"></i></button>
                  <button
                    class="btn btn-sm btn-danger"
                    @click.prevent="confirmDelete(data.item)"
                  ><i class="fa fa-trash fa-fw"></i></button>
                </div>
              </template>
            </b-table>
          </div>
        </div>
      </div>
    </div>

    <!-- modal add player division-->
    <b-modal
      id="modal-player-division"
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
          <label for="division_id">Division</label>
          <select
            name="division_id"
            id="division_id"
            class="form-control"
            v-model.number="form.division_id"
          >
            <option :value="null">Select Division</option>
            <option
              v-for="item in divisions"
              :key="item.id"
              :value="item.id"
            >{{item.division}}</option>
          </select>
        </div>
        <div class="form-group">
          <label for="player_id">Player</label>
          <select
            name="player_id"
            id="player_id"
            class="form-control"
            v-model.number="form.player_id"
          >
            <option :value="null">Select Player</option>
            <option
              v-for="item in players"
              :key="item.id"
              :value="item.id"
            >{{item.name}}</option>
          </select>
        </div>
        <div class="form-group">
          <label for="pool_number">Pool Number</label>
          <input
            id="pool_number"
            v-model="form.pool_number"
            type="text"
            class="form-control"
            placeholder="Enter pool number"
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
  name: 'PlayerDivision',
  data() {
    return {
      fieldPlayerDivision: ['division', 'name', 'pool_number', 'action'],
      playerDivisions: [],
      divisions: [],
      players: [],
      form: {
        division_id: null,
        name: null,
        player_id: null,
        height: null,
        weight: null,
        achievement: null
      },
      modalState: null,
      errorValidation: null,
      filterDivisionId: null
    };
  },

  methods: {
    async getDivisions() {
      try {
        const divisions = await this.$axios.get('master/division/get_all');
        this.divisions = divisions.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Fetch Divisions');
      }
    },

    async getPlayers() {
      try {
        const players = await this.$axios.get('master/player/get_all');
        this.players = players.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Fetch Players');
      }
    },

    async getAllPlayerDivisions() {
      try {
        const playerDivisions = await this.$axios.get('entry/player_division/get_all');
        this.playerDivisions = playerDivisions.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Get Data');
      }
    },

    async insertData() {
      try {
        await this.$axios.post('entry/player_division/insert', {
          division_id: this.form.division_id,
          player_id: this.form.player_id,
          pool_number: this.form.pool_number
        });

        this.$noty.success('Success Insert Data');
        this.getAllPlayerDivisions();
        this.$bvModal.hide('modal-player-division');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Insert Data');
      }
    },

    async updateData() {
      try {
        await this.$axios.post(`entry/player_division/update/${this.form.id}`, {
          division_id: this.form.division_id,
          player_id: this.form.player_id,
          pool_number: this.form.pool_number
        });

        this.$noty.success('Success Update Data');
        this.getAllPlayerDivisions();
        this.$bvModal.hide('modal-player-division');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Update Data');
      }
    },

    async deleteData(item) {
      try {
        await this.$axios.post('entry/player_division/delete', {
          id: item.id
        });

        this.$noty.success('Success Delete Data');
        this.getAllPlayerDivisions();
        this.$bvModal.hide('modal-player-division');

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

    async filterData(divisionId) {
      try {
        const playerDivisions = await this.$axios.get(`entry/player_division/filter_division/${divisionId}`);
        this.playerDivisions = playerDivisions.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Filter Data');
      }
    },

    addData() {
      this.resetData();
      this.$bvModal.show('modal-player-division');
      this.modalState = 'add';
    },

    loadData(item) {
      this.resetData();
      this.$bvModal.show('modal-player-division');
      this.modalState = 'update';
      // populate form
      let { id, division_id, player_id, pool_number } = item;
      this.form.id = id;
      this.form.division_id = division_id;
      this.form.player_id = player_id;
      this.form.pool_number = pool_number;
    },

    resetData() {
      this.errorValidation = null;
      this.form.division_id = null;
      this.form.player_id = null;
      this.form.pool_number = null;
    }
  },

  created() {
    this.getAllPlayerDivisions();
    this.getDivisions();
    this.getPlayers();
  }
};
</script>
