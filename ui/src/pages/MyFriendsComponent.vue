<template>
  <div class="my-friends-component">
    <div class="row">
      <profile-navbar />
      <div class="col">
        <div class="row"
             v-for="(user, i) in users"
             v-bind:key="i"
             style="padding-top: 2rem; padding-left: 8rem;">
          <div class="col" style="text-align: right;">
            <img v-if="!user.avatar"
                 src="@/assets/user_avatar.png"
                 alt="Avatar"
                 class="avatar avtr"
                 @click="showCrop = true" >
            <img v-else
                 :src="`${baseServerURL}/../${user.avatar}`"
                 alt="Avatar"
                 class="avatar avtr"
                 @click="showCrop = true" >
          </div>
          <div class="col" style="text-align: left;">
            <p>{{ user.name }} {{ user.surname }}</p>
          </div>
          <div class="col" style="text-align: right;">
            <button type="button"
                    class="btn"
                    @click="isFriend(user.id) ? unfriend(user.id) : friend(user.id)"
                    :class="{
                      'btn-warning': isFriend(user.id),
                      'btn-success': !isFriend(user.id)
                    }"
            >{{ isFriend(user.id) ? 'Unfriend' : 'Friend '}}</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { serverURL } from '../config'
import ProfileNavbar from '../components/ProfileNavbar'
export default {
  name: 'MyFriendsComponent',
  components: {
    ProfileNavbar
  },
  data () {
    return {
      users: [],
      baseServerURL: serverURL,
      friendsIDs: []
    }
  },
  mounted () {
    this.load()
  },
  methods: {
    load () {
      axios.get('/friends').then(({ data }) => {
        this.users = data
        this.friendsIDs = data.map(x => x.id)
      })
    },
    friend (id) {
      axios.post(`/friends/${id}`).then(() => {
        this.friendsIDs.push(id)
        this.$store.commit('addFriend', id)
      })
    },
    unfriend (id) {
      axios.delete(`/friends/${id}`).then(() => {
        this.friendsIDs = this.friendsIDs.filter(x => x !== id)
        this.$store.commit('deleteFriend', id)
      })
    },
    isFriend (id) {
      return this.friendsIDs.indexOf(id) !== -1
    }
  }
}
</script>

<style scoped>
.avatar {
  cursor: pointer;
  border-radius: 100%;
  width: 100px;
}
</style>
