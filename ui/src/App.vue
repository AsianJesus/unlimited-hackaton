<template>
  <div id="app"
       class="container-fluid">
    <div class="row">
      <div class="col-12 toolbar">
        <b-button-group>
          <b-button @click="$router.push({ name: 'Login' })">Sign in</b-button>
          <b-button @click="$router.push({ name: 'Registration' })">Sign up</b-button>
        </b-button-group>
      </div>
    </div>
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
.toolbar{
  text-align: right
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
