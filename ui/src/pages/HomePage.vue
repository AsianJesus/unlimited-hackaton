<template>
  <div class="home-page">
    <div class ="row main-start" style="text-align: right;">
      <div class="col-6 loz">
        <div class="about">
          <p class="text-right">About us</p>
        </div>
        <div class = "info">
          <div class="text-right">
            <ul style="list-style-type: none;">
              <li>
                Həvəsləndirir <img src="@/assets/motivator.png">
              </li>
              <li>
                Öyrədir <img src="@/assets/edu.png">
              </li>
              <li>
                Yeni perspektivlərə yol açır <img src="@/assets/opport.png">
              </li>
              <li>
                Dostlar qazandırır <img src="@/assets/friends.png">
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col">
        <img src="@/assets/wallper_main.jpg" class="img-fluid main-image" alt="Responsive image">
      </div>
      <div class="col-12"
           style="text-align: center;"
           @click="scrollToFields" >
        <button type="button" class="btn btn-light scroll-down-button">
          <font-awesome-icon :icon="iconCaret" />
        </button>
      </div>
    </div>
    <div class ="row spec"
         style="min-height: 100vh;"
         ref="fieldsSection" >
      <div class="col-sm-4"
           v-for="(field, index) in fields"
           v-bind:key="index" >
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{{ field.name }}</h5>
            <p class="card-text">{{ field.description }}</p>
            <button @click="$router.push({ name: 'Field', params: { name: field.name }})"
                href="#"
                    class="btn btn-primary">Go somewhere</button>
          </div>
        </div>
      </div>
      <div class="col-12 join-holder">
        <button @click="$router.push({ name: 'Registration' })"
                type="button"
                class="btn btn-success join">Join us</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { faCaretDown } from '@fortawesome/free-solid-svg-icons'
export default {
  name: 'HomePage',
  data () {
    return {
      fields: []
    }
  },
  computed: {
    iconCaret: () => faCaretDown
  },
  mounted () {
    if (this.$store.state.isLogged && this.$store.state.shouldRedirect) {
      this.$store.commit('setShouldRedirect', false)
      this.$router.push({name: 'MyProfile'})
    }
    this.loadFields()
  },
  methods: {
    scrollToFields () {
      this.$refs.fieldsSection.scrollIntoView()
    },
    loadFields () {
      axios.get('/fields').then(response => {
        this.fields = response.data
      })
    }
  }
}
</script>

<style scoped>
.loz{
  font-size: 1.4rem;
  font-weight: bold;
  text-align: right;
}
.main-start{
  height: 100vh;
  padding: 3rem 0;
  background-color: rgba(50, 115, 220, 0.3);;
}
.main-image{
  border: 1px solid #00000030;
  border-radius: 15rem;
  box-shadow: 0 0 1px 2px #00000010;
  width: 420px;
  height: 420px;
}

.join-holder{
  text-align: center;
}

.join{
  padding: 1.2rem 2.2rem;
  border-radius: 15rem;
  font-size: 1.3rem;
}
.info img{
  width: 3.5rem;
  border-radius: 100%;
  padding: .3rem .3rem;
}
.info li{
  margin: 1rem auto;
}
.scroll-down-button {
  border-radius: 100%;
}
.home-page{
  padding-bottom: 1rem;
}
</style>
