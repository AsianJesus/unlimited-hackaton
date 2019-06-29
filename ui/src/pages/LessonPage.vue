<template>
  <div class="lesson-page">
    <div class= "row courses" style="padding-left: 37rem; padding-top: 3rem; ">
      <div>
        <h2>{{ lesson.name }}</h2>
      </div>
    </div>
    <div style="text-align: right;"
         v-if="lesson.type === 0">
      <b-button size="sm"
                variant="outline-success"
                @click="downloadPDF" >
        <font-awesome-icon :icon="saveIcon" />
      </b-button>
    </div>
    <div   style = "text-align: center !important;">
      <iframe v-if="lesson.type === 1"
              width="560" height="315"
              :src="lesson.link"
              frameborder="0"
              allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen />
      <div style="padding-right: 32rem; font-weight: bold; font-size: 1.1rem;">Eta: {{ lesson.required_time }} </div>
    </div>
    <div class="content row" >
      <p class="col-9 offset-1"
         ref="content"
         style="text-align: justify;">
        {{ lesson.content }}
      </p>
      <a target="_blank"
          href="#"
          ref="downloader"></a>
    </div>
    <div v-if="canMarkRead">
      <b-button variant="outline-primary"
                @click="markAsRead" >
        Mark as read
      </b-button>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { faSave } from '@fortawesome/free-solid-svg-icons'
import { serverURL } from '../config'

export default {
  name: 'LessonPage',
  data () {
    return {
      lesson: {}
    }
  },
  computed: {
    id () {
      return this.$route.params.id
    },
    saveIcon: () => faSave,
    canMarkRead () {
      return this.$store.state.isLogged && !this.lesson.my_passes_count
    }
  },
  mounted () {
    this.load()
  },
  watch: {
    id (val) {
      this.load(val)
    }
  },
  methods: {
    load (id) {
      axios.get(`/lessons/${id || this.id}`).then(({ data }) => {
        this.lesson = data
      })
    },
    downloadPDF () {
      if (this.lesson.type === 0) {
        this.$refs.downloader.href = `${serverURL}/../${this.lesson.link}`
        this.$refs.downloader.click()
      }
    },
    markAsRead () {
      axios.post(`/lessons/${this.id}`).then(() => {
        this.$router.push({name: 'ActualCourse', params: {id: this.lesson.course_id}})
      })
    }
  }
}
</script>

<style scoped>

</style>
