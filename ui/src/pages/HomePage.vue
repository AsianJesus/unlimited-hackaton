<template>
  <div class="home-page">
    <div class ="row main-start" style="text-align: right;">
      <div class="col-6 loz">
        <div class="about">
          <h2 style="text-align: center; font-weight: bold;">Məqsədlərimiz</h2>
        </div>
        <div class = "info">
          <div>
            <ul style="list-style-type: none;">
              <li>
                <img src="@/assets/motivator.png"> Həvəsləndirir
              </li>
              <li>
                <img src="@/assets/edu.png"> Öyrədir
              </li>
              <li>
                <img src="@/assets/opport.png"> Yeni perspektivlərə yol açır
              </li>
              <li>
                <img src="@/assets/friends.png"> Dostlar qazandırır
              </li>
              <li>
                <img src="@/assets/mentor.png"> Mentorlar ilə ikfirlik
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col">

        <img src="https://i.ytimg.com/vi/J0BPoofmPkw/maxresdefault.jpg" class="img-fluid main-image" alt="Responsive image">
        <!--<img src="@/assets/main_image.jpeg" class="img-fluid main-image" alt="Responsive image">-->

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
         ref="fieldsSection" >
      <div class="col-sm-4"
           v-for="(field, index) in fields"
           v-bind:key="index" >
        <div class="card" style="box-shadow: 0 0 1px 2px #00000040; margin: 1rem auto;">
          <div class="card-body" style="text-align: right;">
            <h5 class="card-title" style="text-align: center;">{{ field.name }}</h5>
            <p class="card-text" style="text-align: left;">{{ getDescription(field) }}</p>
            <button @click="$router.push({ name: 'Field', params: { name: field.name }})"
                href="#"
                    class="btn btn-primary">Davam
              <font-awesome-icon :icon="iconCaretRight"/>
            </button>
          </div>
        </div>
      </div>
      <div class="col-12"
           @click="scrollToJoin" >
        <button type="button" class="btn btn-primary scroll-down-button">
          <font-awesome-icon :icon="iconCaret" />
        </button>
      </div>
    </div>
    <div class="row join-proposal-window"
         ref="joinUs">
      <div class="col-6 offset-3 align-self-center">
        <div>
          <div class="join-holder">
            <button @click="$router.push({ name: 'Registration' })"
                    type="button"
                    class="btn btn-outline-light join">
              Dəyişiklər səndən başlayır!
              Bizə qoşul
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { faCaretDown, faCaretRight } from '@fortawesome/free-solid-svg-icons'
export default {
  name: 'HomePage',
  data () {
    return {
      fields: []
    }
  },
  computed: {
    iconCaret: () => faCaretDown,
    iconCaretRight: () => faCaretRight
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
      this.$refs.fieldsSection.scrollIntoView({behavior: 'smooth'})
    },
    scrollToJoin () {
      this.$refs.joinUs.scrollIntoView({behavior: 'smooth'})
    },
    loadFields () {
      axios.get('/fields').then(response => {
        this.fields = response.data
      })
    },
    getDescription ({ description }) {
      return description && description.length > 200 ? description.substring(0, 200) + '...' : description
    }
  }
}
</script>
<style scoped>
  .loz{
    font-size: 1.8rem;
    font-weight: bold;
    text-align: left;
  }
  .main-start{
    height: 100vh;
    padding: 3rem 0;
    color: white;
    position: relative;
    background: linear-gradient(to bottom, rgb(46, 118, 161), white);
    /*background-color: rgba(50, 115, 220, 0.3);;*/
  }
  .main-image{
    border: 1px solid #00000030;
    border-radius: 4rem;
    box-shadow: 0 -2px 1px 2px #00000020;
    height: 420px;
  }
  .join-holder{
    text-align: center;
  }
  .join{
    padding: .7rem 1rem;
    border-radius: 15rem;
    font-size: 1.3rem;
  }
  .spec{
    min-height: 100vh;
    padding: 1rem 0;
  }
  .info img{
    width: 3.6rem;
    border-radius: 100%;
    padding: .3rem .3rem;
  }
  .info li{
    margin: 1rem auto;
  }
  .scroll-down-button {
    border-radius: 100%;
  }
  .join-proposal-window{
    height: 100vh;
    background: url('https://wallpapertag.com/wallpaper/full/1/e/5/143818-best-good-background-1920x1080.jpg');
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
  }
</style>
