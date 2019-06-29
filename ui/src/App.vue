<template>
  <div id="app"
       class="container-fluid">
    <div class="row toolbar">
      <div class="col-sm " style="padding: 1rem 1rem; text-align: left;">
        <img src="@/assets/icon.png" class="img-fluid logo-image" alt="Responsive image">
        <span class="text-right logo-text">Dəyişiklik səndən başlayır!</span>
      </div>
      <div class="col-sm" style="text-align: right; padding: 1rem 1rem">
        <div class="btn-group" role="group" aria-label="Basic example">
          <div class="btn-group btn-group-lg" role="group" aria-label="...">
            <button type="button"
                    @click="$router.push({ name: 'Login' })"
                    class="btn btn-primary">Sign In</button>
            <button type="button"
                    @click="$router.push({ name: 'Registration' })"
                    class="btn btn-primary">Sign Up</button>
          </div>
        </div>
      </div>
    </div>
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'App',
  beforeMount () {
    let token = this.$cookie.get('token')
    this.$store.commit('setToken', token)
  }
}
</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
.toolbar{
  background-color: #00008B;
}

.logo-text{
  font-weight: bold;
  color: #fff;
  font-size: 1.3rem;
}

.logo-image{
  height: 60px;
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
