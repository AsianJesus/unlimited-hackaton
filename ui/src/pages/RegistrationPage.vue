<template>
  <div class="registration-page row">
    <div class="registration-body align-self-center col-12 offset-md-3 col-md-6">
      <h3>
        Qeydiyyat
      </h3>
      <div>
        <span class="step-indicator"
            :class="{ 'step-activated': currentStep === 0 }"/>
        <span class="step-indicator"
            :class="{ 'step-activated': currentStep === 1 }"/>
        <span class="step-indicator"
            :class="{ 'step-activated': currentStep === 2 }"/>
      </div>
      <transition name="bounce">
        <div v-if="currentStep === 0">
          <div>
            <b-form>
              <b-form-input placeholder="Email"
                            class="registration-input"
                            v-model="form.email"
                            :state="showErrors && firstStageErrors.email ? false : null"
              />
              <b-form-input placeholder="Password"
                            type="password"
                            class="registration-input"
                            v-model="form.password"
                            :state="showErrors && firstStageErrors.password ? false : null" />
              <b-form-input placeholder="Repeat password"
                            type="password"
                            class="registration-input"
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
                            class="registration-input"
                            :state="showErrors && secondStageErrors.name? false : null" />
              <b-form-input placeholder="Surname"
                            v-model="form.surname"
                            class="registration-input"
                            :state="showErrors && secondStageErrors.surname ? false : null" />
            </b-input-group>
            <b-form-textarea  placeholder="Hobbies"
                              class="registration-input"
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
                        class="registration-input"
                        :close-on-select="false"
                        multiple />
          <h4>
            Skills
          </h4>
          <multiselect  :options="availableSkills"
                        v-model="form.skills"
                        track-by="id"
                        label="name"
                        class="registration-input"
                        :close-on-select="false"
                        multiple />
        </div>
      </transition>
      <div class="reg-control-buttons row">
        <div class="col" style="text-align: left;">
          <b-button @click="currentStep--"
                    :disabled="!canGoLeft">
            Previous
            <font-awesome-icon  :icon="faCaretSquareLeft" />
          </b-button>
        </div>
        <div class="col" style="text-align: right;">
          <b-button @click="goNext">
            Next
            <font-awesome-icon  :icon="faCaretSquareRight" />
          </b-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { faCaretLeft, faCaretRight } from '@fortawesome/free-solid-svg-icons'
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
        email: '',
        password: '',
        name: '',
        surname: '',
        hobby: '',
        languages: [],
        skills: []
      },
      availableLanguages: [],
      availableSkills: [],
      repeatPassword: '',
      showErrors: false
    }
  },
  computed: {
    faCaretSquareLeft: () => faCaretLeft,
    faCaretSquareRight: () => faCaretRight,
    canGoLeft () {
      return this.currentStep > 0
    },
    doesPasswordMatch () {
      return this.form.password === this.repeatPassword
    },
    firstStageErrors () {
      return {
        email: !this.form.email || !this.form.email.match(/.+@.+\..+/),
        password: !this.form.password || this.form.password.length < 5
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
  .step-indicator{
    height: 15px;
    width: 15px;
    margin: 0 2px;
    background-color: #bbbbbb;
    border: none;
    border-radius: 50%;
    display: inline-block;
    opacity: 0.5;
    transition: all .5s linear;
  }
  .step-activated {
    background-color: #000;
  }
  .registration-input{
    margin: 1rem auto;
  }
</style>
