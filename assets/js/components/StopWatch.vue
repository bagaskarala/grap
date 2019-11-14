<template>
  <div>
    <p class="h4 font-weight-bold text-center">
      {{ hours }} :
      {{ minutes | zeroPad }} :
      {{ seconds | zeroPad }} :
      {{ milliSeconds | zeroPad(3) }}
    </p>
    <button
      class="btn btn-success btn-sm"
      @click="startTimer"
      :disabled="isRunning"
    ><i class="fa fa-play"></i>&nbsp; START</button>
    <!-- <button
        class="ui button"
        @click="pushTime"
        :disabled="!isRunning"
      >LAP</button> -->
    <button
      class="btn btn-danger btn-sm"
      @click="stopTimer"
      :disabled="!isRunning"
    ><i class="fa fa-stop"></i>&nbsp; STOP</button>
    <button
      class="btn btn-secondary btn-sm"
      @click="clearAll"
    >CLEAR</button>
    <!-- <ul
        class="ui bulleted list"
        v-if="times.length"
      >
        <span
          class="item"
          v-for="item in times"
          :key="item"
        >
          {{ item.hours  }} :
          {{ item.minutes | zeroPad }} :
          {{ item.seconds | zeroPad }} :
          {{ item.milliSeconds | zeroPad(3) }}
        </span>
      </ul> -->
  </div>
  <!-- /.center aligned row -->
</template>

<script>
export default {
  data() {
    return {
      times: [],
      animateFrame: 0,
      nowTime: 0,
      diffTime: 0,
      startTime: 0,
      isRunning: false
    };
  },
  methods: {
    // set start time
    setSubtractStartTime: function (time) {
      time = typeof time !== 'undefined' ? time : 0;
      this.startTime = Math.floor(performance.now() - time);
    },
    // mulai timer
    startTimer: function () {
      // loop()内で this の値が変更されるので退避
      var vm = this;
      vm.setSubtractStartTime(vm.diffTime);
      // infinity loop
      (function loop() {
        vm.nowTime = Math.floor(performance.now());
        vm.diffTime = vm.nowTime - vm.startTime;
        vm.animateFrame = requestAnimationFrame(loop);
      }());
      vm.isRunning = true;
      this.$emit('start-timer');
    },
    // stop timer
    stopTimer: function (clear = true) {
      this.isRunning = false;
      cancelAnimationFrame(this.animateFrame);
      if (clear) {
        this.$emit('stop-timer', {
          hours: this.hours,
          minutes: this.minutes,
          seconds: this.seconds,
          milliSeconds: this.milliSeconds,
          elapsedTime: this.diffTime
        });
      }
    },
    // 計測中の時間を配列に追加
    // pushTime: function () {
    //   this.times.push({
    //     hours: this.hours,
    //     minutes: this.minutes,
    //     seconds: this.seconds,
    //     milliSeconds: this.milliSeconds
    //   });
    // },
    // inisialisasi
    clearAll: function () {
      this.startTime = 0;
      this.nowTime = 0;
      this.diffTime = 0;
      this.times = [];
      this.stopTimer(false);
      this.animateFrame = 0;
      this.$emit('clear-timer');
    }
  },
  computed: {
    // hitung jam
    hours: function () {
      return Math.floor(this.diffTime / 1000 / 60 / 60);
    },
    // Hitung jumlah menit (ketika mencapai 60 menit, kembali ke 0 menit)
    minutes: function () {
      return Math.floor(this.diffTime / 1000 / 60) % 60;
    },
    // Hitung detik (kembali ke 0 ketika 60 detik tercapai)
    seconds: function () {
      return Math.floor(this.diffTime / 1000) % 60;
    },
    // Hitung milidetik (kembali ke 0 milidetik ketika 1000 milidetik tercapai)
    milliSeconds: function () {
      return Math.floor(this.diffTime % 1000);
    }
  },
  filters: {
    // cetak 0 depan angka
    zeroPad: function (value, num) {
      num = typeof num !== 'undefined' ? num : 2;
      return value.toString().padStart(num, '0');
    }
  }
};
</script>

<style>
</style>