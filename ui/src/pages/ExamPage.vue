<template>
  <div class="exam-page">
    <div class= "row courses" style="padding-left: 37rem; padding-top: 3rem; ">
      <div>
        <h2>İmtahan</h2>
      </div>
    </div>
    <div class="row exam-question"
         v-for="(question, index) in questions"
         v-bind:key="index" >
      <div class="col-sm-8 offset-2">
        <div class="card">
          <div class="card-body">
            <h6 class="card-title">Sual #{{ index + 1 }}</h6>
            <p class="card-text">{{ question.question }}?</p>
            <!-- Group of default radios - option 1 -->
            <div class="custom-control custom-radio"
                 v-for="(a, i) in question.answers"
                 v-bind:key="i">
              <input type="radio"
                     class="custom-control-input"
                     v-model="questions[index].selected"
                     :value="i"
                     :id="`defaultGroupExample${i}/${index}`"
                     :name="`groupOfDefaultRadios/${index}`">
              <label class="custom-control-label" :for="`defaultGroupExample${i}/${index}`">{{ a.answer }}</label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="exam-exam">
      <div class="submit-btn" style="text-align: center; padding-top: 1rem;">
        <button type="button"
                @click="endExam"
                class="btn btn-primary" >Bitir</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import { shuffleQuestions, convertQuestions } from '../questions'
export default {
  name: 'ExamPage',
  data () {
    return {
      exam: {},
      questions: [],
      answers: {}
    }
  },
  computed: {
    id () {
      return this.$route.params.id
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
      axios.get(`/exams/${id || this.id}`).then(({ data }) => {
        this.exam = data
        this.questions = shuffleQuestions(convertQuestions(data.questions))
      })
    },
    endExam () {
      if (!confirm('Əminsiniz?')) {
        return
      }
      let correctAnswers = this.questions.filter(q => q.answers[q.selected].correct)
      let score = correctAnswers.length / this.questions.length * 100
      if (score >= 90) {
        this.passExam(score)
      } else {
        this.failExam(score)
      }
      this.saveResults(score)
    },
    passExam (score) {
      alert(`Təbriklər! Siz ${score}% topladız`)
    },
    failExam (score) {
      alert(`Təəsüfki siz ${score}% topladız və imtahanı keçə bilmədiz`)
    },
    saveResults (score) {
      axios.post(`exams/${this.id}`, {
        score: score
      }).then(() => {
        this.$router.push({name: 'ActualCourse', params: {id: this.exam.course_id}})
      })
    }
  }
}
</script>

<style scoped>
.exam-question{
  text-align: left;
}
</style>
