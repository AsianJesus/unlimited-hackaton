<template>
  <div class="my-friends-component">
    <div class="row">
      <profile-navbar />
      <div class="col-9">
        <b-card-group deck>
          <b-card :img-src="user.avatar ? `${baseServerURL}/../${user.avatar}` : null"
                  class="user-card"
                  style="max-width: 10rem;"
                  img-top
                  @click="$router.push({name: 'UserProfile', params: {id: user.id}})"
                  v-for="(user, i) in users"
                  v-bind:key="i">
            <b-card-text>
              {{ user.name }} {{ user.surname }}
            </b-card-text>
            <b-card-footer>
              <b-button @click="isFriend(user.id) ? unfriend(user.id) : friend(user.id)"
                        :variant="isFriend(user.id) ? 'warning': 'success'"
              >{{ isFriend(user.id) ? 'Unfriend' : 'Friend '}}</b-button>
            </b-card-footer>
          </b-card>
        </b-card-group>
      </div>
            <!--<img v-if="!user.avatar"
                 src="@/assets/user_avatar.png"
                 alt="Avatar"
                 class="avatar avtr"
                 @click="showCrop = true" >
            <img v-else
                 :src="`${baseServerURL}/../${user.avatar}`"
                 alt="Avatar"
                 class="avatar avtr"
                 @click="showCrop = true" >
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
          </div> -->
      <div v-if="!users.length">
        <h4>
          Sizin dostuvuz yoxdur
        </h4>
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
        this.friendsIDs = data.filter(x => x).map(x => x.id)
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
.user-card{
  cursor: pointer;
}
</style>
