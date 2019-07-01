<template>
  <div class="login-page row">
    <div class="login-body align-self-center col-12 col-md-4 offset-md-4">
      <b-form>
        <b-form-input v-model="form.email"
                      class="login-element"
                      placeholder="Email" />
        <b-form-input v-model="form.password"
                      type="password"
                      class="login-element"
                      placeholder="Şifrə" />
        <div class="login-button-holder">
          <b-button @click="login"
                    variant="light"
                    :disabled="isLogining">
            Giriş
          </b-button>
        </div>
      </b-form>
      <div style="text-align: right;">
        <router-link :to="{name: 'Registration', params: {callback: callback}}">Qeydiyyatdan keç</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'LoginPage',
  computed: {
    callback () {
      return this.$route.params.callback
    }
  },
  data () {
    return {
      form: {
        email: '',
        password: ''
      },
      isLogining: false
    }
  },
  methods: {
    login () {
      if (this.isLogining) {
        return
      }
      this.isLogining = true
      axios.post('/users/login', this.form).then(response => {
        this.$cookie.set('token', response.data.token)
        this.$store.commit('setUserInfo', response.data.user)
        this.$store.commit('setToken', response.data.token)
        this.isLogining = false
        if (this.callback) {
          this.callback()
        } else {
          this.$router.push({name: 'MyProfile'})
        }
      }).catch(() => {
        alert('Username or password is invalid')
        this.isLogining = false
      })
    }
  }
}
</script>

<style scoped>
.login-button-holder {
  text-align: right;
}
.login-element{
  margin: .5rem auto;
}
</style>
