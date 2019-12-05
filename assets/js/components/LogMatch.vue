<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Log Match</span>
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
                autofocus
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
            </div>

            <div class="mt-3">
              <div class="input-group input-group-sm">
                <div class="input-group-prepend">
                  <span class="input-group-text">Choose System</span>
                </div>
                <select
                  name="match_system"
                  id="match_system"
                  class="form-control"
                  v-model="selectedMatchSystem"
                  :disabled="logMatchs.length > 0 || filterDivisionId==null"
                >
                  <option
                    v-for="item in matchSystemOptions"
                    :key="item.value"
                    :value="item.value"
                  >{{item.text}}</option>
                </select>
                <div class="input-group-append">
                  <button
                    class="btn btn-primary"
                    type="button"
                    :disabled="logMatchs.length > 0"
                    @click.prevent="generateSchedule()"
                  >Generate</button>
                  <button
                    class="btn btn-secondary btn-sm"
                    type="button"
                    title="Clear Schedule"
                    :disabled="logMatchs.length == 0 || filterDivisionId==null"
                    @click.prevent="confirmResetSchedule()"
                  >Clear</button>
                </div>
              </div>
            </div>

            <div v-if="filterDivisionId != null && selectedMatchSystem == 'elimination'">
              <p class="my-2 small text-muted"><i class="fa fa-info-circle"></i> Generate Player only on elimination match</p>
              <button
                class="btn btn-sm btn-primary"
                type="button"
                title="Generate Player after generate schedule"
                :disabled="logMatchs.length == 0"
                @click.prevent="generatePlayer()"
              >Generate Player</button>
              <button
                class="btn btn-sm btn-secondary"
                type="button"
                :disabled="logMatchs.length == 0"
                @click.prevent="confirmResetPlayer()"
              >Reset Player</button>
              <button
                class="btn btn-sm btn-success"
                type="button"
                :disabled="logMatchs.length == 0 || lockMatch || !logMatchs[0].pd1_id"
                @click.prevent="startMatch()"
              >Start Match</button>
            </div>
          </div>

          <div class="card-body">
            <div
              v-show="logMatchs.length == 0"
              class="my-3 text-center"
            >{{filterDivisionId == null? 'Select division to view log match' : 'Empty Data'}}</div>

            <div
              v-if="filterDivisionId && logMatchs.length != 0"
              class="alert alert-info"
            >Match Finished : {{countMatchFinished}}</div>

            <div
              v-if="selectedMatchSystem=='elimination'"
              class="mb-4"
            >
              <Bracket :rounds="matchRounds">
                <template v-slot:player="player">
                  {{ player.player.name }}
                </template>
              </Bracket>

              <span v-if="thirdPlaceRound.length!=0">3rd place</span>
              <Bracket :rounds="thirdPlaceRound">
                <template v-slot:player="player">
                  {{ player.player.name }}
                </template>
              </Bracket>
            </div>

            <b-table
              v-if="logMatchs.length != 0"
              striped
              hover
              responsive
              :items="logMatchs"
              :fields="fieldLogMatch"
              :tbody-tr-class="rowClass"
            >
              <template v-slot:cell(division)="data">
                <div class="min-width-7">
                  <span>{{data.item.division}}</span><br>
                </div>
              </template>

              <template v-slot:cell(pool_number)="data">
                <span
                  v-if="data.item.match_index == 1 && data.item.match_number == 1 && !data.item.pool_number"
                  class="badge badge-primary"
                >
                  FINAL
                </span>
                <span
                  v-if="data.item.match_index == 1 && data.item.match_number == 2 && !data.item.pool_number"
                  class="badge badge-primary"
                >
                  3rd
                </span>
                <span
                  v-else
                  class="badge"
                  :class="[data.item.pool_number ? data.item.pool_number == 'A'? 'badge-dark':'badge-danger' : null]"
                >
                  {{data.item.pool_number != null? data.item.pool_number : ''}}
                </span>
              </template>

              <template v-slot:cell(player1_name)="data">
                <div
                  v-if="!data.item.player1_name && data.item.match_index == 1 && data.item.match_system == 'elimination' && data.item.winner != -1"
                  class="text-danger font-italic"
                >Click Start Match to skip 'BYE'</div>
                <div
                  v-else
                  class="min-width-10"
                  :class="winnerMark(data.item,1)"
                >
                  <span class="font-weight-bold">{{data.item.player1_name}}</span>
                  <span
                    v-if="data.item.player1_last_achievement"
                    class="badge badge-secondary"
                    :title="tooltipAchievement(data.item.player1_last_achievement)"
                  >
                    {{data.item.player1_last_achievement.winner_position}}
                  </span>
                  <br>
                  <span class="small text-muted">{{data.item.player1_club}}</span>
                </div>
              </template>

              <template v-slot:cell(player2_name)="data">
                <div
                  v-if="!data.item.player2_name && data.item.match_index == 1 && data.item.match_system == 'elimination' && data.item.winner != -1"
                  class="text-danger font-italic"
                >Click Start match to skip 'BYE'</div>
                <div
                  v-else
                  class="min-width-10"
                  :class="winnerMark(data.item,2)"
                >
                  <span class="font-weight-bold">{{data.item.player2_name}}</span>
                  <span
                    v-if="data.item.player2_last_achievement"
                    class="badge badge-secondary"
                    :title="tooltipAchievement(data.item.player2_last_achievement)"
                  >
                    {{data.item.player2_last_achievement.winner_position}}
                  </span>
                  <br>
                  <span class="small text-muted">{{data.item.player2_club}}</span>
                </div>
              </template>

              <template v-slot:cell(vs)="data">
                <span class="h5 font-weight-bold">VS</span>
              </template>

              <template v-slot:cell(action)="data">
                <div
                  v-if="data.item.winner != -1"
                  class="min-width-10"
                >
                  <button
                    :disabled="(data.item.pd1_id == null || data.item.pd2_id == null) && data.item.winner == -1"
                    class="btn btn-sm btn-primary"
                    @click.prevent="goToDetail(data.item)"
                  ><i class="fa fa-eye fa-fw"></i></button>
                  <button
                    :title="lockMatch? 'Disabled when match has been started' : 'Edit player matching'"
                    :disabled="lockMatch"
                    class="btn btn-sm btn-warning"
                    @click.prevent="loadData(data.item)"
                  ><i class="fa fa-edit fa-fw"></i></button>
                </div>
                <div
                  v-else
                  class="text-left text-danger"
                >BYE</div>
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
            disabled
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
          <label for="pd1_id">Player 1</label>
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
            >{{item.name}} ({{item.club}}) {{item.weight}}kg</option>
          </select>
        </div>
        <div class="form-group">
          <label for="pd2_id">Player 2</label>
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
            >{{item.name}} ({{item.club}}) {{item.weight}}kg</option>
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
import Bracket from 'vue-tournament-bracket';
import { parseWinner } from '../shared';
export default {
  name: 'LogMatch',
  props: {
    baseUrl: String,
    divisionId: String
  },
  components: {
    Bracket
  },

  data() {
    return {
      matchSystemOptions: [
        { text: 'Round Robin', value: 'roundrobin' },
        { text: 'Elimination', value: 'elimination' }
      ],
      logMatchs: [],
      playerDivisions: [],
      divisions: [],
      winnings: [],
      form: {
        division_id: null,
        pd1_id: null,
        pd2_id: null
      },
      modalState: null,
      errorValidation: null,
      filterDivisionId: null,
      selectedMatchSystem: 'elimination',
      lockMatchTemporary: false
    };
  },

  computed: {
    matchRounds() {
      const container = [];
      // looping pada match index
      for (let index = this.matchIndex.min; index <= this.matchIndex.max; index++) {
        const roundGames = this.logMatchs.filter(f => f.match_index == index && f.match_number != 0)
          .map(m => {
            return {
              player1: {
                id: m.pd1_id,
                name: this.checkPlayer(m, 1),
                winner: this.checkWinner(m, 1)
              },
              player2: {
                id: m.pd2_id,
                name: this.checkPlayer(m, 2),
                winner: this.checkWinner(m, 2)
              }
            };
          });
        container.push({ games: roundGames });
      }
      return container;
    },

    thirdPlaceRound() {
      if (!this.filterDivisionId) return [];
      const thirdPlaceMatch = this.logMatchs.find(item => item.match_number == 0);
      if (!thirdPlaceMatch) return [];

      return [
        {
          games: [
            {
              player1: {
                id: thirdPlaceMatch.pd1_id,
                name: this.checkPlayer(thirdPlaceMatch, 1),
                winner: this.checkWinner(thirdPlaceMatch, 1)
              },
              player2: {
                id: thirdPlaceMatch.pd2_id,
                name: this.checkPlayer(thirdPlaceMatch, 2),
                winner: this.checkWinner(thirdPlaceMatch, 2)
              }
            }
          ]
        }
      ];
    },

    matchIndex() {
      let arr = Object.values(this.logMatchs.map(item => item.match_index));
      let min = Math.min(...arr);
      let max = Math.max(...arr);

      return {
        max, min
      };
    },

    fieldLogMatch() {
      return [
        {
          'key': 'division'
        },
        {
          'key': 'match_system'
        },
        {
          'key': 'match_index'
        },
        {
          'key': this.selectedMatchSystem == 'elimination' ? 'match_number' : 'pool_number'
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
      ];
    },

    countMatchFinished() {
      return this.logMatchs.filter(item => item.winner != null && item.winner != -1).length
        + ' / ' +
        this.logMatchs.length;
    },

    lockMatch() {
      return this.logMatchs.find(item => item.winner != null && item.winner >= 0 || item.match_status == 2 || this.lockMatchTemporary) ? true : false;
    }
  },

  methods: {
    parseWinner,

    checkPlayer(m, playerNumber) {
      // ambil player
      if (m.match_status == 2) {
        return playerNumber == 1 ? `${m.player1_name || 'Bye'} (${m.player1_club_alias || '-'})` : `${m.player2_name || 'Bye'} (${m.player2_club_alias || '-'})`;
      } else {
        return playerNumber == 1 ? `${m.player1_name || '...'} (${m.player1_club_alias || '...'})` : `${m.player2_name || '...'} (${m.player2_club_alias || '...'})`;
      }
    },

    checkWinner(m, playerNumber) {
      // jika belum ada pemenang, set winner ke null
      // jika skipped match maka set ke win
      // lalu set true dan false tergantung pemenang
      if (m.winner == null) return null;
      else if (m.winner == -1 && playerNumber == 1 && m.pd1_id == null) return null;
      else if (m.winner == -1 && playerNumber == 2 && m.pd2_id == null) return null;
      else if (m.winner == -1) return true;
      else if (m.winner == 0) return null;

      if (playerNumber == 1) {
        return m.winner == m.pd1_id ? true : false;
      } else {
        return m.winner == m.pd2_id ? true : false;
      }
    },

    async getDivisions() {
      try {
        const divisions = await this.$axios.get('master/division/get_all');
        this.divisions = divisions.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Fetch Divisions');
      }
    },

    async getPlayerDivisions() {
      try {
        const playerDivisions = await this.$axios.get(`entry/player_division/filter_division/${this.form.division_id}`);
        this.playerDivisions = playerDivisions.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Fetch player division');
      }
    },

    async updateData() {
      // jika p1 dan p2 sama maka error
      if (this.form.pd1_id && this.form.pd2_id && this.form.pd1_id == this.form.pd2_id) {
        this.$noty.error('Cannot matching same player');
        return;
      }

      // jika keduanya tidak null, maka cek lebih lanjut
      if (this.form.pd1_id || this.form.pd2_id) {
        const checkGeneratedPlayer = this.logMatchs.find(lm => {
          return lm.pd1_id == this.form.pd1_id && lm.pd2_id == this.form.pd2_id || lm.pd1_id == this.form.pd2_id && lm.pd2_id == this.form.pd1_id;
        });

        if (checkGeneratedPlayer) {
          this.$noty.error('This player matching has been generated');
          return;
        }
      }

      try {
        await this.$axios.post(`entry/log_match/update/${this.form.id}`, {
          division_id: this.form.division_id,
          pd1_id: this.form.pd1_id,
          pd2_id: this.form.pd2_id
        });

        // tampilkan data setelah aksi
        if (this.filterDivisionId) {
          this.filterData(this.filterDivisionId);
        } else {
          this.getAllPlayerDivisions();
        }

        this.$noty.success('Success Update Data');
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

        // tampilkan data setelah aksi
        if (this.filterDivisionId) {
          this.filterData(this.filterDivisionId);
        } else {
          this.getAllPlayerDivisions();
        }

        this.$noty.success('Success Delete Data');
        this.$bvModal.hide('modal-log-match');

      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Delete Data');
      }
    },

    confirmResetSchedule() {
      this.$bvModal.msgBoxConfirm('Please confirm that you want to clear this match schedule', {
        title: 'Clear Schedule',
        size: 'md',
        okVariant: 'danger',
        centered: true
      })
        .then(value => {
          if (value) {
            this.resetSchedule();
          }
        })
        .catch(err => {
          console.log('Error ', err);
        });
    },

    confirmResetPlayer() {
      this.$bvModal.msgBoxConfirm('Please confirm that you want to reset player schedule', {
        title: 'Reset Player',
        size: 'md',
        okVariant: 'danger',
        centered: true
      })
        .then(value => {
          if (value) {
            this.resetPlayer();
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

        // set match system from db
        if (logMatchs.data.data.length != 0) {
          this.selectedMatchSystem = logMatchs.data.data[0].match_system;
        }
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Filter Data');
      }
    },

    async generateSchedule() {
      if (this.filterDivisionId == null) {
        this.$noty.warning('Select division first before generate schedule');
        return;
      }

      try {
        const a = await this.$axios.post('entry/log_match/generate_schedule', {
          division_id: this.filterDivisionId,
          match_system: this.selectedMatchSystem
        });
        console.log(a.data.data);
        this.filterData(this.filterDivisionId);
        this.$noty.success('Success Generate Schedule');
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Generate Schedule. ' + error.response.data.message);
      }
    },

    async resetSchedule() {
      if (this.filterDivisionId == null) {
        this.$noty.warning('Select division first before reset schedule');
        return;
      }

      try {
        await this.$axios.post('entry/log_match/reset_schedule', {
          division_id: this.filterDivisionId
        });
        this.filterData(this.filterDivisionId);
        this.$noty.success('Success Reset Schedule');
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Reset Schedule');
      }
    },

    async generatePlayer() {
      if (this.filterDivisionId == null) {
        this.$noty.warning('Select division first before generate player');
        return;
      }

      try {
        await this.$axios.post('entry/log_match/generate_player', {
          division_id: this.filterDivisionId,
          match_system: this.selectedMatchSystem
        });
        this.filterData(this.filterDivisionId);
        this.$noty.success('Success Generate Player');
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Generate Player. ' + error.response.data.message);
      }
    },

    async resetPlayer() {
      if (this.filterDivisionId == null) {
        this.$noty.warning('Select division first before reset player');
        return;
      }

      try {
        await this.$axios.post('entry/log_match/reset_player', {
          division_id: this.filterDivisionId
        });
        this.filterData(this.filterDivisionId);
        this.$noty.success('Success Reset Player');
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Reset Player');
      }
    },

    async startMatch() {
      this.lockMatchTemporary = true;
      try {
        await this.$axios.post('entry/log_match/start_match', {
          division_id: this.filterDivisionId
        });
        this.filterData(this.filterDivisionId);
        this.$noty.success('Success Start Match');
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Start Match. ' + error.response.data.message);
      }
    },

    goToDetail(item) {
      window.location.href = `log_match/detail/${item.id}`;
    },

    loadData(item) {
      this.resetData();
      this.$bvModal.show('modal-log-match');
      this.modalState = 'update';
      // populate form
      let { id, division_id, pd1_id, pd2_id } = item;
      this.form.id = id;
      this.form.division_id = division_id;
      this.form.pd1_id = pd1_id;
      this.form.pd2_id = pd2_id;
    },

    resetData() {
      this.errorValidation = null;
      this.form.division_id = null;
      this.form.pd1_id = null;
      this.form.pd2_id = null;
    },

    rowClass(item) {
      if (!item) return;
      if (item.winner === -1) return 'table-secondary';
    },

    winnerMark(item, playerDivision) {
      if (item.match_status == 0) return '';

      // skipped match
      if (item.winner == -1) return 'text-dark';

      // draw
      if (item.match_status == 2 && item.winner == 0) {
        return 'text-primary';
      }

      // win or lose
      if (playerDivision == 1 && item.match_status == 2) {
        if (item.winner == item.pd1_id) {
          return 'text-success';
        } else {
          return 'text-danger';
        }
      } else {
        if (item.winner == item.pd2_id) {
          return 'text-success';
        } else {
          return 'text-danger';
        }
      }
    },

    tooltipAchievement(achievement) {
      return `${parseWinner(achievement.winner_position)} winner on ${achievement.tournament_name} (${achievement.city} ${achievement.achievement_year})`;
    }
  },

  watch: {
    'form.division_id'() {
      this.getPlayerDivisions();
    }
  },

  created() {
    // auto pilih division yang tersimpan di session
    if (this.divisionId) {
      this.filterDivisionId = parseInt(this.divisionId);
      this.filterData(parseInt(this.divisionId));
    }

    this.getDivisions();
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