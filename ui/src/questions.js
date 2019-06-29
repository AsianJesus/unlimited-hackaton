
function shuffleQuestions (questions) {
  let temp
  let i1 = 0
  let i2 = 0
  let count = questions.length * 2
  while (count > 0) {
    i1 = Math.floor(Math.random() * (questions.length - 1))
    i2 = Math.floor(Math.random() * (questions.length - 1))
    temp = questions[i1]
    questions[i1] = questions[i2]
    questions[i2] = temp
    questions[i1].answers = shuffleAnswers(questions[i1].answers)
    count--
  }
  return questions
}

function shuffleAnswers (answers) {
  let temp
  let i1 = 0
  let i2 = 0
  let count = answers.length * 2
  while (count > 0) {
    i1 = Math.floor(Math.random() * (answers.length - 1))
    i2 = Math.floor(Math.random() * (answers.length - 1))
    temp = answers[i1]
    answers[i1] = answers[i2]
    answers[i2] = temp
    count--
  }
  return answers
}

function convertQuestions (questions) {
  return questions.map(q => {
    return {
      question: q.question,
      difficulty: q.difficulty + 1,
      selected: null,
      isCorrect: null,
      answers: ['answer_1', 'answer_2', 'answer_3', 'answer_4'].map((a, i) => {
        return {
          answer: q[a],
          correct: (i + 1) === q.correct_answer
        }
      })
    }
  })
}

export { shuffleQuestions, convertQuestions }
