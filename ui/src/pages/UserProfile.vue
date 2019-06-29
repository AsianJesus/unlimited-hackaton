<template>
<div class="user-profile">
  <div class="row">
    <profile-navbar :initial-user="user"
                    readonly />
    <div class="col-10 graph">
      <!-- Insert chart here -->
      <line-chart v-if="datacollection"
                  ref="chart"
                  v-bind:chart-data="datacollection"
                  :options="chartOptions" />
      <div style="text-align: right; margin-top: 1rem;">
        <b-dropdown text="Select interval"
                    variant="success">
          <b-dropdown-item @click="loadStatistics('w')">Week</b-dropdown-item>
          <b-dropdown-item @click="loadStatistics('m')">Month</b-dropdown-item>
        </b-dropdown>
      </div>
      <div class="row">
        <div class="col-3 prg" >
          <p>Current streak: {{ weekInfo.streak }} </p>
          <div class="progress">
            <div class="progress-bar progress-bar-striped bg-success"
                 role="progressbar" style="width: 25%" :aria-valuenow="weekInfo.points"
                 aria-valuemin="0" :aria-valuemax="weekInfo.required"></div>
          </div>
        </div>
      </div>
      <!--<div>
        <div class="col-6 prg" style="padding-left: 15rem;">
          Course name
          <div class="progress" style="width: 300px;">
            <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
        </div>
      </div>-->
      <h4>
        Skills Progress
      </h4>
      <table class="table table-striped">
        <thead>
        <tr>
          <th scope="col">Name</th>
          <th scope="col">Degree</th>
          <th scope="col">Date</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(sg, index) in skillGains"
            v-bind:key="index">
          <th scope="row">{{ sg }}</th>
          <td>{{ '' }}</td>
          <td>{{ sg.date }}</td>
        </tr>
        <tr v-if="!skillGains.length">
          <th colspan="3" style="text-align: center;">No gains</th>
        </tr>
        </tbody>
      </table>
      <h4>Skills</h4>
      <table class="table table-striped">
        <thead>
        <tr>
          <th scope="col">Skill name</th>
          <th scope="col">Degree</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(skill, index) in skills"
            v-bind:key="index" >
          <th scope="row">{{ skill.skill.name }}</th>
          <td>{{ skill.level }}</td>
        </tr>
        <tr v-if="!skills.length">
          <th colspan="3" style="text-align: center;">No skills</th>
        </tr>
        </tbody>
      </table>
      <h4>
        Last Lessons
      </h4>
      <table class="table table-striped" >
        <thead>
        <tr>
          <th scope="col">Course</th>
          <th>Lesson</th>
          <th scope="col">Date</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(l, i) in lastLessons"
            v-bind:key="i" >
          <th scope="row">
            <router-link :to="{ name: 'ActualCourse', params: {id: l.lesson.course_id}}">
              {{ l.lesson.course.name }}
            </router-link>
          </th>
          <td>
            <router-link :to="{ name: 'Lesson', params: {id: l.lesson_id}}">
              {{ l.lesson.name}}
            </router-link>
          </td>
          <td>{{ l.created_at.substring(0, 10) }}</td>
        </tr>
        <tr v-if="!lastLessons.length">
          <th colspan="3" style="text-align: center;">
            No Last Lessons
          </th>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</template>

<script>
import LineChart from '../components/LineChart'
import ProfileNavbar from '../components/ProfileNavbar'
import axios from 'axios'
export default {
  name: 'UserProfile',
  data () {
    return {
      datacollection: null,
      chartOptions: {
        responsive: true,
        maintainAspectRatio: false
      },
      weekInfo: {},
      skillGains: [],
      skills: [],
      lastLessons: [],
      user: {}
    }
  },
  computed: {
    id () {
      return this.$route.params.id
    }
  },
  components: {
    LineChart,
    ProfileNavbar
  },
  watch: {
    id () {
      setTimeout(() => this.initialize, 1000)
    }
  },
  mounted () {
    this.initialize()
  },
  methods: {
    initialize () {
      if (this.id === this.$store.state.userInfo.id) {
        this.$router.push({ name: 'MyProfile' })
        return
      }
      this.loadStatistics()
      this.loadWeekInformation()
      this.loadSkillsGain()
      this.loadLastLessons()
      this.loadUserSkills()
      this.loadUserInfo()
    },
    loadStatistics (chartInterval = 'w') {
      axios.get('/statistics/points', {
        params: {
          interval: chartInterval
        }
      }).then(response => {
        this.datacollection = {
          labels: Object.keys(response.data),
          datasets: [
            {
              label: 'Points',
              data: Object.values(response.data)
            }
          ]
        }
        if (this.$refs.chart) {
          this.$refs.chart.renderChart(this.datacollection, this.chartOptions)
        }
      })
    },
    loadWeekInformation () {
      axios.get('/statistics/week', {
        params: {
          user_id: this.id
        }
      }).then(({ data }) => {
        this.weekInfo = data.info || {}
        this.weekInfo.required = data.required
        this.weekInfo.streak = (data.user || {}).week_streak || 0
      })
    },
    loadSkillsGain () {
      axios.get('/statistics/skill-gain', {
        params: {
          user_id: this.id
        }
      }).then(({ data }) => {
        this.skillGains = data
      })
    },
    loadLastLessons () {
      axios.get('/statistics/last-lessons', {
        params: {
          user_id: this.id
        }
      }).then(({ data }) => {
        this.lastLessons = data
      })
    },
    loadUserInfo () {
      axios.get(`/users/${this.id}`).then(({ data }) => {
        this.user = data
      })
    },
    loadUserSkills () {
      axios.get('/statistics/skills', {
        params: {
          user_id: this.id
        }
      }).then(({ data }) => {
        this.skills = data
      })
    }
  }
}
</script>

<style scoped>
.table{
  text-align: left !important;
}
.profile-page{
  padding-bottom: 2rem;
}
a{
  color: black !important;
}
</style>
