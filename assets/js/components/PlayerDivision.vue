<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Player Division List</span>
          </div>

          <div class="mx-3 mt-3">
            <div class="input-group input-group-sm">
              <div class="input-group-prepend">
                <span class="input-group-text">Division</span>
              </div>
              <select
                name="filter_division"
                id="filter_division"
                class="form-control"
                v-model.number="filterDivisionId"
                @change="filterData(filterDivisionId)"
              >
                <option
                  :value="null"
                  disabled
                >--- Select Division ---</option>
                <option
                  v-for="item in divisions"
                  :key="item.id"
                  :value="item.id"
                >{{item.division}}</option>
              </select>
              <div class="input-group-append">
                <button
                  type="button"
                  class="btn btn-sm btn-primary"
                  @click.prevent="addData()"
                >
                  Add player
                </button>
              </div>
            </div>

            <div
              v-if="filterDivisionId"
              class="d-flex justify-content-start mt-3"
            >
              <button
                class="btn btn-sm btn-success mr-1"
                type="button"
                :disabled="playerDivisions.length==0"
                @click.prevent="generatePool()"
              >Generate Pool</button>
              <button
                class="btn btn-sm btn-secondary"
                type="button"
                :disabled="playerDivisions.length==0"
                @click.prevent="resetPool()"
              >Reset Pool</button>
            </div>
          </div>

          <div class="card-body">
            <div
              v-show="playerDivisions.length == 0"
              class="my-3 text-center"
            >{{filterDivisionId == null? 'Select division to view player' : 'Empty Data'}}</div>

            <b-table
              v-if="playerDivisions.length != 0"
              striped
              hover
              responsive
              :items="playerDivisions"
              :fields="fieldPlayerDivision"
            >
              <template v-slot:cell(pool_number)="data">
                <span
                  class="badge"
                  :class="[data.item.pool_number ? data.item.pool_number == 'A'? 'badge-dark':'badge-danger' : null]"
                >
                  {{data.item.pool_number != null? data.item.pool_number : ''}}
                </span>
              </template>
              <template v-slot:cell(action)="data">
                <div class="min-width-7">
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
          <label for="division_id">Assign to Division</label>
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
          <label for="player_id">Select Player</label>
          <div class="input-group input-group-sm mb-2">
            <div class="input-group-prepend">
              <span class="input-group-text">Filter by Weight</span>
            </div>
            <input
              id="min_weight"
              v-model="playerFilter.minWeight"
              type="text"
              class="form-control"
              placeholder="Enter Min Weight"
              title="Min Weight"
              maxlength="3"
            >
            <input
              id="max_weight"
              v-model="playerFilter.maxWeight"
              type="text"
              class="form-control"
              placeholder="Enter Max Weight"
              title="Max Weight"
              maxlength="3"
            >
          </div>
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
            >{{item.name}} ({{item.club}}) {{item.weight}}kg</option>
          </select>
        </div>
        <div class="form-group">
          <label for="pool_number">Pool Number</label>
          <select
            name="pool_number"
            id="pool_number"
            class="form-control"
            v-model="form.pool_number"
          >
            <option :value="null">Select pool number</option>
            <option
              v-for="item in poolOptions"
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
      fieldPlayerDivision: ['division', 'club', 'name', 'pool_number', 'win', 'draw', 'lose', 'action'],
      poolOptions: [
        { text: 'Pool A', value: 'A' },
        { text: 'Pool B', value: 'B' }
      ],
      playerDivisions: [],
      divisions: [],
      players: [],
      form: {
        division_id: null,
        name: null,
        player_id: null,
        height: null,
        weight: null,
        achievement: null,
        pool_number: null
      },
      playerFilter: {
        minWeight: 0,
        maxWeight: 200
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

    async getFilteredPlayers() {
      try {
        const players = await this.$axios.post('master/player/filter', {
          min_weight: this.playerFilter.minWeight,
          max_weight: this.playerFilter.maxWeight
        });
        this.players = players.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Fetch Filtered Players');
      }
    },

    async getAllPlayerDivisions() {
      try {
        const playerDivisions = await this.$axios.get('entry/player_division/get_all');
        this.playerDivisions = playerDivisions.data.data;
        this.filterDivisionId = null;
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

        // tampilkan data setelah aksi
        // menuju tampilan divisi yang telah terinput
        this.filterData(this.form.division_id);
        this.filterDivisionId = this.form.division_id;

        this.$noty.success('Success Insert Data');
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

        // tampilkan data setelah aksi
        if (this.filterDivisionId) {
          this.filterData(this.filterDivisionId);
        }

        this.$noty.success('Success Update Data');
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

        // tampilkan data setelah aksi
        if (this.filterDivisionId) {
          this.filterData(this.filterDivisionId);
        }

        this.$noty.success('Success Delete Data');
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

    async generatePool() {
      if (this.filterDivisionId == null) {
        this.$noty.warning('Select division first before generate pool');
        return;
      }

      try {
        await this.$axios.post('entry/player_division/generate_pool', {
          division_id: this.filterDivisionId
        });
        this.filterData(this.filterDivisionId);
        this.$noty.success('Success Generate Pool');
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Generate Pool. ' + error.response.data.message);
      }
    },

    async resetPool() {
      if (this.filterDivisionId == null) {
        this.$noty.warning('Select division first before reset pool');
        return;
      }

      try {
        await this.$axios.post('entry/player_division/reset_pool', {
          division_id: this.filterDivisionId
        });
        this.filterData(this.filterDivisionId);
        this.$noty.success('Success Reset Pool');
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Reset Pool');
      }
    },

    addData() {
      this.resetData();
      this.$bvModal.show('modal-player-division');
      this.modalState = 'add';
      // auto select division, ketika filternya sedang aktif
      if (this.filterDivisionId) {
        this.form.division_id = this.filterDivisionId;
      }
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
    // this.getAllPlayerDivisions();
    this.getDivisions();
    this.getPlayers();
  },

  watch: {
    'form.division_id'(val) {
      let findDivision = this.divisions.find(item => item.id === val);
      // jika divisi tidak ditemukan, set min max ke default
      if (!val || !findDivision) {
        findDivision = {
          min_weight: 0,
          max_weight: 200
        };
      }

      // set max min weight
      this.playerFilter.minWeight = findDivision.min_weight;
      this.playerFilter.maxWeight = findDivision.max_weight;
    },

    playerFilter: {
      handler: function (newValue) {
        this.playerFilter.minWeight = newValue.minWeight;
        this.playerFilter.maxWeight = newValue.maxWeight;
        // NEED DEBOUNCE, HEMAT RESOURCE
        this.getFilteredPlayers();
      },
      deep: true,
      immediate: true
    }
  }
};
</script>

<style lang="css">
.min-width-10 {
  min-width: 10rem;
}
.min-width-7 {
  min-width: 7rem;
}
</style>
