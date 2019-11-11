<template>
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <a :href="baseUrl + 'entry/log_match'"><i class="fa fa-angle-double-left"></i> Back to Log Match</a>
      <div>
        <button
          type="button"
          class="btn btn-danger"
          @click="resetData()"
        >Reset Match</button>
        <button
          class="btn btn-warning"
          @click="loadData()"
        >Update Match</button>
      </div>
    </div>
    <div class="row">
      <div class="col p-3 order-2 order-md-1">
        <img
          :src="baseUrl + '/assets/img/user_avatar.png'"
          class="rounded mx-auto d-block"
          alt="..."
        >
      </div>
      <div class="col-md-6 order-1 order-md-2">
        <div class="card card-default mb-md-3">
          <div class="card-header font-weight-bold">
            Match Detail
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span>Division <i class="fa fa-angle-double-right"></i></span>
              <span>{{this.logMatchDetail.division}}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span>Match System <i class="fa fa-angle-double-right"></i></span>
              <span>{{this.logMatchDetail.match_system != null ?this.logMatchDetail.match_system.toUpperCase():''}}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span>Match No <i class="fa fa-angle-double-right"></i></span>
              <span>{{this.logMatchDetail.match_index}}.{{this.logMatchDetail.match_number}}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span>Time <i class="fa fa-angle-double-right"></i></span>
              <span>{{showTime}}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span>Status <i class="fa fa-angle-double-right"></i></span>
              <span
                class="badge"
                :class="this.logMatchDetail.match_status == 1? 'badge-success': 'badge-dark'"
              >{{this.logMatchDetail.match_status == 0? 'Idle' : this.logMatchDetail.match_status == 1? 'Playing' : 'Finished' }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span>Winning by <i class="fa fa-angle-double-right"></i></span>
              <span>{{this.logMatchDetail.winning}}</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <StopWatch
                  @start-timer="startTimer"
                  @stop-timer="stopTimer"
                />
              </div>
              <div>
                Referee<br>
                <span class="font-weight-bold text-primary">FOO BAR</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col p-3 order-3 order-md-3">
        <img
          :src="baseUrl + '/assets/img/user_avatar.png'"
          class="rounded mx-auto d-block"
          alt="..."
        >
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card card-default mb-3">
          <ul class="list-group list-group-flush">
            <li
              class="list-group-item"
              :class="[logMatchDetail.winner==logMatchDetail.pd1_id? 'list-group-item-success' : '']"
            >
              <div class="d-flex justify-content-between align-items-center font-weight-bold text-uppercase">
                <h3 class="mb-0">{{this.logMatchDetail.player1_name || '...'}}</h3>
                <span
                  v-if="logMatchDetail.winner==logMatchDetail.pd1_id"
                  class="badge badge-success"
                >Winner</span>
                <span
                  v-if="logMatchDetail.winner==0"
                  class="badge badge-primary"
                >Draw</span>
              </div>
            </li>
            <li class="list-group-item">
              <span class="small text-muted">Club</span><br>
              {{this.logMatchDetail.player1_club || '...'}}
            </li>
            <li class="list-group-item">
              <span class="small text-muted">Country</span><br>
              {{this.logMatchDetail.player1_country || '...'}}
            </li>
            <li class="list-group-item">
              <span class="small text-muted">Weight</span><br>
              {{this.logMatchDetail.player1_weight || '...'}} kg
            </li>
            <li class="list-group-item">
              <span class="small text-muted">Card</span><br>
              <span><i class="fa fa-square text-danger"></i> {{this.logMatchDetail.pd1_redcard || '...'}}</span><br>
              <span><i class="fa fa-square text-warning"></i> {{this.logMatchDetail.pd1_yellowcard || '...'}}</span><br>
              <span><i class="fa fa-square text-success"></i> {{this.logMatchDetail.pd1_greencard || '...'}}</span><br>
            </li>
          </ul>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-default mb-3">
          <ul class="list-group list-group-flush">
            <li
              class="list-group-item"
              :class="[logMatchDetail.winner==logMatchDetail.pd2_id? 'list-group-item-success' : '']"
            >
              <div class="d-flex justify-content-between align-items-center font-weight-bold text-uppercase">
                <h3 class="mb-0">{{this.logMatchDetail.player2_name || '...'}}</h3>
                <span
                  v-if="logMatchDetail.winner==logMatchDetail.pd2_id"
                  class="badge badge-success"
                >Winner</span>
                <span
                  v-if="logMatchDetail.winner==0"
                  class="badge badge-primary"
                >Draw</span>
              </div>
            </li>
            <li class="list-group-item">
              <span class="small text-muted">Club</span><br>
              {{this.logMatchDetail.player2_club || '...'}}
            </li>
            <li class="list-group-item">
              <span class="small text-muted">Country</span><br>
              {{this.logMatchDetail.player2_country || '...'}}
            </li>
            <li class="list-group-item">
              <span class="small text-muted">Weight</span><br>
              {{this.logMatchDetail.player2_weight || '...'}} kg
            </li>
            <li class="list-group-item">
              <span class="small text-muted">Card</span><br>
              <span><i class="fa fa-square text-danger"></i> {{this.logMatchDetail.pd2_redcard || '...'}}</span><br>
              <span><i class="fa fa-square text-warning"></i> {{this.logMatchDetail.pd2_yellowcard || '...'}}</span><br>
              <span><i class="fa fa-square text-success"></i> {{this.logMatchDetail.pd2_greencard || '...'}}</span><br>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- modal update log match-->
    <b-modal
      id="modal-update-log-match"
      hide-footer
      title="Update Log Match"
      size="lg"
    >
      <div
        v-if="errorValidation"
        class="alert alert-danger"
        v-html="errorValidation"
      ></div>

      <form method="post">
        <div class="form-group">
          <label for="time">Time <em>(hour : minute : second : millisecond)</em></label>
          <div class="input-group">
            <input
              type="number"
              v-model.number="form.time.hours"
              placeholder="hours"
              class="form-control"
            >
            <input
              type="number"
              v-model.number="form.time.minutes"
              placeholder="minutes"
              class="form-control"
            >
            <input
              type="number"
              v-model.number="form.time.seconds"
              placeholder="seconds"
              class="form-control"
            >
            <input
              type="number"
              v-model.number="form.time.milliseconds"
              placeholder="milliseconds"
              class="form-control"
            >
          </div>
          <!-- <input
            id="time"
            v-model.number="form.time"
            type="text"
            class="form-control"
            placeholder="Enter time"
          > -->
        </div>
        <div class="form-group">
          <label for="match_status">Match Status</label>
          <select
            name="match_status"
            id="match_status"
            class="form-control"
            v-model.number="form.match_status"
          >
            <option
              v-for="item in matchStatusOptions"
              :key="item.value"
              :value="item.value"
            >{{item.text}}</option>
          </select>
        </div>
        <div class="form-group">
          <label for="winner">Winner</label>
          <select
            name="winner"
            id="winner"
            class="form-control"
            v-model.number="form.winner"
          >
            <option :value="null">Select Winner</option>
            <option
              v-for="item in winnerOptions"
              :key="item.value"
              :value="item.value"
            >{{item.text}}</option>
          </select>
        </div>
        <div class="form-group">
          <label for="winning">Winning by</label>
          <select
            name="winning"
            id="winning"
            class="form-control"
            v-model.number="form.winning_id"
          >
            <option :value="null">Select Winning by</option>
            <option
              v-for="item in winningOptions"
              :key="item.id"
              :value="item.id"
            >{{item.winning}}</option>
          </select>
        </div>
        <hr>
        <div class="row">
          <div
            class="col border rounded m-2 p-3"
            :class="[form.winner==1? 'border-success' : '']"
          >
            <p class="font-weight-bold mb-3">PLAYER 1
              <span :class="[form.winner==logMatchDetail.pd1_id? 'd-inline-block badge badge-success' : 'd-none']">Winner</span>
            </p>
            <div class="form-group">
              <label for="name">Name</label>
              <input
                id="name"
                readonly
                :value="logMatchDetail.player1_name"
                type="text"
                class="form-control"
              >
            </div>
            <div class="form-group">
              <label for="club">Club</label>
              <input
                id="club"
                readonly
                :value="logMatchDetail.player1_club"
                type="text"
                class="form-control"
              >
            </div>
            <div class="form-group">
              <label for="country">Country</label>
              <input
                id="country"
                readonly
                :value="logMatchDetail.player1_country"
                type="text"
                class="form-control"
              >
            </div>
            <div class="form-group">
              <label for="weight">Weight</label>
              <input
                id="weight"
                readonly
                :value="logMatchDetail.player1_weight"
                type="text"
                class="form-control"
              >
            </div>
            <div class="form-group">
              <label for="yellow_card">Yellow card</label>
              <input
                id="yellow_card"
                v-model.number="form.pd1_yellowcard"
                type="number"
                class="form-control"
              >
            </div>
            <div class="form-group">
              <label for="red_card">Red card</label>
              <input
                id="red_card"
                v-model.number="form.pd1_redcard"
                type="number"
                class="form-control"
              >
            </div>
            <div class="form-group">
              <label for="green_card">Green card</label>
              <input
                id="green_card"
                v-model.number="form.pd1_greencard"
                type="number"
                class="form-control"
              >
            </div>
          </div>
          <div
            class="col border rounded m-2 p-3"
            :class="[form.winner==2? 'border-success' : '']"
          >
            <p class="font-weight-bold mb-3">PLAYER 2
              <span :class="[form.winner==logMatchDetail.pd2_id? 'd-inline-block badge badge-success' : 'd-none']">Winner</span>
            </p>
            <div class="form-group">
              <label for="name">Name</label>
              <input
                id="name"
                readonly
                :value="logMatchDetail.player2_name"
                type="text"
                class="form-control"
              >
            </div>
            <div class="form-group">
              <label for="club">Club</label>
              <input
                id="club"
                readonly
                :value="logMatchDetail.player2_club"
                type="text"
                class="form-control"
              >
            </div>
            <div class="form-group">
              <label for="country">Country</label>
              <input
                id="country"
                readonly
                :value="logMatchDetail.player2_country"
                type="text"
                class="form-control"
              >
            </div>
            <div class="form-group">
              <label for="weight">Weight</label>
              <input
                id="weight"
                readonly
                :value="logMatchDetail.player2_weight"
                type="text"
                class="form-control"
              >
            </div>
            <div class="form-group">
              <label for="yellow_card">Yellow card</label>
              <input
                id="yellow_card"
                v-model.number="form.pd2_yellowcard"
                type="number"
                class="form-control"
              >
            </div>
            <div class="form-group">
              <label for="red_card">Red card</label>
              <input
                id="red_card"
                v-model.number="form.pd2_redcard"
                type="number"
                class="form-control"
              >
            </div>
            <div class="form-group">
              <label for="green_card">Green card</label>
              <input
                id="green_card"
                v-model.number="form.pd2_greencard"
                type="number"
                class="form-control"
              >
            </div>
          </div>
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
    </b-modal>
  </div>
</template>

<script>
import StopWatch from './StopWatch';
export default {
  name: 'LogMatchDetail',
  components: {
    StopWatch
  },
  props: {
    baseUrl: String
  },

  data() {
    return {
      logMatchDetail: {},
      errorValidation: null,
      matchStatusOptions: [
        {
          value: 0,
          text: 'Idle'
        },
        {
          value: 1,
          text: 'Playing'
        },
        {
          value: 2,
          text: 'Finished'
        }
      ],
      form: {
        time: {
          hours: 0,
          minutes: 0,
          seconds: 0,
          milliseconds: 0
        },
        elapsedTime: 0,
        match_status: 0,
        winner: null,
        winning_id: null,
        pd1_yellowcard: 0,
        pd1_redcard: 0,
        pd1_greencard: 0,
        pd2_yellowcard: 0,
        pd2_redcard: 0,
        pd2_greencard: 0
      },
      winningOptions: null
    };
  },

  computed: {
    logMatchId() {
      const url = window.location.href;
      const split = url.split('/');
      return split[split.length - 1];
    },

    winnerOptions() {
      return [
        {
          value: 0,
          text: 'Draw'
        },
        {
          value: this.logMatchDetail.pd1_id,
          text: `Player 1 - ${this.logMatchDetail.player1_name}`
        },
        {
          value: this.logMatchDetail.pd2_id,
          text: `Player 2 - ${this.logMatchDetail.player2_name}`
        }
      ];
    },

    showTime() {
      return this.convertMillisecondToTime(this.logMatchDetail.time, 'hours') + ' h : ' +
        this.convertMillisecondToTime(this.logMatchDetail.time, 'minutes') + ' m : ' +
        this.convertMillisecondToTime(this.logMatchDetail.time, 'seconds') + ' s : ' +
        this.convertMillisecondToTime(this.logMatchDetail.time, 'milliseconds') + ' ms';
    }
  },

  methods: {
    async getDetailLogMatch() {
      try {
        const logMatchDetail = await this.$axios.get(`entry/log_match/get_detail/${this.logMatchId}`);
        this.logMatchDetail = logMatchDetail.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed fetch logmatch detail');
      }
    },

    async getWinning() {
      try {
        const winning = await this.$axios.get('master/winning/get_all');
        this.winningOptions = winning.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed fetch winning');
      }
    },

    async updateData() {
      try {
        const a = await this.$axios.post(`entry/log_match/update/${this.logMatchDetail.id}`, {
          division_id: this.logMatchDetail.division_id,
          time: this.form.elapsedTime ? this.form.elapsedTime : this.convertTimeToMillisecond(this.form.time),
          winner: this.form.winner,
          winning_id: this.form.winning_id,
          match_status: this.form.match_status,
          pd1_yellowcard: this.form.pd1_yellowcard,
          pd1_redcard: this.form.pd1_redcard,
          pd1_greencard: this.form.pd1_greencard,
          pd2_yellowcard: this.form.pd2_yellowcard,
          pd2_redcard: this.form.pd2_redcard,
          pd2_greencard: this.form.pd2_greencard
        });

        console.log(a.data);

        this.getDetailLogMatch();

        this.$noty.success('Success Update Data');
        this.$bvModal.hide('modal-update-log-match');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Update Data');
      }
    },

    loadData() {
      this.$bvModal.show('modal-update-log-match');
      // populate form
      let { match_status, time, winning_id, winner, pd1_redcard, pd1_yellowcard, pd1_greencard, pd2_redcard, pd2_yellowcard, pd2_greencard } = this.logMatchDetail;
      this.form.match_status = match_status;

      this.form.time.hours = this.convertMillisecondToTime(time, 'hours');
      this.form.time.minutes = this.convertMillisecondToTime(time, 'minutes');
      this.form.time.seconds = this.convertMillisecondToTime(time, 'seconds');
      this.form.time.milliseconds = this.convertMillisecondToTime(time, 'milliseconds');

      this.form.winning_id = winning_id;
      this.form.winner = winner;
      this.form.pd1_yellowcard = pd1_yellowcard;
      this.form.pd1_redcard = pd1_redcard;
      this.form.pd1_greencard = pd1_greencard;
      this.form.pd2_yellowcard = pd2_yellowcard;
      this.form.pd2_redcard = pd2_redcard;
      this.form.pd2_greencard = pd2_greencard;
    },

    async resetData() {
      try {
        await this.$axios.post(`entry/log_match/update/${this.logMatchDetail.id}`, {
          division_id: this.logMatchDetail.division_id,
          time: null,
          winner: null,
          winning_id: null,
          match_status: 0,
          pd1_point: 0,
          pd1_yellowcard: 0,
          pd1_redcard: 0,
          pd1_greencard: 0,
          pd2_point: 0,
          pd2_yellowcard: 0,
          pd2_redcard: 0,
          pd2_greencard: 0
        });

        this.getDetailLogMatch();
        this.$noty.success('Success Reset Data');
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Reset Data');
      }
    },

    startTimer() {
      this.form.match_status = 1;
      this.updateData();
    },

    stopTimer(time) {
      this.form.elapsedTime = time.elapsedTime;
      this.form.match_status = 2;
      this.updateData();
      this.form.elapsedTime = 0;
    },

    convertMillisecondToTime(ms, select) {
      if (select == 'hours') return Math.floor(ms / 1000 / 60 / 60);
      else if (select == 'minutes') return Math.floor(ms / 1000 / 60) % 60;
      else if (select == 'seconds') return Math.floor(ms / 1000) % 60;
      else if (select == 'milliseconds') return Math.floor(ms % 1000);
      else return null;
    },

    convertTimeToMillisecond({ hours, minutes, seconds, milliseconds }) {
      if (hours) {
        hours = hours * 1000 * 60 * 60;
      }
      if (minutes) {
        minutes = minutes * 1000 * 60;
      }
      if (seconds) {
        seconds = seconds * 1000;
      }
      return hours + minutes + seconds + milliseconds;
    }
  },

  created() {
    this.getDetailLogMatch();
    this.getWinning();
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
img {
  width: 80%;
}
</style>