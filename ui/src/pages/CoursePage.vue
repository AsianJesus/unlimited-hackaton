<template>
  <div class="course-page">
    <div class= "row courses">
      <h2  class="col-12 "> {{ course.name }}</h2>
      <div class="col-10 description">
        <p>{{ course.description }}</p>
        <div class="l-cards-holder row">
          <div class="l-card-holder  col-4"
               v-for="(lesson, index) in lessons"
               v-bind:key="index">
            <div class="l-card l-card-success">
              <div>{{ lesson.name }}</div>
              <div>Estimated time: {{ lesson.required_time }} minutes</div>
            </div>
          </div>
        </div>
        <div class="join-course-holder">
          <b-button class="join-course"
                    variant="outline-primary"
                    @click="join" >
            Join course
          </b-button>
        </div>
      </div>
      <div class="col-2 inf">
        <div>Estimated time: {{ course.totalTime }} </div>
        <div>Learns count: {{ course.users_count }} </div>
        <div>Completed: {{ course.completed_users_count }} </div>
        <div>Failed: {{ course.failed_users_count }} </div>
        <div>
          <router-link :to="{ name: 'CourseDashboard', params: { id: id }}">Who completed</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'CoursePage',
  data () {
    return {
      course: {}
    }
  },
  computed: {
    id () {
      return this.$route.params.id
    },
    lessons () {
      return this.course.lessons || []
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
    loadCourse (id) {
      axios.get(`/courses/${id || this.id}`).then(response => {
        this.course = response.data
        this.course.totalTime = parseInt(this.course.lessons.reduce((a, b) => a + b['required_time'] || 0, 0).toString())
      })
    },
    checkIfGoing (id) {
      axios.get(`/courses/${id || this.id}/check`).then(({ data }) => {
        if (data && data !== 'false') {
          this.redirectToActual()
        } else {
          this.loadCourse(id || this.id)
        }
      })
    },
    redirectToActual (id) {
      this.$router.push({name: 'ActualCourse', params: { id: id || this.id }})
    },
    join () {
      if (!this.$store.state.isLogged) {
        this.$router.push({ name: 'Login' })
        return
      }
      axios.post(`/courses/${this.id}`).then(() => {
        this.redirectToActual(this.id)
      })
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
  font-size: .95rem;
  padding: .5rem 1rem;
  border: 1px solid black;
  border-radius: 3rem;
  background-color: #30303020
}

.l-card::after{
  width: 5px;
  position: absolute;
  top: 50%;
  bottom: 0;
  right: 0;
  transform: translateY(-50%);
  margin: auto;
  content: '>';
}

.l-card-holder:last-of-type .l-card::after{
  content: '';
}
.join-course {
  border-radius: 10rem;
}
</style>
