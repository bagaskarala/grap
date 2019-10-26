<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Log Match List</span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              @click.prevent="addData()"
            >
              Add log match
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
              v-show="logMatchs.length == 0"
              class="my-3 text-center"
            >Empty Data</div>

            <b-table
              v-if="logMatchs.length != 0"
              striped
              hover
              responsive
              :items="logMatchs"
              :fields="fieldLogMatch"
            >
              <template v-slot:cell(division)="data">
                <div class="min-width-7">
                  <span>{{data.item.division}}</span><br>
                </div>
              </template>
              <template v-slot:cell(player1_name)="data">
                <div class="min-width-10">
                  <span class="font-weight-bold">{{data.item.player1_name}}</span><br>
                  <span class="small text-muted">{{data.item.player1_club}}</span>
                </div>
              </template>
              <template v-slot:cell(player2_name)="data">
                <div class="min-width-10">
                  <span class="font-weight-bold">{{data.item.player2_name}}</span><br>
                  <span class="small text-muted">{{data.item.player2_club}}</span>
                </div>
              </template>
              <template v-slot:cell(vs)="data">
                <span class="h5 font-weight-bold">VS</span>
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
      id="modal-log-match"
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
          <label for="pd1_id">Player Division 1</label>
          <select
            name="pd1_id"
            id="pd1_id"
            class="form-control"
            v-model.number="form.pd1_id"
          >
            <option :value="null">Select Player</option>
            <option
              v-for="item in playerDivisions"
              :key="item.id"
              :value="item.id"
            >{{item.name}}</option>
          </select>
        </div>
        <div class="form-group">
          <label for="pd2_id">Player Division 2</label>
          <select
            name="pd2_id"
            id="pd2_id"
            class="form-control"
            v-model.number="form.pd2_id"
          >
            <option :value="null">Select Player</option>
            <option
              v-for="item in playerDivisions"
              :key="item.id"
              :value="item.id"
            >{{item.name}}</option>
          </select>
        </div>
        <div class="form-group">
          <label for="match_system">Match System</label>
          <select
            name="match_system"
            id="match_system"
            class="form-control"
            v-model="form.match_system"
          >
            <option :value="null">Select Match System</option>
            <option
              v-for="item in matchSystem"
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
      fieldLogMatch: [
        {
          'key': 'division'
        },
        {
          'key': 'match_system'
        },
        {
          'key': 'player1_name',
          'label': 'Player 1'
        },
        {
          'key': 'vs',
          'label': ''
        },
        {
          'key': 'player2_name',
          'label': 'Player 2'
        },
        {
          'key': 'action'
        }
      ],
      matchSystem: [
        { text: 'Round Robin', value: 'roundrobin' },
        { text: 'Elimination', value: 'elimination' }
      ],
      logMatchs: [],
      playerDivisions: [],
      divisions: [],
      winnings: [],
      form: {
        division: null,
        pd1_id: null,
        pd2_id: null,
        match_system: null
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

    async getWinnings() {
      try {
        const winnings = await this.$axios.get('master/winning/get_all');
        this.winnings = winnings.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Fetch Winnings');
      }
    },

    async getPlayerDivisions() {
      try {
        const playerDivisions = await this.$axios.get('entry/player_division/get_all');
        this.playerDivisions = playerDivisions.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Fetch player division');
      }
    },

    async getAllLogMatchs() {
      try {
        const logMatchs = await this.$axios.get('entry/log_match/get_all');
        this.logMatchs = logMatchs.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Get Data');
      }
    },

    async insertData() {
      try {
        await this.$axios.post('entry/log_match/insert', {
          division_id: this.form.division_id,
          pd1_id: this.form.pd1_id,
          pd2_id: this.form.pd2_id,
          match_system: this.form.match_system
        });

        this.$noty.success('Success Insert Data');
        this.getAllLogMatchs();
        this.$bvModal.hide('modal-log-match');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Insert Data');
      }
    },

    async updateData() {
      try {
        await this.$axios.post(`entry/log_match/update/${this.form.id}`, {
          division_id: this.form.division_id,
          pd1_id: this.form.pd1_id,
          pd2_id: this.form.pd2_id,
          match_system: this.form.match_system
        });

        this.$noty.success('Success Update Data');
        this.getAllLogMatchs();
        this.$bvModal.hide('modal-log-match');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Update Data');
      }
    },

    async deleteData(item) {
      try {
        await this.$axios.post('entry/log_match/delete', {
          id: item.id
        });

        this.$noty.success('Success Delete Data');
        this.getAllLogMatchs();
        this.$bvModal.hide('modal-log-match');

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
        const logMatchs = await this.$axios.get(`entry/log_match/filter_division/${divisionId}`);
        this.logMatchs = logMatchs.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Filter Data');
      }
    },

    addData() {
      this.resetData();
      this.$bvModal.show('modal-log-match');
      this.modalState = 'add';
    },

    loadData(item) {
      this.resetData();
      this.$bvModal.show('modal-log-match');
      this.modalState = 'update';
      // populate form
      let { id, division_id, pd1_id, pd2_id, match_system } = item;
      this.form.id = id;
      this.form.division_id = division_id;
      this.form.pd1_id = pd1_id;
      this.form.pd2_id = pd2_id;
      this.form.match_system = match_system;
    },

    resetData() {
      this.errorValidation = null;
      this.form.division_id = null;
      this.form.pd1_id = null;
      this.form.pd2_id = null;
      this.form.match_system = null;
    }
  },

  created() {
    this.getWinnings();
    this.getDivisions();
    this.getAllLogMatchs();
    this.getPlayerDivisions();
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