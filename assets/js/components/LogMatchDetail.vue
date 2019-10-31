<template>
  <div class="container-fluid">
    <div class="mb-3">
      <a :href="baseUrl + 'entry/log_match'"><i class="fa fa-angle-double-left"></i> Back to Log Match</a>
    </div>
    <div class="row justify-content-center">
      <div class="col p-3 order-2 order-md-1">
        <img
          :src="baseUrl + '/assets/img/user_avatar.png'"
          class="rounded mx-auto d-block"
          alt="..."
        >
      </div>
      <div class="col-md-6 order-1 order-md-2">
        <div class="card card-default mb-md-3">
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
              <span>{{this.logMatchDetail.time}}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span>Status <i class="fa fa-angle-double-right"></i></span>
              <span
                class="badge"
                :class="this.logMatchDetail.play == 1? 'badge-success': 'badge-dark'"
              >{{this.logMatchDetail.play == 0? 'Not Play' : this.logMatchDetail.play == 1? 'Playing' : 'Finished' }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span>Winning by <i class="fa fa-angle-double-right"></i></span>
              <span>{{this.logMatchDetail.winning}}</span>
            </div>
            <hr>
            <div class="text-center">Referee<br>
              <span class="font-weight-bold text-primary">FOO BAR</span>
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
              :class="[logMatchDetail.winner==1? 'list-group-item-success' : '']"
            >
              <div class="d-flex justify-content-between align-items-center font-weight-bold text-uppercase">
                <h3 class="mb-0">{{this.logMatchDetail.player1_name || '...'}}</h3>
                <span
                  v-if="logMatchDetail.winner==1"
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
              :class="[logMatchDetail.winner==2? 'list-group-item-success' : '']"
            >
              <div class="d-flex justify-content-between align-items-center font-weight-bold text-uppercase">
                <h3 class="mb-0">{{this.logMatchDetail.player2_name || '...'}}</h3>
                <span
                  v-if="logMatchDetail.winner==2"
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
  </div>
</template>

<script>
export default {
  name: 'LogMatchDetail',
  props: {
    baseUrl: String
  },
  data() {
    return {
      logMatchDetail: {}
    };
  },

  computed: {
    logMatchId() {
      const url = window.location.href;
      const split = url.split('/');
      return split[split.length - 1];
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
    }
  },

  created() {
    this.getDetailLogMatch();
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