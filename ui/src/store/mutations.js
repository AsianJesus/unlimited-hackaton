import axios from 'axios'
export default {
  setUserInfo (state, userInfo) {
    state.userInfo = userInfo
  },
  setToken (state, token) {
    axios.defaults.headers.common = {
      'Authentication': `Bearer ${token}`
    }
    state.token = token
  }
}
