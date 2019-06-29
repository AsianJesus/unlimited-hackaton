<template>
  <div class="login-page row">
    <div class="login-body col-12 col-md-6 offset-md-3">
      <b-form>
        <b-form-input v-model="form.email"
                      placeholder="Email" />
        <b-form-input v-model="form.password"
                      type="password"
                      placeholder="Password" />
        <div class="login-button-holder">
          <b-button @click="login"
                    :disabled="isLogining">
            Login
          </b-button>
        </div>
      </b-form>
      <div>
        <router-link :to="{name: 'Registration'}">Don't have an account?</router-link>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'LoginPage',
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
        this.$router.push({ name: 'MyProfile' })
        this.isLogining = false
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
</style>
