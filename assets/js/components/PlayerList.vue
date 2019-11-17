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
        <div class="form-group">
          <img
            v-if="form.left_photo && typeof form.left_photo == 'string'"
            :src="baseUrl+ 'assets/img/player/' + form.left_photo"
            class="img-thumbnail mb-2"
            style="max-width:30%"
          >
          <label>Left Photo</label>
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
          <label>Right Photo</label>
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
          <label>Front Photo</label>
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
  props: {
    baseUrl: String
  },

  data() {
    return {
      players: [],
      countries: [],
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
        img: null,
        height: null,
        weight: null,
        achievement: null,
        left_photo: null,
        right_photo: null,
        front_photo: null
      },
      modalState: null,
      errorValidation: null,
      searchKeyword: '',
      customFileLabelLeft: null,
      customFileLabelRight: null,
      customFileLabelFront: null
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
          club_id: this.form.club_id,
          name: this.form.name,
          nickname: this.form.nickname,
          gender: this.form.gender,
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
        const a = await this.$axios.post(`master/player/upload_photo/${this.form.id}/${photoType}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });
        console.log(a.data);
        this.getAllPlayers();
        this.$noty.success('Success Upload Photo');
        this.$bvModal.hide('modal-player');
      } catch (error) {
        console.log(error.response);
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

    resetData() {
      this.errorValidation = null;

      Object.keys(this.form).forEach(key => {
        return this.form[key] = null;
      });

      this.customFileLabelLeft = 'Select File';
      this.customFileLabelRight = 'Select File';
      this.customFileLabelFront = 'Select File';
    }
  },

  created() {
    this.getAllPlayers();
    this.getCountries();
    this.getClubs();
  }
};
</script>
