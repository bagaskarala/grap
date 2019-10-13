<template>
  <div>
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Winning List</span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              data-toggle="modal"
              data-target="#modal-winning"
              @click="modalState = 'add'"
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
                </div>
                <div>
                  <button
                    class="btn btn-sm btn-warning"
                    data-toggle="modal"
                    data-target="#modal-winning"
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
      id="modal-winning"
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
                <label for="description">description</label>
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
  name: 'WinningList',
  data() {
    return {
      winnings: [],
      form: {
        winning: null,
        description: null
      },
      modalState: null
    };
  },
  methods: {
    async getAllWinnings() {
      const winnings = await this.$axios.get('master/winning/get_all');
      this.winnings = winnings.data.data;
    },

    async addData() {
      const result = await this.$axios.post('master/winning/insert', {
        winning: this.form.winning,
        description: this.form.description
      });

      this.triggerAlert(result.data.status, 'Insert');
    },

    async updateData() {
      const result = await this.$axios.post(`master/winning/update/${this.form.id}`, {
        winning: this.form.winning,
        description: this.form.description
      });

      this.triggerAlert(result.data.status, 'Update');
    },

    async deleteData(item) {
      const result = await this.$axios.post('master/winning/delete', {
        id: item.id
      });

      this.triggerAlert(result.data.status, 'Delete');
    },

    loadData(item) {
      this.modalState = 'update';
      let { id, winning, description } = item;
      this.form.id = id;
      this.form.winning = winning;
      this.form.description = description;
    },

    triggerAlert(status, type = 'Action') {
      if (status) {
        alert(`Success ${type} Data`);
        location.reload();
      } else {
        alert(`Failed ${type} Data`);
      }
    }
  },

  created() {
    this.getAllWinnings();
  }
};
</script>
