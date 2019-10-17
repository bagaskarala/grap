<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Division List</span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              @click.prevent="addData()"
            >
              Add division
            </button>
          </div>

          <div class="card-body">
            <div
              v-show="divisions.length == 0"
              class="my-3 text-center"
            >Empty Data</div>
            <div class="list-group">
              <div
                v-for="item in divisions"
                :key="item.id"
                class="list-group-item d-flex justify-content-between align-items-center"
              >
                <div>
                  <p class="font-weight-bold m-0">
                    {{item.division}}
                  </p>
                  <p class="small text-muted m-0">
                    {{item.system}}
                  </p>
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

    <!-- modal add division -->
    <b-modal
      id="modal-division"
      hide-footer
      :title="modalState == 'add'? 'Add Item' : 'Update Item'"
      @show="modalState = 'add'"
    >
      <form method="post">
        <div class="form-group">
          <label for="division">Division</label>
          <input
            id="division"
            v-model="form.division"
            type="text"
            class="form-control"
            placeholder="Enter division"
          >
        </div>
        <div class="form-group">
          <label for="system">System</label>
          <input
            id="system"
            v-model="form.system"
            type="text"
            class="form-control"
            placeholder="Enter system"
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
        <div class="form-group">
          <label for="play">Play</label>
          <input
            id="play"
            v-model="form.play"
            type="text"
            class="form-control"
            placeholder="Enter play"
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
  </div>
</template>

<script>
export default {
  name: 'DivisionList',
  data() {
    return {
      divisions: [],
      form: {
        id: null,
        division: null,
        system: null,
        description: null,
        play: null
      },
      modalState: null
    };
  },

  methods: {
    async getAllDivisions() {
      try {
        const divisions = await this.$axios.get('master/division/get_all');
        this.divisions = divisions.data.data;
      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Get Data');
      }
    },

    async insertData() {
      try {
        await this.$axios.post('master/division/insert', {
          division: this.form.division,
          system: this.form.system,
          description: this.form.description,
          play: this.form.play
        });

        this.$noty.success('Success Insert Data');
        this.getAllDivisions();
        this.$bvModal.hide('modal-division');

      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Insert Data');
      }
    },

    async updateData() {
      try {
        await this.$axios.post(`master/division/update/${this.form.id}`, {
          division: this.form.division,
          system: this.form.system,
          description: this.form.description,
          play: this.form.play
        });

        this.$noty.success('Success Update Data');
        this.getAllDivisions();
        this.$bvModal.hide('modal-division');

      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Update Data');
      }
    },

    async deleteData(item) {
      try {
        await this.$axios.post('master/division/delete', {
          id: item.id
        });

        this.$noty.success('Success Delete Data');
        this.getAllDivisions();
        this.$bvModal.hide('modal-division');

      } catch (error) {
        console.log(error.response);
        this.$noty.error('Failed Delete Data');
      }
    },

    confirmDelete(item) {
      this.$bvModal.msgBoxConfirm(`Please confirm that you want to delete ${item.division}`, {
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
      this.$bvModal.show('modal-division');
    },

    loadData(item) {
      this.$bvModal.show('modal-division');
      this.modalState = 'update';
      // populate form
      let { id, division, system, description, play } = item;
      this.form.id = id;
      this.form.division = division;
      this.form.system = system;
      this.form.description = description;
      this.form.play = play;
    },

    resetData() {
      this.form.division = null;
      this.form.system = null;
      this.form.description = null;
      this.form.play = null;
    }
  },

  created() {
    this.getAllDivisions();
  }
};
</script>
