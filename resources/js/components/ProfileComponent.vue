<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12 mt-3">
        <!-- Widget: user widget style 1 -->
        <div class="card card-widget widget-user">
          <!-- Add the bg color to the header using any of the bg-* classes -->
          <div
            class="widget-user-header text-white"
            style="background: url('./img/bg.jpg') center center; height:250px"
          >
            <h3 class="widget-user-username">Elizabeth Pierce</h3>
            <h5 class="widget-user-desc">Web Designer</h5>
          </div>
          <div class="widget-user-image">
            <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar">
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-sm-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">3,200</h5>
                  <span class="description-text">SALES</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-4 border-right">
                <div class="description-block">
                  <h5 class="description-header">13,000</h5>
                  <span class="description-text">FOLLOWERS</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
              <div class="col-sm-4">
                <div class="description-block">
                  <h5 class="description-header">35</h5>
                  <span class="description-text">PRODUCTS</span>
                </div>
                <!-- /.description-block -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
        </div>
        <!-- /.widget-user ends -->
        <!-- Settings and activity tab starts -->
        <div class="card">
          <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item">
                <a class="nav-link active show" href="#activity" data-toggle="tab">Activity</a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="#settings" data-toggle="tab">Settings</a>
              </li>
            </ul>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="tab-content">
              <div class="tab-pane active show" id="activity">
                <div class="card">
                  <div class="card-header">Activity</div>
                  <div class="card-body">I'm an Activity tab.</div>
                </div>
              </div>

              <div class="tab-pane" id="settings">
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input
                        v-model="form.name"
                        type="email"
                        class="form-control"
                        id="inputName"
                        placeholder="Name"
                        :class="{ 'is-invalid': form.errors.has('name') }"
                      >
                      <has-error :form="form" field="name"></has-error>
                      <!-- v-model diye 2 way data binding krci. amar component e form er moddhe name, email shob field ace,
                      oikhane ja data segula ei input field e chole ashar jonno 2 way data bind korlam v-model diye-->
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input
                        type="email"
                        v-model="form.email"
                        class="form-control"
                        id="inputEmail"
                        placeholder="Email"
                        :class="{ 'is-invalid': form.errors.has('email') }"
                      >

                      <has-error :form="form" field="email"></has-error>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                    <div class="col-sm-10">
                      <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Profile picture</label>

                    <div class="col-sm-10">
                      <input type="file" @change="updateProfileImage" id="inputProfilePicture">
                    </div>
                  </div>

                  <div class="form-group">
                    <label
                      for="inputExperience"
                      class="col-sm-12 control-label"
                    >Password(Leave empty if not changing)</label>

                    <div class="col-sm-10">
                      <input
                        type="password"
                        v-model="form.password"
                        class="form-control"
                        id="inputPassword"
                        placeholder="*****"
                        :class="{ 'is-invalid': form.errors.has('password') }"
                      >

                      <has-error :form="form" field="password"></has-error>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox"> I agree to the
                          <a href="#">terms and conditions</a>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button
                        type="submit"
                        @click.prevent="updateProfile"
                        class="btn btn-danger"
                      >Update profile</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.nav-tabs-custom -->
        <!-- Settings and activity tab ends -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      //amar ei component er 1ta form thakbe jeta diye data tule anbo. tai data er moddhe initialize korlam
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
    getProfilePhoto() {
      let photo = "";
      //instantly update photo without refreshing browser
      if (this.form.photo.length > 100) {
        //user jodi new photo upload kore then base64 e convert kore ota. so length onek boro..
        photo = this.form.photo;
      } else {
        //jodi user new photo chose na kore tahole database theke previous image er name ta pacci.
        photo = "img/profile/" + this.form.photo;
      }
      return photo;
    },
    updateProfile() {
      this.$Progress.start();
      this.form
        .put("api/profile/")
        .then(() => {
          this.$Progress.finish();
        })
        .catch(() => {
          this.$Progress.fail();
        });
    },
    updateProfileImage(element) {
      //user jokhn picture chose korbe tokhn ei function fire hobe

      //1st kaj hocce picture ta grab kora
      let file = element.target.files[0];
      //console.log("The picture info : ", file); //uplodaed file er sob option dekhte parbo
      let reader = new FileReader();
      if (file["size"] <= 5485760) {
        //file size 5MB prjonto accept korbo
        reader.onloadend = file => {
          //console.log("RESULT", reader.result);
          this.form.photo = reader.result;
        };
        reader.readAsDataURL(file);
      } else {
        Swal.fire("Oops..", "Maximum file size allowed is 10MB", "error");
      }
    }
  },

  created() {
    //now jokhn ei component ta created hobe, tokhn api call kore form e data tule anbo. form ta upore initialize korci
    axios.get("api/profile").then(({ data }) => this.form.fill(data));
  }
};
</script>
