<template>
  <div class="speciality-page row">
      <b-nav vertical style="text-align: left;">
        <b-nav-item :to="{ name: 'Field', params: {name: field.name} }"
                    style="font-size: 1.2rem"
        > {{ field.name }} </b-nav-item>
        <!--<b-nav-item :to="{ name: 'Field', params: {name: name} }">Top</b-nav-item>-->
        <b-nav-item v-for="(speciality, index) in specialities"
                    v-bind:key="index"
                    :to="{ name: 'Speciality', params: {name: speciality.name} }">{{ speciality.name }}</b-nav-item>
      </b-nav>
    <div class="col">
      <div>
        <h2 style="text-align: center;">{{ name }}</h2>
      </div>
      <div class="tab-content col" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
          <div class ="row spec">
            <div class="col-sm-6"
                 v-for="(course, index) in courses"
                 v-bind:key="index">
              <div class="card course-card">
                <div class="card-body">
                  <h4 class="card-title">{{ course.name }}</h4>
                  <div class="card-text">
                    <div> Difficulty: {{ getDifficulty(course.difficulty )}} </div>
                    <!--<div> Level: {{ course.level }} </div>-->
                    <div> Lessons count: {{ course.lessons_count }} </div>
                  </div>
                  <div style="text-align: right;">
                    <router-link :to="{ name: 'Course', params: { id: course.id }}"
                                 class="btn btn-primary" >
                      View course
                    </router-link>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import difficulties from '../difficulties.json'
export default {
  name: 'SpecialityPage',
  data () {
    return {
      field: {},
      speciality: {}
    }
  },
  computed: {
    name () {
      return this.$route.params.name
    },
    specialities () {
      return this.field.specialities || []
    },
    courses () {
      return this.speciality.courses || []
    }
  },
  mounted () {
    this.loadSpeciality()
  },
  watch: {
    name (val) {
      this.loadSpeciality(val)
    }
  },
  methods: {
    loadSpeciality (name) {
      axios.get(`/specialities/${name || this.name}`).then(response => {
        this.speciality = response.data
        this.field = response.data.field
      })
    },
    getDifficulty (diff) {
      return difficulties[diff]
    }
  }
}
</script>

<style scoped>
.course-card {
  text-align: left;
  margin: 1rem auto;
}
</style>
