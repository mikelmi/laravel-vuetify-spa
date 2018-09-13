<template>
  <v-footer :fixed="fixed" app>
    <div class="pl-3" v-html="text"></div>
  </v-footer>
</template>

<script>
export default {
  data () {
    return {
      fixed: false
    }
  },
    computed: {
      text() {
          return '&copy;' + (new Date()).getFullYear();
      }
    }
}
</script>
