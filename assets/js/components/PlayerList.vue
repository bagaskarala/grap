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

          <div class="mx-3 mt-3">
            <div class="input-group input-group-sm">
              <div class="input-group-prepend">
                <span class="input-group-text">Search Player</span>
              </div>
              <input
                v-model="searchKeyword"
                type="text"
                class="form-control"
                placeholder="Enter Keyword"
                @keyup.enter="searchData"
              >
              <div class="input-group-append">
                <button
                  type="button"
                  class="btn btn-sm btn-secondary"
                  @click="searchKeyword=''; searchData()"
                >
                  <i class="fa fa-times"></i>
                </button>
                <button
                  type="button"
                  class="btn btn-sm btn-primary"
                  @click.prevent="searchData"
                >
                  <i class="fa fa-search"></i>
                </button>
              </div>
            </div>
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
                    <span>{{item.name}} <span class="font-weight-normal">({{item.nickname}})</span></span>
                    <i
                      :class="[item.gender == 'male'? 'fa-male text-primary':'fa-female text-danger', 'fa']"
                      :title="item.gender"
                    ></i>
                  </p>
                  <p class="mb-0">{{item.club}} - {{item.country}}</p>
                  <p class="badge badge-secondary mb-0 small">{{item.weight}} kg</p>
                </div>
                <div>
                  <button
                    class="btn btn-sm btn-warning"
                    @click.prevent="loadAchievement(item)"
                  ><i class="fa fa-trophy fa-fw"></i></button>
                  <button
                    class="btn btn-sm btn-warning"
                    :class="[!item.left_photo || !item.right_photo || !item.front_photo ? 'text-danger' : 'asd'  ]"
                    @click.prevent="loadPhoto(item)"
                  ><i class="fa fa-file-image fa-fw"></i></button>
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
          <label for="club_id">Club</label>
          <select
            name="club_id"
            id="club_id"
            class="form-control"
            v-model.number="form.club_id"
          >
            <option :value="null">Select Club</option>
            <option
              v-for="item in clubs"
              :key="item.id"
              :value="item.id"
            >{{item.club}}</option>
          </select>
        </div>
        <div class="form-group">
          <label for="nickname">Nickname</label>
          <input
            id="nickname"
            v-model="form.nickname"
            type="text"
            class="form-control"
            placeholder="Enter Nickname"
            maxlength="10"
          >
        </div>
        <div class="form-group">
          <label for="gender">Gender</label>
          <select
            name="gender"
            id="gender"
            class="form-control"
            v-model="form.gender"
          >
            <option :value="null">Select Gender</option>
            <option
              v-for="item in genderOptions"
              :key="item.value"
              :value="item.value"
            >{{item.text}}</option>
          </select>
        </div>
        <div class="form-group">
          <label for="height">Height</label>
          <div class="input-group mb-3">
            <input
              id="height"
              v-model.number="form.height"
              type="number"
              class="form-control"
              placeholder="Enter height"
            >
            <div class="input-group-append">
              <span class="input-group-text">cm</span>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label for="weight">Weight</label>
          <div class="input-group mb-3">
            <input
              id="weight"
              v-model.number="form.weight"
              type="number"
              class="form-control"
              placeholder="Enter weight"
            >
            <div class="input-group-append">
              <span class="input-group-text">kg</span>
            </div>
          </div>
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

    <!-- modal player photo -->
    <b-modal
      id="modal-player-photo"
      hide-footer
      title="Player Photo"
    >
      <div
        v-if="errorValidation"
        class="alert alert-danger"
        v-html="errorValidation"
      ></div>
      <div class="form-group">
        <img
          v-if="form.left_photo && typeof form.left_photo == 'string'"
          :src="baseUrl+ 'assets/img/player/' + form.left_photo"
          class="img-thumbnail mb-2"
          style="max-width:30%"
        >
        <label>Left Photo *</label>
        <div class="input-group">
          <div class="custom-file">
            <input
              id="left-photo"
              ref="left_photo"
              type="file"
              class="custom-file-input"
              @change="handleFileChange('left_photo')"
            >
            <label
              class="custom-file-label"
              ref="left_photo_label"
              for="left-photo"
            >{{customFileLabelLeft}}</label>
          </div>
          <div class="input-group-append">
            <button
              class="btn btn-success"
              type="button"
              @click="uploadPhoto('left_photo')"
            >Upload</button>
          </div>
        </div>
      </div>
      <div class="form-group">
        <img
          v-if="form.right_photo && typeof form.right_photo == 'string'"
          :src="baseUrl+ 'assets/img/player/' + form.right_photo"
          class="img-thumbnail mb-2"
          style="max-width:30%"
        >
        <label>Right Photo *</label>
        <div class="input-group">
          <div class="custom-file">
            <input
              id="right-photo"
              ref="right_photo"
              type="file"
              class="custom-file-input"
              @change="handleFileChange('right_photo')"
            >
            <label
              class="custom-file-label"
              ref="left_photo_label"
              for="right-photo"
            >{{customFileLabelRight}}</label>
          </div>
          <div class="input-group-append">
            <button
              class="btn btn-success"
              type="button"
              @click="uploadPhoto('right_photo')"
            >Upload</button>
          </div>
        </div>
      </div>
      <div class="form-group">
        <img
          v-if="form.front_photo && typeof form.front_photo == 'string'"
          :src="baseUrl+ 'assets/img/player/' + form.front_photo"
          class="img-thumbnail mb-2"
          style="max-width:30%"
        >
        <label>Front Photo *</label>
        <div class="input-group">
          <div class="custom-file">
            <input
              id="front-photo"
              ref="front_photo"
              type="file"
              class="custom-file-input"
              @change="handleFileChange('front_photo')"
            >
            <label
              class="custom-file-label"
              ref="left_photo_label"
              for="front-photo"
            >{{customFileLabelFront}}</label>
          </div>
          <div class="input-group-append">
            <button
              class="btn btn-success"
              type="button"
              @click="uploadPhoto('front_photo')"
            >Upload</button>
          </div>
        </div>
      </div>
    </b-modal>

    <!-- modal achievement player -->
    <b-modal
      id="modal-achievement"
      hide-footer
      title="Player Achievement"
      size="xl"
    >
      <div
        v-if="errorValidation"
        class="alert alert-danger"
        v-html="errorValidation"
      ></div>

      <button
        class="btn btn-primary btn-sm mb-3"
        :disabled="formAchievement.length >=6"
        @click="addNewAchievement"
      >Add achievement</button>

      <b-tabs
        v-if="formAchievement.length != 0"
        v-model="tabIndex"
        content-class="mt-3"
      >
        <b-tab
          v-for="(item, index) in formAchievement"
          :key="index"
        >
          <template v-slot:title>
            <span>{{`Achievement ${item.category} #${index+1}`}}</span>
            <span v-if="item && !item.id">
              <i
                class="fa fa-circle text-danger"
                title="Not Saved Yet"
              ></i>
            </span>
          </template>

          <div
            v-if="countAchievement(item.category) > 3"
            class="alert alert-warning"
          >
            Max 3 achievement in {{item.category}} category
          </div>

          <form method="post">
            <div class="form-group">
              <div class="row">
                <div class="col">
                  <label for="division">Category</label>
                  <select
                    id="category"
                    class="form-control"
                    v-model.number="formAchievement[index].category"
                  >
                    <option
                      v-for="item in categoryOptions"
                      :key="item.value"
                      :value="item.value"
                    >{{item.text}}</option>
                  </select>
                </div>
                <div class="col">
                  <label for="division">Division</label>
                  <input
                    id="division"
                    v-model="formAchievement[index].division"
                    type="text"
                    autocomplete="off"
                    class="form-control"
                    placeholder="Enter division"
                    list="divisionList"
                    :disabled="countAchievement(item.category) > 3"
                  >
                  <datalist id="divisionList">
                    <option
                      v-for="division in divisionOptions"
                      :key="division.id"
                    >{{ division.division }}</option>
                  </datalist>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col">
                  <label for="tournament_name">Tournament Name</label>
                  <input
                    id="tournament_name"
                    v-model="formAchievement[index].tournament_name"
                    type="text"
                    class="form-control"
                    placeholder="Enter tournament name"
                    :disabled="countAchievement(item.category) > 3"
                  >
                </div>
                <div class="col">
                  <label for="tournament_name">Winner Position</label>
                  <select
                    name="winner_position"
                    id="winner_position"
                    class="form-control"
                    v-model.number="formAchievement[index].winner_position"
                    :disabled="countAchievement(item.category) > 3"
                  >
                    <option
                      :value="null"
                      disabled
                    >Choose Position</option>
                    <option
                      v-for="item in winnerPositionOptions"
                      :key="item.value"
                      :value="item.value"
                    >{{item.text}}</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col">
                  <label for="achievement_city">City</label>
                  <select
                    name="achievement_city"
                    id="achievement_city"
                    class="form-control"
                    v-model.number="formAchievement[index].achievement_city"
                    :disabled="countAchievement(item.category) > 3"
                  >
                    <option
                      :value="null"
                      disabled
                    >Select City</option>
                    <option
                      v-for="item in cities"
                      :key="item.value"
                      :value="item.value"
                    >{{item.text}}</option>
                  </select>
                </div>
                <div class="col">
                  <label for="achievement_city">Year</label>
                  <input
                    id="achievement_year"
                    v-model.number="formAchievement[index].achievement_year"
                    type="number"
                    class="form-control"
                    placeholder="Enter year"
                    max-length="3000"
                    min-length="2000"
                    :disabled="countAchievement(item.category) > 3"
                  >
                </div>
              </div>
            </div>
            <div class="d-flex justify-content-between">
              <div>
                <button
                  type="button"
                  class="btn btn-danger"
                  @click.prevent="deleteAchievement(formAchievement[index])"
                >Delete</button>
              </div>
              <div
                class="btn-group"
                role="group"
              >
                <button
                  type="button"
                  class="btn btn-secondary"
                  @click="resetAchievement"
                >Reset</button>
                <button
                  v-if="formAchievement[index].id"
                  class="btn btn-primary"
                  :disabled="countAchievement(item.category) > 3"
                  @click.prevent="updateAchievement(formAchievement[index])"
                >Update</button>
                <button
                  v-else
                  class="btn btn-primary"
                  :disabled="countAchievement(item.category) > 3"
                  @click.prevent="insertAchievement(formAchievement[index])"
                >Insert</button>
              </div>
            </div>
          </form>
        </b-tab>
      </b-tabs>
    </b-modal>
  </div>
</template>

<script>
import { getCities } from '../shared';
export default {
  name: 'PlayerList',
  props: {
    baseUrl: String
  },

  data() {
    return {
      players: [],
      countries: [],
      achievements: [],
      clubs: [],
      genderOptions: [
        { text: 'Male', value: 'male' },
        { text: 'Female', value: 'female' }
      ],
      form: {
        id: null,
        country_id: null,
        club_id: null,
        name: null,
        nickname: null,
        gender: null,
        height: null,
        weight: null,
        achievement: null,
        left_photo: null,
        right_photo: null,
        front_photo: null
      },
      formAchievement: [],
      modalState: null,
      errorValidation: null,
      searchKeyword: '',
      customFileLabelLeft: null,
      customFileLabelRight: null,
      customFileLabelFront: null,
      tabIndex: 0,
      divisionOptions: [],
      winnerPositionOptions: [
        {
          text: '1st winner',
          value: 1
        },
        {
          text: '2nd winner',
          value: 2
        },
        {
          text: '3rd winner',
          value: 3
        }
      ],
      categoryOptions: [
        {
          text: 'General',
          value: 'general'
        },
        {
          text: 'Grappling',
          value: 'grappling'
        }
      ]
    };
  },

  methods: {
    getCities,

    async getAllDivisions() {
      try {
        const divisions = await this.$axios.get('master/division/get_all');
        this.divisionOptions = divisions.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Fetch Division');
      }
    },
    async getPlayerAchivements(playerId) {
      try {
        const achievements = await this.$axios.get(`master/achievement/filter/${playerId}`);
        this.achievements = achievements.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Fetch Achievements');
      }
    },

    async getCountries() {
      try {
        const countries = await this.$axios.get('master/country/get_all');
        this.countries = countries.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Fetch Countries');
      }
    },

    async getClubs() {
      try {
        const clubs = await this.$axios.get('master/club/get_all');
        this.clubs = clubs.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Fetch Clubs');
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

    async searchData() {
      try {
        const result = await this.$axios.post(`master/player/search/${this.searchKeyword}`);
        this.players = result.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Search Data');
      }
    },

    async insertData() {
      try {
        await this.$axios.post('master/player/insert', {
          country_id: this.form.country_id,
          club_id: this.form.club_id,
          name: this.form.name,
          nickname: this.form.nickname,
          gender: this.form.gender,
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
          club_id: this.form.club_id,
          name: this.form.name,
          nickname: this.form.nickname,
          gender: this.form.gender,
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

    handleFileChange(photoType) {
      this.form[photoType] = this.$refs[photoType].files[0];

      // ganti label
      let filename = this.$refs[photoType].files[0].name;
      if (filename) {
        if (photoType == 'left_photo') {
          this.customFileLabelLeft = filename;
        } else if (photoType == 'right_photo') {
          this.customFileLabelRight = filename;
        } else if (photoType == 'front_photo') {
          this.customFileLabelFront = filename;
        }
      }
    },

    async uploadPhoto(photoType) {
      let formData = new FormData();
      formData.append(photoType, this.form[photoType]);

      // error jika tidak pilih file
      if (!this.form[photoType].name) {
        this.$noty.error('No file selected');
        return;
      }

      try {
        await this.$axios.post(`master/player/upload_photo/${this.form.id}/${photoType}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        this.$bvModal.hide('modal-player-photo');
        this.getAllPlayers();
        this.$noty.success('Success Upload Photo');
        this.$bvModal.hide('modal-player');
      } catch (error) {
        console.log('gatot');
        console.log(error);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Upload Photo');
      }
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
      Object.keys(this.form).forEach(key => {
        return this.form[key] = item[key];
      });
    },

    loadPhoto(item) {
      this.resetData();
      this.$bvModal.show('modal-player-photo');
      // populate form
      Object.keys(this.form).forEach(key => {
        return this.form[key] = item[key];
      });
    },

    async loadAchievement(item) {
      this.getAllDivisions();
      this.errorValidation = null;
      this.tabIndex = 0;
      this.form.id = item.id;
      this.$bvModal.show('modal-achievement');
      await this.getPlayerAchivements(item.id);
      this.formAchievement = JSON.parse(JSON.stringify(this.achievements));
    },

    addNewAchievement() {
      let notSaved = this.formAchievement.find(item => {
        return item.id == undefined;
      });
      console.log(notSaved);
      if (notSaved) {
        this.$noty.warning('Save your new achievement, before insert another achievement');
        return;
      }

      // auto select grappling, jika general sudah 3
      let categorySelected = 'general';
      if (this.countAchievement('general') == 3) {
        categorySelected = 'grappling';
      }

      this.formAchievement.push({
        achievement_city: null,
        achievement_year: null,
        category: categorySelected,
        division: null,
        tournament_name: null,
        winner_position: null
      });
      setTimeout(() => {
        this.tabIndex = this.formAchievement.length - 1;
      }, 0);
    },

    checkAchievementCategory(category, popArray = true) {
      this.errorValidation = null;
      let achievementCategory = this.formAchievement.filter(item => item.category === category);
      console.log(achievementCategory);
      if (achievementCategory.length > 3) {
        this.errorValidation = `Only 3 achivement can be registered per category. Change your oldest ${category} achievement to the new one`;

        // ubah category yang kelebihan, menjadi category lain
        this.formAchievement[this.tabIndex].category = this.formAchievement[this.tabIndex].category == 'general' ? 'grappling' : 'general';

        // cari item yang sudah tersimpan
        // cari tahun yang paling tua
        let arrYear = achievementCategory.filter(item => item.id).map(item => item.achievement_year);
        console.log(arrYear);
        let oldestYear = Math.min(...arrYear);

        // pindah ke oldest achievement
        this.tabIndex = achievementCategory.findIndex(x => x.achievement_year == oldestYear);

        // pop item baru ketika insert new achievement
        if (popArray) this.formAchievement.pop();
        return false;
      }
      return true;
    },

    async insertAchievement(ach) {
      // jika achievement per category melebihi batas
      if (!this.checkAchievementCategory(ach.category, true)) return;

      try {
        await this.$axios.post('master/achievement/insert', {
          tournament_name: ach.tournament_name,
          winner_position: ach.winner_position,
          achievement_city: ach.achievement_city,
          achievement_year: ach.achievement_year,
          division: ach.division,
          category: ach.category,
          player_id: this.form.id
        });
        this.formAchievement = [];
        this.loadAchievement(this.form);
        this.$noty.success('Success Insert Data');
      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Insert Data');
      }
    },

    async updateAchievement(ach) {
      // jika achievement per category melebihi batas
      if (!this.checkAchievementCategory(ach.category, false)) return;

      try {
        await this.$axios.post(`master/achievement/update/${ach.id}`, {
          tournament_name: ach.tournament_name,
          winner_position: ach.winner_position,
          achievement_city: ach.achievement_city,
          achievement_year: ach.achievement_year,
          division: ach.division,
          category: ach.category,
          player_id: this.form.id
        });
        this.$noty.success('Success Update Data');
      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Update Data');
      }
    },

    async deleteAchievement(item) {
      // hapus arraynya saja ketika belum tersimpan di db
      if (!item.id) {
        this.formAchievement.splice(this.formAchievement.findIndex(x => x.id == item.id), 1);
        return;
      }

      try {
        await this.$axios.post('master/achievement/delete', {
          id: item.id
        });
        this.$noty.success('Success Delete Data');
        this.formAchievement.splice(this.formAchievement.findIndex(x => x.id == item.id), 1);
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Delete Data');
      }
    },

    countAchievement(category) {
      // menghitung achievement per category
      return this.formAchievement.filter(item => {
        return item.category == category;
      }).length;
    },

    resetAchievement() {
      this.errorValidation = null;
      this.formAchievement[this.tabIndex].tournament_name = null;
      this.formAchievement[this.tabIndex].winner_position = null;
      this.formAchievement[this.tabIndex].achievement_city = null;
      this.formAchievement[this.tabIndex].achievement_year = null;
      this.formAchievement[this.tabIndex].division = null;
      this.formAchievement[this.tabIndex].category = null;
    },

    resetData() {
      this.errorValidation = null;

      Object.keys(this.form).forEach(key => {
        return this.form[key] = null;
      });

      this.customFileLabelLeft = 'Select File for Left Photo';
      this.customFileLabelRight = 'Select File for Right Photo';
      this.customFileLabelFront = 'Select File for Front Photo';
    }
  },

  created() {
    this.getAllPlayers();
    this.getCountries();
    this.getClubs();
    this.cities = this.getCities();
  },

  watch: {
    'form.weight'(val) {
      // jika negatif maka jadikan positif
      if (val < 0) this.form.weight = Math.abs(this.form.weight);
    },
    'form.height'(val) {
      // jika negatif maka jadikan positif
      if (val < 0) this.form.height = Math.abs(this.form.height);
    }
  }
};
</script>
