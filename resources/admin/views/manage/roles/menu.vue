<template>
	<el-dialog title="权限设置" :visible.sync="dialogFormVisible" width="750px" class="yt-role-setting" >
		<div class="ctx">
			<el-card body-style="padding: 20px,min-height: 150px"  v-loading="loading">
			<div slot="header">
						<span class="title">权限列表</span>
			</div>
			 <el-form ref="form" label-width>
						<el-form-item label="是否管理员">
						  <el-switch v-model="is_admin" active-value="1" inactive-value="0"></el-switch>
						</el-form-item>
			</el-form>
			<el-alert v-if="is_admin==1" show-icon title="管理员" type="success">管理员拥有所有权限</el-alert>
			<el-scrollbar wrap-class="auth" v-else-if="is_admin!=1">
				<el-card
				  class="mb"
				  :body-style="{ padding: '14px' }"
				  v-for="(item, index) in menuList"
				  :key="index"
				  
				>
				  <div slot="header" class="grayBackgroundColor">
					<el-checkbox v-model="item.check"  @change="change(item,index)">{{item.title}}</el-checkbox>
					<div class="right">
						<i :class="[item.hidden==1 ?'el-icon-plus':'el-icon-close']" @click="folded(index)"></i>
					</div>
				  </div>
				  <div v-for="(item_1, index) in item.children" :key="index"  :class="[item.hidden==1? 'close' : 'open', 'pageFloor']">
					<div class="pageFloorCtrl">
					  <el-checkbox v-model="item_1.check" @change="change(item_1,index)">{{index+1}}.{{item_1.title}}</el-checkbox>
					</div>
					<div class="btnFloor" v-if="item_1.button && item_1.button.length>0">
					  <el-checkbox
						v-for="(e,$index) in item_1.button"
						:key="$index"
						v-model="e.check"
						@change="change(e,$index)"
					  >{{e.title}}</el-checkbox>
					</div>
				  </div>
				  </el-card>
				  </el-scrollbar>
				</el-card>
		</div>

	  <div slot="footer" class="dialog-footer">
	    <el-button @click="dialogFormVisible = false">取消</el-button>
	    <el-button :loading="btnLoading" type="primary" @click="saveData()">保存</el-button>
	  </div>
    </el-dialog>
</template>

<script>
import tree from '@/utils/tree'
export default {
  name: 'menuForm',
  data() {
	return {
	  btnLoading: false,
	  dialogFormVisible:false,
	  loading: false,
	  menuList:[],
	  is_admin:0,
	  p_list:[],
	  id:0,
	  }
  },
  computed: {

  },
  created() {
    this.getRules()
  },
  methods: {
	  getRules() {
	   const _this = this;
	   this.$request({
	   		url: '/api/system/menuList',
	   		method: 'post',
	   		data:{page:1,pageSize:10000,status:1,is_show:1}
	   }).then(response => {
		   if(response.code==1){
			_this.menuList = response.data.list
		  }
	    })
	  },
	  handleCreate(id) {
	    this.dialogFormVisible = true;
		this.id = id;
		const _this = this;
		this.$request({
				url: '/api/system/getRolePermission',
				method: 'post',
				data:{id}
		}).then(response => {
		   if(response.code==1){
			   _this.is_admin = response.data.is_admin+'';
			   let list = [];
			   response.data.list.forEach(item => {
				   list.push(item.menu_id)
			   });
			   _this.p_list = list;
			   _this.checkHandle(true);
			   console.log( _this.is_admin)
			   _this.menuList = tree.listToTreeMulti(_this.menuList);
		   }
		 });

	  },
	  folded(index){
		 if(this.menuList[index].hidden){
			 this.$set(this.menuList[index],"hidden",0)
		 }else{
			this.$set(this.menuList[index],"hidden",1)
		 }
		  console.log(this.menuList[index])
	  },
	  change(cur,index){
		 if(cur.check){
			 this.p_list.push(cur.id)
		 }else{
			 this.p_list.splice(this.p_list.indexOf(cur.id),1)
		 }
		  console.log(this.p_list);
	  },
	  saveData(){
		  if(this.is_admin!=1&&!this.p_list.length){
			   this.$notify.error('请勾选权限');
			   return;
		  }
		  let _this = this;
		  this.$request({
		  		url: '/api/system/savePermission',
		  		method: 'post',
		  		data:{id:_this.id,is_admin:_this.is_admin,p_list:_this.p_list}
		  }).then(response => {
		     if(response.code==1){
				_this.$message.success('保存成功')
		     }else{
				_this.$notify.error(response.msg)
			 }
		   });
	  },
	  checkHandle(reset){
		  if(this.menuList.length>0){
			  for(let i=0,len=this.menuList.length;i<len;i++){
				  if(this.menuList[i].parent_id==0){
					  if(reset||this.menuList.length<3){
						  this.$set( this.menuList[i],"hidden",0)
					  }else{
						  this.$set( this.menuList[i],"hidden",1)
					  }
					   
				  }
				if(this.p_list.length>0&&this.p_list.indexOf(this.menuList[i].id)!=-1){
					  this.$set( this.menuList[i],"check",true)
					  
				  }else{
					  this.$set( this.menuList[i],"check",false)
				  }
				  if(this.menuList[i].button&&this.menuList[i].button.length>0){
					  for(let j=0,len=this.menuList[i].button.length;j<len;j++){
						  if(this.p_list.length>0&&this.p_list.indexOf(this.menuList[i].button[j].id)!=-1){
							  this.$set( this.menuList[i].button[j],"check",true)
							  
						  }else{
							  this.$set( this.menuList[i].button[j],"check",false)
						  }
					  }
				  }
			  }
		  }
		 
	  }
  }
}
</script>

<style lang="scss" scoped>
.yt-role-setting {
	max-height: calc(100% - 60px);
  .ctx {
    
    /deep/ .auth {
      max-height: calc(100vh - 510px);
    }
    .mb {
      /deep/ .el-card__header {
        background: #f5f5f5;
      }
    }
	.close{
		display:none;
	}
	.open{
		display: block;
	}
	
    .pageFloorCtrl {
      padding: 20px 14px 20px;
      border-bottom: 1px solid #eee;
      box-shadow: 0 10px 10px -6px rgba(0, 0, 0, 0.05);
    }
    .btnFloor {
      background: #fefefe;
      padding: 20px 28px 20px;
      .el-checkbox {
        margin-bottom: 8px;
      }
    }
	.grayBackgroundColor{
		display: flex;
		.el-checkbox{
			width: calc(100% - 20px);
		}
	}
  }
}
</style>
