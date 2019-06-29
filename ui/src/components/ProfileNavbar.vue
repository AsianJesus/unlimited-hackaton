<template>
  <ul class="nav flex-column">
    <li @click="showCrop = true"
        style="text-align: center;">
      <img :src="userAvatar"
           class="avatar"
           v-if="userAvatar">
      <img src="@/assets/user_avatar.png"
           class="avatar"
           v-else>
    </li>
    <li v-if="readonly" style="padding: .5rem 1rem;">
      <b-button-group>
        <b-button @click="isFriend(user.id) ? unfriend(user.id) : friend(user.id)"
                :variant="isFriend(user.id) ? 'warning' :'success'"
        >{{ isFriend(user.id) ? 'Unfriend' : 'Friend '}}</b-button>
        <b-button variant="primary"
                  @click="sendMessage" >
          <font-awesome-icon :icon="messageIcon" />
        </b-button>
      </b-button-group>
    </li>
    <li class="user-name">
      {{ user.name }} {{ user.surname }}
    </li>
    <li class="nav-item">
      <router-link class="nav-link active"
                   :to="{ name: 'MyProfile' }"
                   style="font-size: 1.3rem;">
        Dashboard
      </router-link>
    </li>
    <li class="nav-item">
      <router-link class="nav-link"
                   :to="{ name: 'MyFriends' }"
                   style="font-size: 1.3rem;">
        Friends
      </router-link>
    </li>
    <li class="nav-item">
      <router-link class="nav-link"
                   :to="{ name: 'Messenger' }"
                   style="font-size: 1.3rem;">
        Messenger
      </router-link>
    </li>
    <vue-image-upload v-if="!readonly"
                      v-model="showCrop"
                      :url="serverURL"
                      :headers="headers"
                      lang-type="en"
                      @crop-upload-success="changeImage" />
  </ul>
</template>

<script>
import { serverURL } from '../config'
import { faEnvelope } from '@fortawesome/free-solid-svg-icons'
import VueImageUpload from 'vue-image-crop-upload'
import axios from 'axios'
export default {
  name: 'ProfileNavbar',
  components: {
    VueImageUpload
  },
  props: {
    readonly: {
      type: Boolean,
      default: false
    },
    initialUser: {
      default: null
    }
  },
  computed: {
    user () {
      return this.initialUser || this.$store.state.userInfo
    },
    userAvatar () {
      return this.user.avatar ? `${serverURL}/../${this.user.avatar}` : null
    },
    headers () {
      return {
        'Authorization': `Bearer ${this.$store.state.token}`
      }
    },
    messageIcon: () => faEnvelope
  },
  data () {
    return {
      showCrop: false,
      serverURL: `${serverURL}/user`
    }
  },
  methods: {
    changeImage (response) {
      this.$store.commit('setUserInfo', response)
    },
    isFriend (id) {
      return this.$store.state.friends.some(f => f === id)
    },
    friend (id) {
      axios.post(`/friends/${id}`).then(() => {
        this.$store.commit('addFriend', id)
      })
    },
    unfriend (id) {
      axios.delete(`/friends/${id}`).then(() => {
        this.$store.commit('deleteFriend', id)
      })
    },
    sendMessage () {
      let message = prompt('Input message: ')
      if (message) {
        axios.post(`/chats/${this.user.id}`, {
          message: message
        }).then(() => {
          this.$router.push({ name: 'Messenger' })
        })
      }
    }
  }
}
</script>

<style scoped>
.user-name{
  font-size: 1.3rem;
  padding: .5rem 1rem;
}
.nav {
  text-align: left;
}
</style>
