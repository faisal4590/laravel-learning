<template>
  <div class="container">
    <div class="row mt-5" v-if="$gate.isAdminOrIsAuthorFrontEnd()">
      <!-- only admin ar author ei div dekhte parbe. evabe front end e ACL control korte pari -->
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Users</h3>
            <div class="card-tools">
              <button class="btn btn-success" @click="addUserModal">
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
                  <tr v-for="userVariable in usersObj.data" :key="userVariable.id">
                    <td>{{userVariable.id}}</td>
                    <td>{{userVariable.name}}</td>
                    <td>{{userVariable.email}}</td>
                    <td>{{userVariable.type | upperCaseAnyText}}</td>
                    <!-- upperCaseAnyText hocce filter er name. filter ta app.js e globally define kora ace -->
                    <td>{{userVariable.created_at | parseCreatedAtDateForUser}}</td>

                    <td>
                      <a href="#" @click="updateUserModal(userVariable)">
                        <i class="fa fa-edit icon-indigo"></i>
                      </a>
                      /
                      <a href="#" @click="deleteUser(userVariable.id)">
                        <i class="fa fa-trash icon-red"></i>
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
            <!-- card footer -->
            <div class="card-footer">
              <!-- card-footer bootstrap er define kora 1ta class -->
              <pagination :data="usersObj" @pagination-change-page="getResults"></pagination>
              <!--
                  :data="usersObj" ei users ta astece script e je usersObj name e object ta define korci otar data
                  jokhn keu next page e click korbo tokhn pagination-change-page event ta execute hobe. ei event er
                  function hocce getResult.
              -->
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>

    <div class="row mt-5" v-if="!$gate.isAdminOrIsAuthorFrontEnd()">
      <!-- only admin ar author ei div dekhte parbe. evabe front end e ACL control korte pari -->
      <div class="col-md-12">
        <h2 class="text-center">Only admin and author is allowed to access this page</h2>
        <not-found></not-found>
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
            <h5 v-show="!editMode" class="modal-title" id="exampleModalLabel">Add new user</h5>
            <!-- if editMode==false, then Add new user korabo form e. -->
            <h5 v-show="editMode" class="modal-title" id="exampleModalLabel">Update user</h5>
            <!-- if editMode==true, then Update new user show korabo form e. -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <form @submit.prevent="editMode ? updateUser() : createUser()">
            <!-- Jodi edit mode true hoy(niche script e data te define korci) then edit modal show korabo, ow createUserModal show korabo -->
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
              <button v-show="!editMode" type="submit" class="btn btn-success">
                <!-- if editMode==false, then Create button show korabo form e. -->
                <i class="fas fa-check-circle fa-fw"></i>Create
              </button>
              <button v-show="editMode" type="submit" class="btn btn-primary">
                <!-- if editMode==true, then Update button show korabo form e. -->
                <i class="fas fa-check-circle fa-fw"></i>Update
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
      editMode: false,
      usersObj: {}, //ei object e users er data tule ene store korbo
      form: new Form({
        id: "",
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
      //jodi admin hoy taholei only user er info fetch kore anbe api diye
      if (this.$gate.isAdminOrIsAuthorFrontEnd()) {
        //using axios to send an HTTP request(get request) to our API(UserController.php) to fetch data from index() function
        axios.get("api/users").then(({ data }) => (this.usersObj = data));
        //user api theke sob data tule ene then data gulake userObj object e store korlam
        //get request dile automatic API/UserController.php er index() method ke call korbe. Okhan theke data return koracci
      }
    },
    deleteUser(id) {
      //delete korar age 1ta confirmation message dewa lagbe
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        //Delete button e confirm howar por press korle nicher code execute korbo

        //send http request to server.
        //API/UserController.php file e destroy() method use kore delete korbo data

        if (result.value) {
          //vform use korlam. delete() call korle ota UserController.php er destroy() method ke refer korbe.
          this.form
            .delete("api/users/" + id)
            .then(() => {
              Swal.fire("Deleted!", "Your file has been deleted.", "success");

              Fire.$emit("loadData"); //1ta custom event fire korlam
            })
            .catch(() => {
              Swal.fire("Failed", "There was something wrong", "warning");
            });
        }
      });
    },
    createUser() {
      //user create start howar shuru tei progress bar start hobe. so createUser() method call holei progress bar start korci
      this.$Progress.start();
      this.form
        .post("api/users")
        .then(() => {
          //jodi api use kore successfully data tule ante pare then nicher kaj gula korbo.

          /*
                jokhn api call kore user create kora hoye jabe, ami chai page refresh na kore table e new user er data chole ashbe.
                ejonno custom event use korbo vue js er.
                Fire.$emit("event_name") etar mane ami 1ta event fire korlam.
                now je event ta fire korlam ota keu listen korbe.
                Fire.$on("event_name",function()); etar mane oi event ta fire hole 1ta function execute koro.
          */
          Fire.$emit("loadData"); //1ta custom event fire korlam

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
        })
        .catch(() => {
          //jodi data tule ante na pare tahole eta execute hobe.
          this.$Progress.fail(); //jodi data tule ante fail kore tahole failed er progress bar dekhabo(red color)
        });
    },
    updateUser() {
      this.$Progress.start();
      this.form
        .put("api/users/" + this.form.id)
        .then(() => {
          $("#addNewUserModal").modal("hide");
          //successfully update data
          Swal.fire("Updated!", "Your data has been updated.", "success");
          this.$Progress.finish();
          Fire.$emit("loadData");
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },

    addUserModal() {
      this.editMode = false; //user add korte chai, edit korte na. tai false kore dicci
      this.form.reset(); //reset the form before opening so that no data is present in the form
      $("#addNewUserModal").modal("show");
    },
    updateUserModal(user) {
      this.editMode = true; //update button e click korle editmode true korteci
      this.form.reset(); //reset the form before opening so that no data is present in the form
      $("#addNewUserModal").modal("show");
      this.form.fill(user); //je user ke update korbo tar info gula modal e load kore nilam
    },
    getResults(page = 1) {
      //initially 1st page er data anbo.
      //method for fetching data for pagination after pagination-change-page event fires
      axios.get("api/users?page=" + page).then(response => {
        this.usersObj = response.data; //jokhn next page e click korbo tokhn next page er data usersObj e store korbe
      });
    }
  },

  created() {
    //ei component ta jokhn create hobe tokhn chai amar sob user load kore niye ashbe.
    this.loadUsers();

    //ami chai 3 second por por user er data gulake fetch kore anbe jate page bar bar reload kora na lage 1ta user create korar por
    //loadUser() method ta 1bar http request pathay. so etake 3 sec por por call korlei hbe.

    //setInterval(() => this.loadUsers(), 3000);//but eta good practice na. caz 3 sec por por i server e req ditei thakbe.
    //amar dorkar jokhn user create kora hobe tokhn only http req pathano.
    //sejonno custom event use korbo. upore emit diye 1ta custom event fire kora ace. now ota listen krbo.
    Fire.$on("loadData", () => {
      this.loadUsers(); //jokhn user create hobe tokhni loadUser() call kore new user er data fetch kore anbo.
      //always ana lagtecena.
    });
  }
};
</script>
