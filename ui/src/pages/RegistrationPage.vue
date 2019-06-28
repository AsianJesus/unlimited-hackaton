<template>
  <div class="registration-page row">
    <div class="registration-body align-self-center col-12 offset-md-3 col-md-6">
      <h3>
        Registration
      </h3>
      <transition name="bounce">
        <div v-if="currentStep === 0">
          <div>
            <b-form>
              <b-form-input placeholder="Email"
                            v-model="form.email"
                            :state="showErrors && firstStageErrors.email ? false : null"
              />
              <b-form-input placeholder="Password"
                            type="password"
                            v-model="form.password"
                            :state="showErrors && firstStageErrors.password ? false : null" />
              <b-form-input placeholder="Repeat password"
                            type="password"
                            v-model="repeatPassword"
                            :state="showErrors && firstStageErrors.password ? false : null" />
            </b-form>
          </div>
        </div>
        <div  v-else-if="currentStep === 1">
          <b-form>
            <b-input-group>
              <b-form-input placeholder="Name"
                            v-model="form.name"
                            :state="showErrors && secondStageErrors.name? false : null" />
              <b-form-input placeholder="Surname"
                            v-model="form.surname"
                            :state="showErrors && secondStageErrors.surname ? false : null" />
            </b-input-group>
            <b-form-textarea  placeholder="Hobbies"
                              v-model="form.hobby" />
          </b-form>
        </div>
        <div  v-else-if="currentStep === 2">
          <h4>
            Languages
          </h4>
          <multiselect  :options="availableLanguages"
                        v-model="form.languages"
                        track-by="id"
                        label="name"
                        :close-on-select="false"
                        multiple />
          <h4>
            Skills
          </h4>
          <multiselect  :options="availableSkills"
                        v-model="form.skills"
                        track-by="id"
                        label="name"
                        :close-on-select="false"
                        multiple />
        </div>
      </transition>
      <div class="reg-control-buttons row">
        <div class="col">
          <b-button @click="currentStep--"
                    :disabled="!canGoLeft">
            <font-awesome-icon  :icon="faCaretSquareLeft" />
          </b-button>
        </div>
        <div class="col">
          <b-button @click="goNext">
            <font-awesome-icon  :icon="faCaretSquareRight" />
          </b-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { faCaretSquareLeft, faCaretSquareRight } from '@fortawesome/free-solid-svg-icons'
import axios from 'axios'
export default {
  name: 'RegistrationPage',
  data () {
    return {
      showFirst: true,
      isEmployer: null,
      currentStep: 0,
      maxGoneStep: 2,
      form: {
        email: '1',
        password: '1',
        name: '1',
        surname: '1',
        hobby: '',
        languages: [],
        skills: []
      },
      availableLanguages: [],
      availableSkills: [],
      repeatPassword: '1',
      showErrors: false
    }
  },
  computed: {
    faCaretSquareLeft: () => faCaretSquareLeft,
    faCaretSquareRight: () => faCaretSquareRight,
    canGoLeft () {
      return this.currentStep > 0
    },
    doesPasswordMatch () {
      return this.form.password === this.repeatPassword
    },
    firstStageErrors () {
      return {
        email: !this.form.email,
        password: !this.form.password
      }
    },
    secondStageErrors () {
      return {
        name: !this.form.name,
        surname: !this.form.surname
      }
    }
  },
  mounted () {
    this.loadAvailableSkills()
    this.loadAvailableLanguages()
  },
  methods: {
    goNext () {
      if (this.currentStep === 0) {
        if (Object.values(this.firstStageErrors).some(x => x)) {
          this.showErrors = true
          return
        }
        if (!this.doesPasswordMatch) {
          alert('Passwords doesn\'t match')
          return
        }
      }
      if (this.currentStep === 1) {
        if (Object.values(this.secondStageErrors).some(x => x)) {
          this.showErrors = true
          return
        }
      }
      if (this.currentStep === 2) {
        this.register()
        return
      }
      this.showErrors = false
      this.currentStep++
    },
    register () {
      let form = JSON.parse(JSON.stringify(this.form))
      form.skills = form.skills.map(s => s.id)
      form.languages = form.languages.map(s => s.id)

      axios.post('/users', form).then(response => {
        this.$cookie.set('token', response.data.token)
        this.$store.commit('setUserInfo', response.data.user)
        this.$store.commit('setToken', response.data.token)
        this.$router.push({ name: 'MyProfile' })
      }).catch(() => {
        alert('Email is already in use')
      })
    },
    loadAvailableLanguages () {
      axios.get('/languages').then(response => {
        this.availableLanguages = response.data
      })
    },
    loadAvailableSkills () {
      axios.get('/skills').then(response => {
        this.availableSkills = response.data
      })
    }
  }
}
</script>

<style>
  .registration-page{
    text-align: center !important;
  }
  .bounce-enter-active {
    animation: slide-right .5s reverse;
  }
  .bounce-leave-active {
    animation: slide-right .5s;
  }
  @keyframes slide-right {
    0%{
      transform: translateX(0%);
    }
    100% {
      transform: translateX(100%);
    }
  }
</style>
