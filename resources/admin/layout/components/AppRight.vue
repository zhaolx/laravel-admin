<template>
  <div :class="{'hidden':!show}" class="app-right">
    <el-card shadow="always" body-style="{padding:'10px'}">
      <div class="header clearfix">
        <span v-text="title"/>
        <el-button style="float: right; padding: 3px 0" type="text" @click="setRightBar">关闭</el-button>
      </div>
      <div class="content">
        <slot/>
      </div>
    </el-card>

  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'AppRight',
  props: {
    show: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
    }
  },
  computed: {
    ...mapGetters([
      'rihgtbar'
    ])
  },
  methods: {
    setRightBar() {
      this.$store.dispatch('setRightBar', 0)
    }
  }
}
</script>

<style scoped>
.app-right {
  /*84 = navbar + tags-view = 50 +34 */
  min-height: calc(100vh - 84px);
  width: 50%;
  background-color:aliceblue;
  position: absolute;
  right: 0;
  top: 84px;
  z-index: 999;
  overflow: auto;
}
.header{
  border-bottom: 1px dotted #409EFF;
}
.clearfix:before,
  .clearfix:after {
    display: table;
    content: "";
  }
  .clearfix:after {
    clear: both
  }
.hidden{
  display: none
}

</style>

