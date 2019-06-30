<template>
  <div class="lesson-component" :class="{ 'lesson-passed': success }">
    <div class="lesson-name">
      {{ name }}
    </div>
    <transition name="lesson">
      <div class="lesson-body"
           v-if="isExpanded" >
        {{ description }}
        <hr>
        <div class="lesson-eta">
          ETA: {{ eta }} minutes
        </div>
        <div>
          <slot name="buttons" />
        </div>
      </div>
    </transition>
    <div class="lesson-expand-holder"
         @click="toggleExpansion" >
      <transition name="expand-button">
        <font-awesome-icon :class="{
                    'button-to-expand': !isExpanded,
                    'button-to-hide': isExpanded
                  }"
                           :icon="iconCaret" />
      </transition>
    </div>
  </div>
</template>

<script>
import { faCaretDown } from '@fortawesome/free-solid-svg-icons'
export default {
  name: 'LessonComponent',
  props: {
    value: {
      type: Object,
      required: true
    },
    customButtons: {
      type: Boolean,
      default: false
    },
    success: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      isExpanded: false
    }
  },
  computed: {
    name () {
      return this.value.name
    },
    iconCaret: () => faCaretDown,
    eta () {
      return this.value.required_time || 0
    },
    description () {
      return this.value.content ? this.value.content.substring(0, 300) + '...' : ''
    }
  },
  methods: {
    toggleExpansion () {
      this.isExpanded = !this.isExpanded
    }
  }
}
</script>

<style scoped>
.lesson-component{
  border: 1px solid #00000040;
  text-align: left;
}
.lesson-passed {
  background-color: #95b994;
}
.lesson-name, .lesson-body{
  padding: .5rem 1rem;
}
.lesson-expand-holder{
  text-align: center;
  cursor: pointer;
  border-top: 1px solid #00000040;
}
.lesson-name{
  font-size: 1.1rem;
}
.lesson-body{
  border-top: 1px solid #00000040;
}
.button-to-hide{
  transform: rotate(180deg);
}
.button-to-expand{
  transform: rotate(0deg);
}
.expand-button-enter-active{
  animation: rotate .5s linear;
}
.expand-button-leave-active{
  animation: rotate .5s linear reverse;
}
.lesson-enter-active{
  animation: shrink .3s ease;
}
.lesson-leave-active{
  animation: shrink .3s ease reverse;
}
@keyframes rotate {
  0% {
    transform: rotate(0deg);
  }
  50% {
    transform: rotate(90deg);
  }
  100% {
    transform: rotate(180deg);
  }
}
@keyframes shrink {
  0% {
    transform: translateY(-50%) scaleY(0);
  }
  100% {
    transform: translateY(0) scaleY(1);
  }
}
</style>
