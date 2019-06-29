<template>
  <div class="course-completed">
    <div class= "row courses" style="padding-left: 33rem; padding-top: 3rem; ">
      <div>
        <h2>Course name</h2>
      </div>
    </div>
    {{ users }}
    <div>
      <table class="table table-striped">
        <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">First name</th>
          <th scope="col">Last name</th>
          <th scope="col">Level</th>
          <th scope="col">Max strike</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(user, index) in users"
            v-bind:key="index">
          <th scope="row">{{ index + 1}}</th>
          <td>{{ user.name }}</td>
          <td>{{ user.surname }}</td>
          <td>{{ user.level }}</td>
          <td>{{ user.max_strike }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'CourseCompleted',
  data () {
    return {
      course: {}
    }
  },
  computed: {
    users () {
      return this.course.completed_users || []
    },
    id () {
      return this.$route
    }
  },
  watch: {
    id (val) {
      this.load(val)
    }
  },
  mounted () {
    this.load()
  },
  methods: {
    load (id) {
      axios.get(`course/${id || this.id}/dashboard`).then(({ data }) => {
        this.course = data
      })
    }
  }
}
</script>

<style scoped>

</style>
