<template>
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col">
        <div class="card card-default">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Sub Menu List</span>
            <!-- Button trigger modal -->
            <button
              type="button"
              class="btn btn-sm btn-primary"
              @click="addData()"
            >
              Add sub menu
            </button>
          </div>

          <div class="card-body">
            <div
              v-show="subMenus.length == 0"
              class="my-3 text-center"
            >Empty Data</div>

            <b-table
              v-if="subMenus.length != 0"
              striped
              hover
              responsive
              :items="subMenus"
              :fields="fieldSubMenu"
            >
              <template v-slot:cell(icon)="data">
                <i :class="[data.item.icon]"></i>
              </template>

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

    <!-- modal add menu -->
    <b-modal
      id="modal-sub-menu"
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
          <label for="title">Sub Menu Title</label>
          <input
            id="title"
            v-model="form.title"
            type="text"
            class="form-control"
            placeholder="Enter title"
          >
        </div>
        <div class="form-group">
          <label for="menu_id">Menu</label>
          <select
            name="menu_id"
            id="menu_id"
            class="form-control"
            v-model.number="form.menu_id"
          >
            <option :value="null">Select Menu</option>
            <option
              v-for="item in menus"
              :key="item.id"
              :value="item.id"
            >{{item.menu}}</option>
          </select>
        </div>
        <div class="form-group">
          <label for="url">URL</label>
          <input
            id="url"
            v-model="form.url"
            type="text"
            class="form-control"
            placeholder="Enter url"
          >
        </div>
        <div class="form-group">
          <label for="icon">Icon</label>
          <input
            id="icon"
            v-model="form.icon"
            type="text"
            class="form-control"
            placeholder="Enter font-awesome icon (fa fa-fw fa-***)"
          >
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
  name: 'SubMenuList',
  data() {
    return {
      subMenus: [],
      menus: [],
      fieldSubMenu: [
        {
          'key': 'title'
        },
        {
          'key': 'menu'
        },
        {
          'key': 'url'
        },
        {
          'key': 'icon'
        },
        {
          'key': 'is_active',
          label: 'Active'
        },
        {
          'key': 'action'
        }
      ],
      form: {
        id: null,
        title: null,
        menu_id: null,
        icon: null,
        url: null,
        is_active: 1
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
        this.$noty.error('Failed Get Menus');
        console.log(error.response);
      }
    },

    async getAllSubMenus() {
      try {
        const subMenus = await this.$axios.get('sidebar/sub_menu/get_all');
        this.subMenus = subMenus.data.data;
      } catch (error) {
        this.$noty.error('Failed Get Data');
        console.log(error.response);
      }
    },

    async insertData() {
      try {
        await this.$axios.post('sidebar/sub_menu/insert', {
          title: this.form.title,
          menu_id: this.form.menu_id,
          icon: this.form.icon,
          url: this.form.url,
          is_active: this.form.is_active
        });
        this.$noty.success('Success Insert Data');
        this.getAllSubMenus();
        this.$bvModal.hide('modal-sub-menu');
      } catch (error) {
        this.$noty.error('Failed Insert Data');
        this.errorValidation = error.response.data.message;
        console.log(error.response);
      }
    },

    async updateData() {
      try {
        await this.$axios.post(`sidebar/sub_menu/update/${this.form.id}`, {
          title: this.form.title,
          menu_id: this.form.menu_id,
          icon: this.form.icon,
          url: this.form.url,
          is_active: this.form.is_active
        });

        this.$noty.success('Success Update Data');
        this.getAllSubMenus();
        this.$bvModal.hide('modal-sub-menu');
      } catch (error) {
        this.$noty.error('Failed Update Data');
        this.errorValidation = error.response.data.message;
        console.log(error.response);
      }
    },

    async deleteData(item) {
      try {
        await this.$axios.post('sidebar/sub_menu/delete', {
          id: item.id
        });

        this.$noty.success('Success Delete Data');
        this.getAllSubMenus();
      } catch (error) {
        this.$noty.error('Failed Delete Data');
        console.log(error.response);
      }
    },

    confirmDelete(item) {
      this.$bvModal.msgBoxConfirm(`Please confirm that you want to delete ${item.title}`, {
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
      this.$bvModal.show('modal-sub-menu');
      this.modalState = 'add';
    },

    loadData(item) {
      this.resetData();
      this.$bvModal.show('modal-sub-menu');
      this.modalState = 'update';
      let { id, title, menu_id, icon, url, is_active } = item;
      this.form.id = id;
      this.form.title = title;
      this.form.menu_id = menu_id;
      this.form.icon = icon;
      this.form.url = url;
      this.form.is_active = is_active;
    },

    resetData() {
      this.errorValidation = null;
      this.form.title = null;
      this.form.menu_id = null;
      this.form.icon = null;
      this.form.url = null;
      this.form.is_active = 1;
    }
  },

  created() {
    this.getAllSubMenus();
    this.getAllMenus();
  }
};
</script>
