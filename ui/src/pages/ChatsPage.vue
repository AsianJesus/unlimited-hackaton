<template>
  <div class="chats-page">
    <div class="row">
      <profile-navbar />
      <div class="col-3">
        <div v-for="(c, i) in chats"
             v-bind:key="i"
             @click="selectChat(i)"
             class="row chat-element">
            <div class="col">
              <img :src="`${serverURL}/../${c.avatar}`"
                   class="mini-avatar"
                   v-if="c.avatar">
              <img src="@/assets/user_avatar.png"
                   class="mini-avatar"
                   v-else>
            </div>
            <div class="col">
              <div>
                <router-link :to="{name: 'UserProfile', params: {id: c.id}}">
                  {{ c.name }} {{ c.surname }}
                </router-link>
              </div>
              <div style="font-size: .9rem; color: #00000080">
                {{ c.last_message }}
              </div>
            </div>
        </div>
      </div>
      <div class="col messages-body"
           v-if="selectedChat !== null">
        <div class="new-message-body" @keydown="checkKey">
          <b-form-input v-model="newMessage" />
        </div>
        <div class="messages-holder">
          <div  v-for="(message, mIndex) in messages[currentChatId] || []"
                v-bind:key="mIndex"
                class="message-holder"
                :class="{
                   'message-mine-holder': message.from === myId,
                   'message-not-mine-holder': message.to === myId
                }">
            <span class="message"
                  :class="{
                     'message-mine': message.from === myId,
                     'message-not-mine': message.to === myId
                  }">
              {{ message.message }}
            </span>
            <div style="font-size: .8rem; color: #00000060;">
              {{ message.created_at }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import ProfileNavbar from '../components/ProfileNavbar'
import { serverURL } from '../config'

export default {
  name: 'ChatsPage',
  components: {
    ProfileNavbar
  },
  data () {
    return {
      chats: [],
      messages: {},
      serverURL: serverURL,
      selectedChat: null,
      newMessage: null
    }
  },
  computed: {
    currentChatId () {
      return (this.chats[this.selectedChat] || {}).id
    },
    myId () {
      return this.$store.state.userInfo.id
    }
  },
  watch: {
    selectedChat (index) {
      if (!this.messages[this.chats[index].id]) {
        this.loadMessages(this.chats[index].id)
      }
    }
  },
  mounted () {
    this.load()
  },
  methods: {
    load () {
      axios.get('/chats').then(({ data }) => {
        this.chats = data
      })
    },
    selectChat (index) {
      this.selectedChat = index
    },
    loadMessages (id) {
      axios.get(`/chats/${id}`).then(({ data }) => {
        this.$set(this.messages, id, data)
      })
    },
    checkKey (event) {
      if (event.keyCode === 13) {
        this.sendMessage(this.newMessage)
        this.newMessage = ''
      }
    },
    sendMessage (text) {
      let chatId = this.currentChatId
      axios.post(`chats/${chatId}`, {
        message: text
      }).then(({ data }) => {
        this.messages[chatId] = this.messages[chatId] || []
        this.messages[chatId].push(data)
      })
    }
  }
}
</script>

<style scoped>
.chat-element {
  padding: .25rem .75rem;
  text-align: left;
  cursor: pointer;
}
.chat-element:hover {
  background-color: #00000010;
}
.messages-body {
  position: relative;
  height: calc(100vh - 7rem);
  padding-bottom: 6rem;
  background-image: linear-gradient(to bottom, rgba(154,154,154,0), rgba(154,154,154,.6));
  overflow-y: auto;
}
.new-message-body{
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 5rem;
  background-color: white;
}
.message-mine-holder{
  text-align: right;
}
.message-not-mine-holder{
  text-align: left;
}
.message-mine {
  background-color: #dcf8c6;
  border-radius: 10px;
}
.message-not-mine{
  background-color: white;
}
.message {
  padding: .5rem 1rem;
}
.message-holder {
  margin: 1rem auto;
}
</style>
