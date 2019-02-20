<template>
  <div class="container" >
    <div class="row justify-content-center" v-if="$gate.isAdminFrontEnd()">
      <div class="col-md-12">
        <passport-clients></passport-clients>
        <passport-authorized-clients></passport-authorized-clients>
        <passport-personal-access-tokens></passport-personal-access-tokens>
      </div>
    </div>

    <div class="row justify-content-center mt-4" v-if="!$gate.isAdminFrontEnd()">
      <div class="col-md-12">
        <h2 class="text-center">Only admin is allowed to access this page</h2>
        <not-found></not-found>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  }
};
</script>
