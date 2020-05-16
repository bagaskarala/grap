<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Club List <span class="badge badge-secondary">{{clubs.length}}</span></span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              @click.prevent="addData()"
            >
              Add club
            </button>
          </div>

          <div class="mx-3 mt-3">
            <div class="input-group input-group-sm">
              <div class="input-group-prepend">
                <span class="input-group-text">Search Club</span>
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
              v-show="clubs.length == 0"
              class="my-3 text-center"
            >Empty Data</div>
            <div class="list-group">
              <div
                v-for="item in clubs"
                :key="item.id"
                class="list-group-item d-flex justify-content-between align-items-center"
              >
                <div>
                  <p class="font-weight-bold m-0">
                    {{item.club}}
                  </p>
                  <span>{{item.alias}}</span>
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

    <!-- modal add club -->
    <b-modal
      id="modal-club"
      hide-footer
      :title="modalState == 'add'? 'Add Item' : 'Update Item'"
      @show="modalState = 'add'"
    >
      <div
        v-if="errorValidation"
        class="alert alert-danger"
        v-html="errorValidation"
      ></div>

      <form method="post">
        <div class="form-group">
          <label for="club">Club *</label>
          <input
            id="club"
            v-model="form.club"
            type="text"
            class="form-control"
            placeholder="Enter club"
          >
        </div>

        <div class="form-group">
          <label for="alias">Alias</label>
          <input
            id="alias"
            v-model="form.alias"
            type="text"
            class="form-control"
            placeholder="Enter alias"
            maxlength="3"
          >
        </div>
        <div class="form-group">
          <label for="description">Description</label>
          <textarea
            id="description"
            v-model="form.description"
            class="form-control"
            cols="30"
            rows="4"
            placeholder="Enter description"
          ></textarea>
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
  name: 'ClubList',
  data() {
    return {
      clubs: [],
      form: {
        club: null,
        alias: null,
        description: null
      },
      modalState: null,
      errorValidation: null,
      searchKeyword: ''
    };
  },
  methods: {
    async getAllClubs() {
      try {
        const clubs = await this.$axios.get('master/club/get_all');
        this.clubs = clubs.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Get Data');
      }
    },

    async searchData() {
      try {
        const result = await this.$axios.post(`master/club/search/${this.searchKeyword}`);
        this.clubs = result.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Search Data');
      }
    },

    async insertData() {
      try {
        await this.$axios.post('master/club/insert', {
          club: this.form.club,
          alias: this.form.alias,
          description: this.form.description
        });

        this.$noty.success('Success Insert Data');
        this.getAllClubs();
        this.$bvModal.hide('modal-club');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Insert Data');
      }
    },

    async updateData() {
      try {
        await this.$axios.post(`master/club/update/${this.form.id}`, {
          club: this.form.club,
          alias: this.form.alias,
          description: this.form.description
        });

        this.$noty.success('Success Update Data');
        this.getAllClubs();
        this.$bvModal.hide('modal-club');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Update Data');
      }
    },

    async deleteData(item) {
      try {
        await this.$axios.post('master/club/delete', {
          id: item.id
        });

        this.$noty.success('Success Delete Data');
        this.getAllClubs();
        this.$bvModal.hide('modal-club');

      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Delete Data');
      }
    },

    confirmDelete(item) {
      this.$bvModal.msgBoxConfirm(`Please confirm that you want to delete ${item.club}`, {
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

    addData() {
      this.resetData();
      this.$bvModal.show('modal-club');
    },

    loadData(item) {
      this.resetData();
      this.$bvModal.show('modal-club');
      this.modalState = 'update';
      // populate form
      let { id, club, alias, description } = item;
      this.form.id = id;
      this.form.alias = alias;
      this.form.club = club;
      this.form.description = description;
    },

    resetData() {
      this.errorValidation = null;
      this.form.id = null;
      this.form.alias = null;
      this.form.club = null;
      this.form.description = null;
    }
  },

  created() {
    this.getAllClubs();
  }
};
</script>
