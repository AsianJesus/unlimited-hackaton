<template>
  <div class="current-course-page">
    <div class= "row courses">
      <h2  class="col-12 " style="padding-left: 6rem;">{{ course.name }}</h2>
      <div class="col-10 description">
        <div>
          <p>{{ course.description }}</p>
        </div>
        <div class="row">
          <div class="l-cards-holder col-8 offset-2">
            <div class="l-card-holder"
                 v-for="(element, index) in elements"
                 v-bind:key="index"
                 v-if="!element.lesson_id" >
              <lesson-component v-bind:value="element"
                                :success="element.my_passes_count !== 0"
                                custom-buttons>
                <template slot="buttons" v-if="!isDisabled(index)">
                  <div style="text-align: right;">
                    <b-button variant="outline-info"
                              @click="openLesson(element)">
                      Davam et
                    </b-button>
                  </div>
                </template>
              </lesson-component>
            </div>
            <div class="l-card-holder"
                 @click="openExam(element)"
                 v-else>
              <div class="l-card"
                   :class="{
                  'success': hasPassed(element),
                  'error': element.my_passes && element.my_passes.length && !hasPassed(element)
                 }">
                Exam
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-2 inf">
        <div>Müddət: {{ course.totalTime }} minute(s) </div>
        <div>İştirakçıların sayı: {{ course.users_count }} </div>
        <div>Bitirənlərin sayı: {{ course.completed_users_count }} </div>
        <div>Bitirə bilmıyənlərin sayı: {{ course.failed_users_count }} </div>
        <div>Qalan dərslər: {{ course.remainingLessons }} </div>
        <div>
          <router-link :to="{ name: 'CourseDashboard', params: { id: id }}">Bitirənlər</router-link>
        </div>
      </div>
    </div>
    <div style="text-align: center;"
         v-if="canLeave" >
      <b-button @click="leave"
                variant="outline-danger">
        Kursu bitir
      </b-button>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import LessonComponent from '../components/LessonComponent'
export default {
  name: 'CurrentCoursePage',
  components: {
    LessonComponent
  },
  data () {
    return {
      course: {},
      lessons: [],
      exams: [],
      elements: []
    }
  },
  computed: {
    id () {
      return this.$route.params.id
    },
    canLeave () {
      return this.exams.some(e => !this.hasPassed(e))
    }
  },
  watch: {
    id (val) {
      this.checkIfGoing(val)
    }
  },
  mounted () {
    this.checkIfGoing()
  },
  methods: {
    load (id) {
      axios.get(`/courses/${id || this.id}`).then(({ data }) => {
        this.course = data
        this.lessons = data.lessons
        this.exams = data.exams
        this.course.totalTime = Math.floor(this.lessons.reduce((a, b) => a + b['required_time'], 0))
        let passedLessonsCount = this.lessons.filter(l => l.my_passes_count).length
        this.course.remainingLessons = this.lessons.length - passedLessonsCount
        this.elements = JSON.parse(JSON.stringify(this.lessons))
        for (let i = this.lessons.length - 1; i >= 0; i--) {
          let exam = this.exams.find(e => e.lesson_id === i)
          if (exam) {
            this.elements.splice(i, 0, exam)
          }
        }
      })
    },
    checkIfGoing (id) {
      axios.get(`/courses/${id || this.id}`).then(({ data }) => {
        if (!data || data === 'false') {
          this.redirectToActual()
        } else {
          this.load(id || this.id)
        }
      })
    },
    redirectToActual (id) {
      this.$router.push({name: 'Course', params: { id: id || this.id }})
    },
    openLesson ({ id }) {
      this.$router.push({name: 'Lesson', params: {id: id}})
    },
    openExam (exam) {
      if (!this.hasPassed(exam)) {
        this.$router.push({name: 'Exam', params: {id: exam.id}})
      }
    },
    leave () {},
    hasPassed (exam) {
      console.log(exam)
      return exam.my_passes && exam.my_passes.some(x => x.is_finished)
    },
    isDisabled (index) {
      return this.exams.some(e => e.lesson_id < index && !this.hasPassed(e))
    }
  }
}
</script>

<style scoped>
.l-card-holder{
  padding: .5rem 1rem;
  position: relative;
}

.l-card{
  cursor: pointer;
  font-size: .95rem;
  padding: .5rem 1rem;
  border: 1px solid black;
  border-radius: 3rem;
  background-color: #30303020
}

.join-course {
  border-radius: 10rem;
}
.success {
  background-color: #34b96c;
}
.disabled {
  background-color: #63646b;
  cursor: inherit;
}
.error {
  background-color: red;
}
.inf{
  text-align: left;
}
</style>
