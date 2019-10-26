<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Winning List</span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              @click.prevent="addData()"
            >
              Add winning
            </button>
          </div>

          <div class="card-body">
            <div
              v-show="winnings.length == 0"
              class="my-3 text-center"
            >Empty Data</div>
            <div class="list-group">
              <div
                v-for="item in winnings"
                :key="item.id"
                class="list-group-item d-flex justify-content-between align-items-center"
              >
                <div>
                  <p class="font-weight-bold m-0">
                    {{item.winning}}
                  </p>
                  <span>{{item.description}}</span>
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

    <!-- modal add winning -->
    <b-modal
      id="modal-winning"
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
          <label for="winning">Winning by</label>
          <input
            id="winning"
            v-model="form.winning"
            type="text"
            class="form-control"
            placeholder="Enter winning"
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
  name: 'WinningList',
  data() {
    return {
      winnings: [],
      form: {
        winning: null,
        description: null
      },
      modalState: null,
      errorValidation: null
    };
  },
  methods: {
    async getAllWinnings() {
      try {
        const winnings = await this.$axios.get('master/winning/get_all');
        this.winnings = winnings.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Get Data');
      }

    },

    async insertData() {
      try {
        await this.$axios.post('master/winning/insert', {
          winning: this.form.winning,
          description: this.form.description
        });

        this.$noty.success('Success Insert Data');
        this.getAllWinnings();
        this.$bvModal.hide('modal-winning');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Insert Data');
      }
    },

    async updateData() {
      try {
        await this.$axios.post(`master/winning/update/${this.form.id}`, {
          winning: this.form.winning,
          description: this.form.description
        });

        this.$noty.success('Success Update Data');
        this.getAllWinnings();
        this.$bvModal.hide('modal-winning');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Update Data');
      }
    },

    async deleteData(item) {
      try {
        await this.$axios.post('master/winning/delete', {
          id: item.id
        });

        this.$noty.success('Success Delete Data');
        this.getAllWinnings();
        this.$bvModal.hide('modal-winning');

      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Delete Data');
      }
    },

    confirmDelete(item) {
      this.$bvModal.msgBoxConfirm(`Please confirm that you want to delete ${item.winning}`, {
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
      this.$bvModal.show('modal-winning');
      this.modalState = 'add';
    },

    loadData(item) {
      this.resetData();
      this.$bvModal.show('modal-winning');
      this.modalState = 'update';
      // populate form
      let { id, winning, description } = item;
      this.form.id = id;
      this.form.winning = winning;
      this.form.description = description;
    },

    resetData() {
      this.errorValidation = null;
      this.form.winning = null;
      this.form.description = null;
    }
  },

  created() {
    this.getAllWinnings();
  }
};
</script>
