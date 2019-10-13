<template>
  <div>
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Division List</span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              data-toggle="modal"
              data-target="#modal-division"
              @click="modalState = 'add'"
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
                    System: {{item.system}}
                  </p>
                </div>
                <div>
                  <button
                    class="btn btn-sm btn-warning"
                    data-toggle="modal"
                    data-target="#modal-division"
                    @click="loadData(item)"
                  ><i class="fa fa-edit fa-fw"></i></button>
                  <button
                    class="btn btn-sm btn-danger"
                    @click="deleteData(item)"
                  ><i class="fa fa-trash fa-fw"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- modal add division -->
    <div
      class="modal fade"
      id="modal-division"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div
        class="modal-dialog"
        role="document"
      >
        <div class="modal-content">
          <div class="modal-header">
            <h5
              class="modal-title"
              id="exampleModalLabel"
            >{{modalState == 'add'? 'Add Item' : 'Update Item'}}</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post">
            <div class="modal-body">
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
                <label for="keterangan">Keterangan</label>
                <textarea
                  id="keterangan"
                  v-model="form.keterangan"
                  class="form-control"
                  cols="30"
                  rows="4"
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
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >Close</button>
              <button
                v-if="modalState=='add'"
                class="btn btn-primary"
                @click.prevent="addData()"
              >Add</button>
              <button
                v-else
                class="btn btn-primary"
                @click.prevent="updateData()"
              >Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
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
        keterangan: null,
        play: null
      },
      modalState: null
    };
  },
  methods: {
    async getAllDivisions() {
      const divisions = await this.$axios.get('division/get_all');
      this.divisions = divisions.data.data;
    },

    async addData() {
      const result = await this.$axios.post('division/insert', {
        division: this.form.division,
        system: this.form.system,
        keterangan: this.form.keterangan,
        play: this.form.play
      });

      this.triggerAlert(result.data.status, 'Insert');
    },

    async updateData() {
      const result = await this.$axios.post(`division/update/${this.form.id}`, {
        division: this.form.division,
        system: this.form.system,
        keterangan: this.form.keterangan,
        play: this.form.play
      });

      this.triggerAlert(result.data.status, 'Update');
    },

    async deleteData(item) {
      const result = await this.$axios.post('division/delete', {
        id: item.id
      });

      this.triggerAlert(result.data.status, 'Delete');
    },

    loadData(item) {
      this.modalState = 'update';
      let { id, division, system, keterangan, play } = item;
      this.form.id = id;
      this.form.division = division;
      this.form.system = system;
      this.form.keterangan = keterangan;
      this.form.play = play;
    },

    triggerAlert(status, type = 'Action') {
      if (status) {
        alert(`Success ${type} Data`);
        location.reload();
      } else {
        alert(`Failed ${type} Data`);
      }
    },

    resetData() {
      this.form = {
        division: null,
        system: null,
        keterangan: null,
        play: null
      };
    }
  },

  created() {
    this.getAllDivisions();
  }
};
</script>
