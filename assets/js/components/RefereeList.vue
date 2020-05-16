<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Referee List <span class="badge badge-secondary">{{referees.length}}</span></span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              @click.prevent="addData()"
            >
              Add referee
            </button>
          </div>

          <div class="card-body">
            <div
              v-show="referees.length == 0"
              class="my-3 text-center"
            >Empty Data</div>
            <div class="list-group">
              <div
                v-for="item in referees"
                :key="item.id"
                class="list-group-item d-flex justify-content-between align-items-center"
              >
                <div>
                  <p class="font-weight-bold m-0">
                    {{item.name}}
                  </p>
                  <p class="mb-0 small text-muted">{{item.description}}</p>
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

    <!-- modal add referee -->
    <b-modal
      id="modal-referee"
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
          <label for="name">Name *</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            class="form-control"
            placeholder="Enter referee name"
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
  name: 'RefereeList',
  data() {
    return {
      referees: [],
      form: {
        name: null,
        description: null
      },
      modalState: null,
      errorValidation: null
    };
  },

  methods: {
    async getAllReferees() {
      try {
        const referees = await this.$axios.get('master/referee/get_all');
        this.referees = referees.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Get Data');
      }

    },

    async insertData() {
      try {
        await this.$axios.post('master/referee/insert', {
          name: this.form.name,
          description: this.form.description
        });

        this.$noty.success('Success Insert Data');
        this.getAllReferees();
        this.$bvModal.hide('modal-referee');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Insert Data');
      }
    },

    async updateData() {
      try {
        await this.$axios.post(`master/referee/update/${this.form.id}`, {
          name: this.form.name,
          description: this.form.description
        });

        this.$noty.success('Success Update Data');
        this.getAllReferees();
        this.$bvModal.hide('modal-referee');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Update Data');
      }
    },

    async deleteData(item) {
      try {
        await this.$axios.post('master/referee/delete', {
          id: item.id
        });

        this.$noty.success('Success Delete Data');
        this.getAllReferees();
        this.$bvModal.hide('modal-referee');

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

    addData() {
      this.resetData();
      this.$bvModal.show('modal-referee');
      this.modalState = 'add';
    },

    loadData(item) {
      this.resetData();
      this.$bvModal.show('modal-referee');
      this.modalState = 'update';
      // populate form
      let { id, name, description } = item;
      this.form.id = id;
      this.form.name = name;
      this.form.description = description;
    },

    resetData() {
      this.errorValidation = null;
      this.form.name = null;
      this.form.description = null;
    }
  },

  created() {
    this.getAllReferees();
  }
};
</script>
