import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import HomePage from '@/pages/HomePage'
import RegistrationPage from '@/pages/RegistrationPage'
import FieldPage from '@/pages/FieldPage'
import SpecialityPage from '@/pages/SpecialityPage'
import LoginPage from '@/pages/LoginPage'
import CoursePage from '@/pages/CoursePage'
import ActualCoursePage from '@/pages/CurrentCoursePage'
import CourseDashboard from '@/pages/CourseCompleted'
import LessonPage from '@/pages/LessonPage'
import ExamPage from '@/pages/ExamPage'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      component: HomePage
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
    },
    {
      path: '/fields/:name',
      name: 'Field',
      component: FieldPage
    },
    {
      path: '/specs/:name',
      name: 'Speciality',
      component: SpecialityPage
    },
    {
      path: '/course/:id',
      name: 'Course',
      component: CoursePage
    },
    {
      path: '/my-course/:id',
      name: 'ActualCourse',
      component: ActualCoursePage
    },
    {
      path: '/course/:id/dashboard',
      name: 'CourseDashboard',
      component: CourseDashboard
    },
    {
      path: '/lessons/:id/',
      name: 'Lesson',
      component: LessonPage
    },
    {
      path: '/exams/:id',
      name: 'Exam',
      component: ExamPage
    }
  ]
})
