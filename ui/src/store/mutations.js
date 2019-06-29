import axios from 'axios'
export default {
  setUserInfo (state, userInfo) {
    state.userInfo = userInfo
  },
  setToken (state, token) {
    axios.defaults.headers.common = {
      'Authorization': `Bearer ${token}`
    }
    state.token = token
    state.isLogged = token !== null
  }
}
