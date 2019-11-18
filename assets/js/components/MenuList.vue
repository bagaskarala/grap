<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Menu List</span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              @click="addData()"
            >
              Add menu
            </button>
          </div>

          <div class="card-body">
            <div
              v-show="menus.length == 0"
              class="my-3 text-center"
            >Empty Data</div>
            <div class="list-group">
              <div
                v-for="item in menus"
                :key="item.id"
                class="list-group-item d-flex justify-content-between align-items-center"
              >
                <div>
                  <p class="font-weight-bold m-0">
                    {{item.menu}}
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

    <!-- modal add menu -->
    <b-modal
      id="modal-menu"
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
          <label for="menu">Menu Category</label>
          <input
            id="menu"
            v-model="form.menu"
            type="text"
            class="form-control"
            placeholder="Enter menu category name"
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
  name: 'MenuList',
  data() {
    return {
      menus: [],
      form: {
        menu: null
      },
      modalState: null,
      errorValidation: null
    };
  },
  methods: {
    async getAllMenus() {
      try {
        const menus = await this.$axios.get('sidebar/menu/get_all');
        this.menus = menus.data.data;
      } catch (error) {
        this.$noty.error('Failed Get Data');
      }
    },

    async insertData() {
      try {
        await this.$axios.post('sidebar/menu/insert', {
          menu: this.form.menu
        });
        this.$noty.success('Success Insert Data');
        this.getAllMenus();
        this.$bvModal.hide('modal-menu');
      } catch (error) {
        this.$noty.error('Failed Insert Data');
        this.errorValidation = error.response.data.message;
        console.log(error.response);
      }
    },

    async updateData() {
      try {
        await this.$axios.post(`sidebar/menu/update/${this.form.id}`, {
          menu: this.form.menu
        });

        this.$noty.success('Success Update Data');
        this.getAllMenus();
        this.$bvModal.hide('modal-menu');
      } catch (error) {
        this.$noty.error('Failed Update Data');
        this.errorValidation = error.response.data.message;
        console.log(error.response);
      }
    },

    async deleteData(item) {
      try {
        await this.$axios.post('sidebar/menu/delete', {
          id: item.id
        });

        this.$noty.success('Success Delete Data');
        this.getAllMenus();
        this.$bvModal.hide('modal-menu');
      } catch (error) {
        this.$noty.error('Failed Delete Data');
        console.log(error.response);
      }
    },

    confirmDelete(item) {
      this.$bvModal.msgBoxConfirm(`Please confirm that you want to delete ${item.menu}`, {
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
      this.$bvModal.show('modal-menu');
      this.modalState = 'add';
    },

    loadData(item) {
      this.resetData();
      this.$bvModal.show('modal-menu');
      this.modalState = 'update';
      let { id, menu } = item;
      this.form.id = id;
      this.form.menu = menu;
    },

    resetData() {
      this.errorValidation = null;
      this.form.menu = null;
    }
  },

  created() {
    this.getAllMenus();
  }
};
</script>
