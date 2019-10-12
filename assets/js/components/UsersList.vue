<template>
  <div class="row justify-content-center">
    <div class="col">
      <div class="card card-default">
        <div class="card-header">Users</div>

        <div class="card-body">
          <div class="list-group">
            <div
              v-for="user in users"
              :key="user.id"
              class="list-group-item"
            >
              <div>
                ID: {{user.id}}
              </div>
              <div>
                Nama: {{user.name}}
              </div>
              <div>
                Email: {{user.email}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UsersList',
  data() {
    return {
      users: []
    };
  },
  methods: {
    async getAllUsers() {
      const users = await this.$axios.get('/admin/api_get_all_users');
      this.users = users.data.message;
    }
  },

  mounted() {
    this.getAllUsers();
  }
};
</script>
