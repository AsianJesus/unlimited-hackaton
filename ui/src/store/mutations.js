import axios from 'axios'
export default {
  setUserInfo (state, userInfo) {
    state.userInfo = userInfo
    state.friends = userInfo.friends || state.friends
  },
  setToken (state, token) {
    axios.defaults.headers.common = {
      'Authorization': `Bearer ${token}`
    }
    state.token = token
    state.isLogged = !!token
  },
  setShouldRedirect (state, f) {
    state.shouldRedirect = f
  },
  addFriend (state, id) {
    state.friends.push(id)
  },
  deleteFriend (state, id) {
    state.friends = state.friends.filter(f => f !== id)
  }
}
