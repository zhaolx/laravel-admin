<template>
	 <el-dialog title="选择图标" :visible.sync="dialogFormVisible" width="400px" append-to-body>
		 <div class="icons-container">
		<div class="icons-wrapper">
		  <div v-for="item of iconsMap" :key="item" @click="selectIcon(item)">
			<el-tooltip placement="top">
			  <div slot="content">
				{{item}}
			  </div>
			  <div class="icon-item">
				<svg-icon :icon-class="item" class-name="disabled" />
				<!--
				<span>{{ item }}</span>
				-->
			  </div>
			</el-tooltip>
		  </div>
		</div>
		</div>
	 </el-dialog>
</template>

<script>
	import icons from './requireIcons'
	import clipboard from '@/utils/clipboard'
	export default {
	  name: 'Icons',
	  data() {
	    return {
	      iconsMap: icons,
		  dialogFormVisible:false,
	    }
	  },
	  methods: {
		init() {
			this.dialogFormVisible = true;
		 },
	    generateIconCode(symbol) {
	      return `<svg-icon icon-class="${symbol}" />`
	    },
	    handleClipboard(text, event) {
	      clipboard(text, event)
	    },
		selectIcon(val){
			this.$emit('selectIcon',val);
			this.dialogFormVisible = false;
		}
	  }
	}
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
	.icons-container {
	  margin: 10px 20px 0;
	  overflow: hidden;
	  .icons-wrapper {
	    margin: 0 auto;
	  }
	  .icon-item {
	    margin: 5px;
	    height: 26px;
	    text-align: center;
	    width: 26px;
	    float: left;
	    font-size: 16px;
	    color: #24292e;
	    cursor: pointer;
	  }
	  span {
	    display: block;
	    font-size: 14px;
	    margin-top: 10px;
	  }
	  .disabled{
	    pointer-events: none;
	  }
	}
</style>
