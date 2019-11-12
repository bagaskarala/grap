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
                    <span>{{item.division}}</span>
                    <i
                      :class="generateGenderIcon(item.gender)"
                      :title="item.gender"
                    ></i>
                  </p>
                  <span
                    class="badge"
                    :class="[item.play == 1 ? 'badge-success':'badge-secondary']"
                  >
                    {{item.play == 1? 'Play' : 'Not Play'}}
                  </span>
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
    >
      <div
        v-if="errorValidation"
        class="alert alert-danger"
        v-html="errorValidation"
      ></div>

      <form method="post">
        <div class="form-group">
          <label for="division">Division Name</label>
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <input
                  type="checkbox"
                  v-model="customName"
                  title="Custom name division"
                >
              </div>
            </div>
            <input
              v-if="!customName"
              id="division"
              type="text"
              class="form-control"
              disabled
              :value="generateName"
            >
            <input
              v-else
              id="division"
              type="text"
              class="form-control"
              v-model="form.division"
            >
          </div>

        </div>
        <div class="form-group">
          <label for="min_weight">Min. Weight</label>
          <input
            id="min_weight"
            v-model.number="form.min_weight"
            type="number"
            class="form-control"
            placeholder="Enter minimal weight"
          >
        </div>
        <div class="form-group">
          <label for="max_weight">Max. Weight</label>
          <input
            id="max_weight"
            v-model.number="form.max_weight"
            type="number"
            class="form-control"
            placeholder="Enter maximum weght"
          >
        </div>
        <div class="form-group">
          <label for="gender">Gender</label>
          <select
            name="gender"
            id="gender"
            class="form-control"
            v-model="form.gender"
          >
            <option :value="null">Select Gender</option>
            <option
              v-for="item in genderOptions"
              :key="item.value"
              :value="item.value"
            >{{item.text}}</option>
          </select>
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
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              v-model="form.play"
              id="defaultCheck1"
            >
            <label
              class="form-check-label"
              for="defaultCheck1"
            >
              Play Status
            </label>
          </div>
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
      genderOptions: [
        { text: 'Male', value: 'male' },
        { text: 'Female', value: 'female' },
        { text: 'Male and Female', value: 'all' }
      ],
      form: {
        id: null,
        division: null,
        min_weight: null,
        max_weight: null,
        gender: null,
        system: null,
        description: null,
        play: null
      },
      modalState: null,
      errorValidation: null,
      customName: false
    };
  },

  computed: {
    generateName() {
      if (!this.form.min_weight || !this.form.max_weight) {
        return '--- Auto generate name by weight range ---';
      } else {
        return `Division ${this.form.min_weight} kg - ${this.form.max_weight} kg`;
      }
    },

    divisonName() {
      if (this.customName) {
        return this.form.division;
      } else {
        return this.generateName;
      }
    }
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
          division: this.divisonName,
          min_weight: this.form.min_weight,
          max_weight: this.form.max_weight,
          gender: this.form.gender,
          system: this.form.system,
          description: this.form.description,
          play: this.form.play ? 1 : 0
        });

        this.$noty.success('Success Insert Data');
        this.getAllDivisions();
        this.$bvModal.hide('modal-division');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
        this.$noty.error('Failed Insert Data');
      }
    },

    async updateData() {
      try {
        await this.$axios.post(`master/division/update/${this.form.id}`, {
          division: this.divisonName,
          min_weight: this.form.min_weight,
          max_weight: this.form.max_weight,
          gender: this.form.gender,
          system: this.form.system,
          description: this.form.description,
          play: this.form.play ? 1 : 0
        });

        this.$noty.success('Success Update Data');
        this.getAllDivisions();
        this.$bvModal.hide('modal-division');

      } catch (error) {
        console.log(error.response);
        this.errorValidation = error.response.data.message;
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

    generateGenderIcon(gender) {
      if (gender == 'male') {
        return 'fa fa-male text-primary';
      } else if (gender == 'female') {
        return 'fa fa-female text-danger';
      } else {
        return 'fa fa-genderless text-dark';
      }
    },

    addData() {
      this.resetData();
      this.$bvModal.show('modal-division');
      this.modalState = 'add';
    },

    loadData(item) {
      this.resetData();
      this.$bvModal.show('modal-division');
      this.modalState = 'update';
      // populate form
      let { id, division, min_weight, max_weight, gender, system, description, play } = item;
      this.form.id = id;
      this.form.division = division;
      this.form.min_weight = min_weight;
      this.form.max_weight = max_weight;
      this.form.gender = gender;
      this.form.system = system;
      this.form.description = description;
      this.form.play = play == 1 ? true : false;
      this.customName = true;
    },

    resetData() {
      this.errorValidation = null;
      this.form.division = null;
      this.form.min_weight = null;
      this.form.max_weight = null;
      this.form.gender = null;
      this.form.system = null;
      this.form.description = null;
      this.form.play = null;
      this.customName = false;
    }
  },

  created() {
    this.getAllDivisions();
  }
};
</script>
