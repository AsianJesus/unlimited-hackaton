<template>
  <div class="profile-page">
    <div class="row">
      <profile-navbar />
      <div class="col-8 graph">
        <!-- Insert chart here -->
        <line-chart v-if="datacollection"
                    ref="chart"
                    v-bind:chart-data="datacollection"
                    :options="chartOptions" />
        <div style="text-align: right; margin-top: 1rem;">
          <b-dropdown text="İntervalı seç"
                      variant="success">
            <b-dropdown-item @click="loadStatistics('w')">Həftə</b-dropdown-item>
            <b-dropdown-item @click="loadStatistics('m')">Ay</b-dropdown-item>
          </b-dropdown>
        </div>
        <div class="row">
          <div class="col-3 prg" >
            <p>Davamiyyət: {{ weekInfo.streak }} həftə</p>
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
        <div class="row" style="margin: 2rem auto;">
          <div class="col-6">
            <h4>
              Bacarıqların inkişafı
            </h4>
            <table class="table table-striped">
              <thead>
              <tr>
                <th scope="col">Ad</th>
                <th scope="col">Dərəcə</th>
                <th scope="col">Tarix</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(sg, index) in skillGains"
                  v-bind:key="index">
                <th scope="row">{{ sg.skill.name }}</th>
                <td>1</td>
                <td>{{ sg.date }}</td>
              </tr>
              <tr v-if="!skillGains.length">
                <th colspan="3" style="text-align: center;">Yoxdur</th>
              </tr>
              </tbody>
            </table></div>
          <div class="col-6">
            <h4>Bacarıqlar</h4>
            <table class="table table-striped">
              <thead>
              <tr>
                <th scope="col">Ad</th>
                <th scope="col">Dərəcə</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(skill, index) in skills"
                  v-bind:key="index" >
                <th scope="row">{{ skill.skill.name }}</th>
                <td>{{ skill.level }}</td>
              </tr>
              <tr v-if="!skills.length">
                <th colspan="3" style="text-align: center;">Yoxdur</th>
              </tr>
              </tbody>
            </table></div>
        </div>
        <h4>
          Sonuncu dərslər
        </h4>
        <table class="table table-striped" >
          <thead>
          <tr>
            <th scope="col">Kurs</th>
            <th>Dərs</th>
            <th scope="col">Tarix</th>
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
              Yoxdur
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
  name: 'ProfilePage',
  components: {
    LineChart,
    ProfileNavbar
  },
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
  mounted () {
    this.loadStatistics()
    this.loadWeekInformation()
    this.loadSkillsGain()
    this.loadLastLessons()
    this.loadUserSkills()
    this.loadUserInfo()
  },
  methods: {
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
      axios.get('/statistics/week').then(({ data }) => {
        this.weekInfo = data.info || {}
        this.weekInfo.required = data.required
        this.weekInfo.streak = data.user.week_streak
      })
    },
    loadSkillsGain () {
      axios.get('/statistics/skill-gain').then(({ data }) => {
        this.skillGains = data
      })
    },
    loadLastLessons () {
      axios.get('/statistics/last-lessons').then(({ data }) => {
        this.lastLessons = data
      })
    },
    loadUserInfo () {
      axios.get('/user').then(({ data }) => {
        this.user = data
      })
    },
    loadUserSkills () {
      axios.get('/statistics/skills').then(({ data }) => {
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
