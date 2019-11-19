<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header">Users</div>

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
                  <!-- <button
                    class="btn btn-sm btn-warning"
                    @click.prevent="loadData(data.item)"
                  ><i class="fa fa-edit fa-fw"></i></button> -->
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
  </div>
</template>

<script>
export default {
  name: 'UserList',
  data() {
    return {
      users: [],
      fieldUser: [
        {
          'key': 'name'
        },
        {
          'key': 'email'
        },
        {
          'key': 'role'
        },
        {
          'key': 'is_active'
        },
        {
          'key': 'action'
        }
      ]
    };
  },
  methods: {
    async getAllUsers() {
      try {
        const users = await this.$axios.get('admin/dashboard/get_all_users');
        this.users = users.data.data;
      } catch (error) {
        this.$noty.error('Failed Get Data');
      }
    },

    async deleteData(item) {
      console.log(item.id);
      try {
        await this.$axios.post('admin/dashboard/delete_user', {
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
    }
  },

  mounted() {
    this.getAllUsers();
  }
};
</script>
