<template>
  <div id="app"
       class="container-fluid">
    <div class="row toolbar">
      <router-link :to="{name: 'Home'}"
                   class="col-sm"
                   style="padding: 1rem 1rem; text-align: left;">
        <img src="@/assets/icon.png" class="img-fluid logo-image" alt="Responsive image">
        <span class="text-right logo-text">Təlimçim</span>
      </router-link>
      <div class="col-sm"
           style="text-align: right; padding: 1rem 1rem"
           v-if="!isLogged"
      >
        <div class="btn-group" role="group" aria-label="Basic example">
          <div class="btn-group btn-group-lg" role="group" aria-label="...">
            <button type="button"
                    @click="$router.push({ name: 'Login' })"
                    class="btn btn-light toolbar-button">Giriş</button>
            <button type="button"
                    @click="$router.push({ name: 'Registration' })"
                    class="btn btn-light toolbar-button">Qeydiyyat</button>
          </div>
        </div>
      </div>
      <div class="col-sm"
           style="text-align: right; padding: 1rem 1rem"
            v-else>
        <b-button varinat="success"
                  @click="$router.push({name: 'MyProfile'})">
          Şəxsi kabinet
          <font-awesome-icon :icon="iconUser"
                             :scale="1.5" />
        </b-button>
        <b-button variant="primary"
                  @click="logout" >
          Çıxış
          <font-awesome-icon :icon="iconLogout"
                             :scale="1.5"/>
        </b-button>
      </div>
    </div>
    <router-view id="main" />
    <div class="row footer">
      <span>
        Made by 4U Team
      </span>
    </div>
  </div>
</template>

<script>
import { faSignOutAlt, faUserCircle } from '@fortawesome/free-solid-svg-icons'
import axios from 'axios'
export default {
  name: 'App',
  beforeMount () {
    let token = this.$cookie.get('token')
    this.$store.commit('setToken', token)
    if (token) {
      this.loadUserInfo()
    }
  },
  computed: {
    isLogged () {
      return this.$store.state.isLogged
    },
    iconLogout: () => faSignOutAlt,
    iconUser: () => faUserCircle
  },
  methods: {
    logout () {
      this.$cookie.set('token', '')
      this.$store.commit('setToken', null)
      this.$router.push({ name: 'Home' })
    },
    loadUserInfo () {
      axios.get('/user').then(({ data }) => {
        this.$store.commit('setUserInfo', data)
      }).catch(() => {
        this.$cookie.set('token', '')
        this.$store.commit('setToken', null)
      })
    }
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
#main {
  min-height: 80vh;
}
.toolbar{
  background-color: rgb(46, 118, 161);
  border-bottom: 1px solid #00000010;
}

.logo-text{
  font-weight: bold;
  color: #fff;
  font-size: 1.4rem;
}

.logo-image{
  height: 60px !important;
}

.toolbar-button {
  font-size: 1rem !important;
}
.footer {
  border-top: 1px solid #00000040;
  padding: .5rem 1rem;
  font-size: .9rem;
  text-align: right;
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
