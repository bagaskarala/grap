<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Role List</span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              @click="addData()"
            >
              Add role
            </button>
          </div>

          <div class="card-body">
            <div
              v-show="roles.length == 0"
              class="my-3 text-center"
            >Empty Data</div>
            <div class="list-group">
              <div
                v-for="item in roles"
                :key="item.id"
                class="list-group-item d-flex justify-content-between align-items-center"
              >
                <div>
                  <p class="font-weight-bold m-0">
                    {{item.role}}
                  </p>
                </div>
                <div>
                  <button
                    class="btn btn-sm btn-primary"
                    title="Edit role access"
                    @click.prevent="goToRoleAccess(item)"
                  ><i class="fa fa-lock fa-fw"></i></button>
                  <button
                    class="btn btn-sm btn-warning"
                    title="Edit data"
                    @click.prevent="loadData(item)"
                  ><i class="fa fa-edit fa-fw"></i></button>
                  <button
                    class="btn btn-sm btn-danger"
                    title="Delete data"
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
      id="modal-role"
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
          <label for="role">Role</label>
          <input
            id="role"
            v-model="form.role"
            type="text"
            class="form-control"
            placeholder="Enter role name"
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
  name: 'RoleList',
  data() {
    return {
      roles: [],
      form: {
        role: null
      },
      modalState: null,
      errorValidation: null
    };
  },
  methods: {
    async getAllRoles() {
      try {
        const roles = await this.$axios.get('admin/role/get_all');
        this.roles = roles.data.data;
      } catch (error) {
        this.$noty.error('Failed Get Data');
      }
    },

    async insertData() {
      try {
        await this.$axios.post('admin/role/insert', {
          role: this.form.role
        });
        this.$noty.success('Success Insert Data');
        this.getAllRoles();
        this.$bvModal.hide('modal-role');
      } catch (error) {
        this.$noty.error('Failed Insert Data');
        this.errorValidation = error.response.data.message;
        console.log(error.response);
      }
    },

    async updateData() {
      try {
        await this.$axios.post(`admin/role/update/${this.form.id}`, {
          role: this.form.role
        });

        this.$noty.success('Success Update Data');
        this.getAllRoles();
        this.$bvModal.hide('modal-role');
      } catch (error) {
        this.$noty.error('Failed Update Data');
        this.errorValidation = error.response.data.message;
        console.log(error.response);
      }
    },

    async deleteData(item) {
      try {
        await this.$axios.post('admin/role/delete', {
          id: item.id
        });
        this.$noty.success('Success Delete Data');
        this.getAllRoles();
        this.$bvModal.hide('modal-role');
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
      this.$bvModal.show('modal-role');
      this.modalState = 'add';
    },

    loadData(item) {
      this.resetData();
      this.$bvModal.show('modal-role');
      this.modalState = 'update';
      let { id, role } = item;
      this.form.id = id;
      this.form.role = role;
    },

    resetData() {
      this.errorValidation = null;
      this.form.role = null;
    },

    goToRoleAccess(item) {
      window.location.href = `role/role_access/${item.id}`;
    }
  },

  created() {
    this.getAllRoles();
  }
};
</script>
