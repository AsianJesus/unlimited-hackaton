import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import RegistrationPage from '@/pages/RegistrationPage'
import LoginPage from '@/pages/LoginPage'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'HelloWorld',
      component: HelloWorld
    },
    {
      path: '/register',
      name: 'Registration',
      component: RegistrationPage
    },
    {
      path: '/login',
      name: 'Login',
      component: LoginPage
    },
    {
      path: '/profile',
      name: 'MyProfile',
      component: HelloWorld
    }
  ]
})
