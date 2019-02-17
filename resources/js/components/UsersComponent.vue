<template>
  <div class="container">
    <div class="row mt-5">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Users</h3>
            <div class="card-tools">
              <button class="btn btn-success" data-toggle="modal" data-target="#addNewUserModal">
                <i class="fas fa-user-plus"></i>
                Add new
              </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table class="table table-hover">
                <tbody>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Registed at</th>
                  </tr>
                  <tr v-for="userVariable in usersObj" :key="userVariable.id">
                    <td>{{userVariable.id}}</td>
                    <td>{{userVariable.name}}</td>
                    <td>{{userVariable.email}}</td>
                    <td>{{userVariable.type | upperCaseAnyText}}</td>
                    <!-- upperCaseAnyText hocce filter er name. filter ta app.js e globally define kora ace -->
                    <td>{{userVariable.created_at | parseCreatedAtDateForUser}}</td>

                    <td>
                      <a href="#">
                        <i class="fa fa-edit icon-indigo"></i>
                      </a>
                      /
                      <a href="#">
                        <i class="fa fa-trash icon-red"></i>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="addNewUserModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add new user</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <form @submit.prevent="createUser">
            <!-- form submit korle page jate reload na hoy ejonno prevent use korlam
            createUser() method ta niche script e define kora ace-->
            <div class="modal-body">
              <div class="form-group">
                <!-- niche script form e je field dici(name) seta diye v-model bind korlam -->
                <input
                  v-model="form.name"
                  type="text"
                  placeholder="name"
                  name="name"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('name') }"
                >
                <has-error :form="form" field="name"></has-error>
              </div>

              <div class="form-group">
                <!-- niche script form e je field dici(name) seta diye v-model bind korlam -->
                <input
                  v-model="form.email"
                  type="email"
                  placeholder="(ex: faisal@gmail.com)"
                  name="email"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('email') }"
                >
                <has-error :form="form" field="email"></has-error>
              </div>

              <div class="form-group">
                <!-- niche script form e je field dici(name) seta diye v-model bind korlam -->
                <input
                  v-model="form.password"
                  type="password"
                  placeholder="*****"
                  name="password"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('password') }"
                >
                <has-error :form="form" field="password"></has-error>
              </div>

              <div class="form-group">
                <!-- niche script form e je field dici(name) seta diye v-model bind korlam -->
                <select
                  v-model="form.type"
                  type="text"
                  name="type"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('type') }"
                >
                  <option value>Select user role</option>
                  <option value="admin">Admin</option>
                  <option value="user">Standard user</option>
                  <option value="author">Author</option>
                </select>
                <has-error :form="form" field="type"></has-error>
              </div>

              <div class="form-group">
                <!-- niche script form e je field dici(name) seta diye v-model bind korlam -->
                <textarea
                  v-model="form.bio"
                  type="text"
                  placeholder="bio"
                  name="bio"
                  class="form-control"
                  :class="{ 'is-invalid': form.errors.has('bio') }"
                ></textarea>
                <has-error :form="form" field="bio"></has-error>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">
                <i class="fas fa-times-circle fa-fw"></i>Close
              </button>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-check-circle fa-fw"></i>Create
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      usersObj: {}, //ei object e users er data tule ene store korbo
      form: new Form({
        name: "",
        email: "",
        password: "",
        type: "",
        bio: "",
        photo: ""
      })
    };
  },
  methods: {
    loadUsers() {
      //using axios to send an HTTP request(get request) to our API(UserController.php) to fetch data from index() function
      axios.get("api/users").then(({ data }) => (this.usersObj = data.data));
      //user api theke sob data tule ene then data gulake userObj object e store korlam
      //get request dile automatic API/UserController.php er index() method ke call korbe. Okhan theke data return koracci
    },
    createUser() {
      //user create start howar shuru tei progress bar start hobe. so createUser() method call holei progress bar start korci
      this.$Progress.start();
      this.form.post("api/users");
      //data api diye successfully insert kora hoye gele 1ta toaster diye success msg dekhabo
      //toaster ta app.js e globally define kora ace. sejonno ei component e just call kore use korte parbo
      Toast.fire({
        type: "success",
        title: "User created successfully"
      });

      //api use kore data insert kora hoye gele progress bar stop hye jabe. so http req er por progress bar finish korci
      this.$Progress.finish();

      //progressbar finish howar por modal ta close kore dibo
      $("#addNewUserModal").modal("hide");
    }
  },

  created() {
    //ei component ta jokhn create hobe tokhn chai amar sob user load kore niye ashbe.
    this.loadUsers();
  }
};
</script>
