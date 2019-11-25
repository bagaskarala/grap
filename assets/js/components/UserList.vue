<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header">User List</div>

          <div class="card-body">
            <div
              v-show="users.length == 0"
              class="my-3 text-center"
            >Empty Data</div>

            <b-table
              v-if="users.length != 0"
              striped
              hover
              responsive
              :items="users"
              :fields="fieldUser"
            >
              <template v-slot:cell(is_active)="data">
                <span
                  v-if="data.item.is_active"
                  class="badge badge-success"
                >
                  <i class="fa fa-check"></i>
                </span>
                <span
                  v-else
                  class="badge badge-secondary"
                >
                  <i class="fa fa-times"></i>
                </span>
              </template>

              <template v-slot:cell(action)="data">
                <div class="min-width-10">
                  <button
                    class="btn btn-sm btn-warning"
                    @click.prevent="loadData(data.item)"
                  ><i class="fa fa-edit fa-fw"></i></button>
                  <button
                    class="btn btn-sm btn-danger"
                    @click.prevent="confirmDelete(data.item)"
                  ><i class="fa fa-trash fa-fw"></i></button>
                </div>
              </template>
            </b-table>
          </div>
        </div>
      </div>
    </div>

    <!-- modal add winning -->
    <b-modal
      id="modal-user"
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
          <label for="email">Email</label>
          <input
            id="email"
            v-model="form.email"
            type="text"
            class="form-control"
            placeholder="Enter email"
          >
        </div>

        <div class="form-group">
          <label for="role_id">Role</label>
          <select
            name="role_id"
            id="role_id"
            class="form-control"
            v-model.number="form.role_id"
          >
            <option
              :value="null"
              disabled
            >Select Role</option>
            <option
              v-for="item in roles"
              :key="item.id"
              :value="item.id"
            >{{item.role}}</option>
          </select>
        </div>

        <div class="form-group">
          <input
            id="is_active"
            type="checkbox"
            v-model="form.is_active"
            :true-value=1
            :false-value=0
          >
          <label
            for="is_active"
            class="form-check-label"
          >Active?</label>
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
  name: 'UserList',
  data() {
    return {
      users: [],
      form: {
        name: null,
        email: null,
        role_id: null,
        is_active: null
      },
      modalState: null,
      errorValidation: null,
      roles: [],
      fieldUser: [
        {
          key: 'name'
        },
        {
          key: 'email'
        },
        {
          key: 'role'
        },
        {
          key: 'is_active',
          label: 'Active'
        },
        {
          key: 'action'
        }
      ]
    };
  },
  methods: {
    async getAllUsers() {
      try {
        const users = await this.$axios.get('admin/user/get_all_users');
        this.users = users.data.data;
      } catch (error) {
        this.$noty.error('Failed Get Data');
      }
    },

    async getAllRoles() {
      try {
        const roles = await this.$axios.get('admin/role/get_all');
        this.roles = roles.data.data;
      } catch (error) {
        this.$noty.error('Failed Get Data');
      }
    },

    async updateData() {
      try {
        await this.$axios.post(`admin/user/update/${this.form.id}`, {
          name: this.form.name,
          email: this.form.email,
          role_id: this.form.role_id,
          is_active: this.form.is_active
        });

        this.$noty.success('Success Update Data');
        this.getAllUsers();
        this.$bvModal.hide('modal-user');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Update Data');
      }
    },

    async deleteData(item) {
      console.log(item.id);
      try {
        await this.$axios.post('admin/user/delete_user', {
          id: item.id
        });

        this.$noty.success('Success Delete Data');
        this.getAllUsers();
      } catch (error) {
        this.$noty.error('Failed Delete Data');
        console.log(error.response);
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

    loadData(item) {
      this.resetData();
      this.getAllRoles();
      this.$bvModal.show('modal-user');
      this.modalState = 'update';
      // populate form
      let { id, name, email, role_id, is_active } = item;
      this.form.id = id;
      this.form.name = name;
      this.form.email = email;
      this.form.role_id = role_id;
      this.form.is_active = is_active;
    },

    resetData() {
      this.errorValidation = null;
      this.form.name = null;
      this.form.email = null;
      this.form.role_id = null;
      this.form.is_active = null;
    }
  },

  mounted() {
    this.getAllUsers();
  }
};
</script>
