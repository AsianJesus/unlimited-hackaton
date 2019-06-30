<template>
  <div class="course-completed">
    <div class= "row courses" style="padding-left: 33rem; padding-top: 3rem; ">
      <div>
        <h2>{{ course.name }}</h2>
      </div>
    </div>
    <div>
      <table class="table table-striped">
        <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Ad</th>
          <th scope="col">Soyad</th>
          <th scope="col">Points</th>
          <th scope="col">Maksimal davamiyyət</th>
          <th scope="col">Tarix</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(user, index) in users"
            v-bind:key="index"
            style="cursor:pointer;"
            @click="$router.push({ name: 'UserProfile', params: {id: user.user_id }})" >
          <th scope="row">{{ index + 1}}</th>
          <td>{{ user.user.name }}</td>
          <td>{{ user.user.surname }}</td>
          <td>{{ user.user.points }}</td>
          <td>{{ user.user.max_week_streak }}</td>
          <td>{{ user.created_at }}</td>
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
      return this.$route.params.id
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
      axios.get(`courses/${id || this.id}/dashboard`).then(({ data }) => {
        this.course = data
      })
    }
  }
}
</script>

<style scoped>

</style>
