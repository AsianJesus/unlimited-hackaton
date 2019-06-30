<template>
  <div class="field-page row">
    <b-nav vertical style="text-align: left;" >
      <b-nav-item :to="{ name: 'Field', params: {name: name} }" style="font-size: 1.2rem;"> {{ name }} </b-nav-item>
      <!--<b-nav-item :to="{ name: 'Field', params: {name: name} }">Top</b-nav-item>-->
      <b-nav-item v-for="(speciality, index) in specialities"
                  v-bind:key="index"
          :to="{ name: 'Speciality', params: {name: speciality.name} }">{{ speciality.name }}</b-nav-item>
      <!--<b-nav-item-dropdown text="Specialities"
                           dropright>
        <b-dropdown-item  v-for="(speciality, index) in specialities"
                          v-bind:key="index"
                          >
          {{ speciality.name }}
        </b-dropdown-item>
      </b-nav-item-dropdown>-->
    </b-nav>
    <div class="col">
      <div>
        <h2 style="text-align: center;">{{ name }}</h2>
      </div>
      <div class="tab-content col" id="v-pills-tabContent">
        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
          {{ field.description }}
          <!--<table class="table table-striped">
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
            <tr>
              <th scope="row">1</th>
              <td>Mark</td>
              <td>Otto</td>
              <td>7</td>
              <td>4</td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td>Jacob</td>
              <td>Thornton</td>
              <td>5</td>
              <td>6</td>
            </tr>
            <tr>
              <th scope="row">3</th>
              <td >Larry</td>
              <td >Green</td>
              <td>3</td>
              <td>3</td>
            </tr>
            </tbody>
          </table>-->
        </div>
        <hr>
        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab"></div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'FieldPage',
  data () {
    return {
      field: {}
    }
  },
  computed: {
    specialities () {
      return this.field.specialities || []
    },
    name () {
      return this.$route.params.name
    }
  },
  watch: {
    name (val) {
      this.loadFieldInfo(val)
    }
  },
  mounted () {
    this.loadFieldInfo()
  },
  methods: {
    loadFieldInfo (name) {
      axios.get(`/fields/${name || this.name}`).then(response => {
        this.field = response.data
      })
    }
  }
}
</script>

<style scoped>

</style>
