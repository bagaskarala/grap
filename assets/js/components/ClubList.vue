<template>
  <div>
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Club List</span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              data-toggle="modal"
              data-target="#modal-club"
              @click="modalState = 'add'"
            >
              Add club
            </button>
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
                </div>
                <div>
                  <button
                    class="btn btn-sm btn-warning"
                    data-toggle="modal"
                    data-target="#modal-club"
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
      id="modal-club"
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
                <label for="club">club</label>
                <input
                  id="club"
                  v-model="form.club"
                  type="text"
                  class="form-control"
                  placeholder="Enter club"
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
  name: 'ClubList',
  data() {
    return {
      clubs: [],
      form: {
        club: null,
        description: null
      },
      modalState: null
    };
  },
  methods: {
    async getAllClubs() {
      const clubs = await this.$axios.get('master/club/get_all');
      this.clubs = clubs.data.data;
    },

    async addData() {
      const result = await this.$axios.post('master/club/insert', {
        club: this.form.club,
        description: this.form.description
      });

      this.triggerAlert(result.data.status, 'Insert');
    },

    async updateData() {
      const result = await this.$axios.post(`master/club/update/${this.form.id}`, {
        club: this.form.club,
        description: this.form.description
      });

      this.triggerAlert(result.data.status, 'Update');
    },

    async deleteData(item) {
      const result = await this.$axios.post('master/club/delete', {
        id: item.id
      });

      this.triggerAlert(result.data.status, 'Delete');
    },

    loadData(item) {
      this.modalState = 'update';
      let { id, club, description } = item;
      this.form.id = id;
      this.form.club = club;
      this.form.description = description;
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
        club: null,
        description: null
      };
    }
  },

  created() {
    this.getAllClubs();
  }
};
</script>
