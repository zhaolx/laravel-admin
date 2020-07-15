<template>
  <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible" width="700px" height="500px">
    <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="80px" style="width: 100%; min-height: 230px;">
		<el-row>
		<el-col :span="11" >
		  <el-form-item label="类型" prop="type" >
			<el-radio-group v-model="temp.type" @change="changeType">
			  <el-radio :label="1">菜单</el-radio>
			  <el-radio :label="2">按钮</el-radio>
			</el-radio-group>
		  </el-form-item>
		</el-col>
		 </el-row>
		 <block v-if="temp.type==2">
			 <el-row>
			 	  <el-col :span="11" >
			 	    <el-form-item label="名称" prop="title">
			 	  	<el-input v-model="temp.title" clearable/>
			 	    </el-form-item>
			 	  </el-col>
			 	<el-col :span="11" class="form-right">
			 	  <el-form-item label="所属菜单" prop="parent_id" class="is-required11" required=true>
			 		<el-cascader v-model="parent_id" :options="getRulesList" :props="props_pid"  placeholder="请选择" change-on-select @change="handleChange"/>
			 	  </el-form-item>
			 	</el-col>
			    </el-row>
				<el-row>
				  <el-col :span="11" >
					<el-form-item label="标识" prop="name">
					  <el-input v-model="temp.name"  clearable/>
					</el-form-item>
				  </el-col>
				  <el-col :span="11" class="form-right">
					  <el-form-item label="后台路由" prop="route">
						<el-input v-model="temp.route" clearable/>
					  </el-form-item>
				   </el-col>
				</el-row>
				<el-row>
					<el-col :span="11" class="form-right">
					 <el-form-item label="显示">
					   <el-radio-group v-model="temp.is_show">
					     <el-radio :label="1">是</el-radio>
					     <el-radio :label="0">否</el-radio>
					   </el-radio-group>
					 </el-form-item>
					</el-col>
					<el-col :span="11" class="form-right">
					<el-form-item label="状态">
								<el-radio-group v-model="temp.status" >
								  <el-radio :label="1">正常</el-radio>
								  <el-radio :label="0">禁用</el-radio>
								</el-radio-group>
					</el-form-item>
					</el-col>
				</el-row>
		 </block>
		<block v-if="temp.type==1">
			<el-row>
				  <el-col :span="11" >
				    <el-form-item label="名称" prop="title">
				  	<el-input v-model="temp.title" clearable/>
				    </el-form-item>
				  </el-col>
				<el-col :span="11" class="form-right">
				  <el-form-item label="上级" prop="parent_id">
					<el-cascader v-model="parent_id" :options="getRulesList" :props="props_pid"  placeholder="请选择" change-on-select @change="handleChange"/>
				  </el-form-item>
				</el-col>
			   </el-row>
			   <el-row>
				  <el-col :span="11" >
					<el-form-item label="标识" prop="name">
					  <el-input v-model="temp.name"  clearable/>
					</el-form-item>
				  </el-col>
				  <el-col :span="11" class="form-right">
					  <el-form-item label="图标" prop="icon">
						<el-input v-model="temp.icon" @click.native="showIcon" clearable/>
					  </el-form-item>
				   </el-col>
				</el-row>
				<el-row>
					<el-col :span="11" >
					  <el-form-item label="后台路由" prop="route">
						<el-input v-model="temp.route" clearable/>
					  </el-form-item>
					</el-col>
				    <el-col :span="11" class="form-right">
					  <el-form-item label="前端路由" prop="path">
						<el-input v-model="temp.path" clearable/>
					  </el-form-item>
				   </el-col>
				</el-row>
				<el-row>
					<el-col :span="11" >
					  <el-form-item label="组件" prop="component">
						<el-input v-model="temp.component" clearable/>
					  </el-form-item>
					</el-col>
					<el-col :span="11" class="form-right">
					 <el-form-item label="显示">
					   <el-radio-group v-model="temp.is_show">
					     <el-radio :label="1">是</el-radio>
					     <el-radio :label="0">否</el-radio>
					   </el-radio-group>
					 </el-form-item>
					</el-col>
				</el-row>
				<el-row>
					<el-col :span="11" >
						<el-form-item label="缓存">
						  <el-radio-group v-model="temp.is_cache">
						    <el-radio :label="0">是</el-radio>
						    <el-radio :label="1">否</el-radio>
						  </el-radio-group>
						</el-form-item>
				 
				</el-col>
				<el-col :span="11" class="form-right">
				<el-form-item label="状态">
							<el-radio-group v-model="temp.status">
							  <el-radio :label="1">正常</el-radio>
							  <el-radio :label="0">禁用</el-radio>
							</el-radio-group>
				</el-form-item>
				</el-col>
			</el-row>
		</block>
      
    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button @click="dialogFormVisible = false">取消</el-button>
      <el-button :loading="btnLoading" type="primary" @click="saveData()">保存</el-button>
    </div>
	 <icons ref="icons"  @selectIcon="selectIcon" />
  </el-dialog>
 
</template>

<script>
import { getinfo, save } from '@/api/rules'
import tree from '@/utils/tree'
import icons from './icon'
export default {
  name: 'RulesForm',
  components: {icons},
  props: {
    ruleList: {
      type: Array,
      default: []
    }
  },
  data() {
	  
	const checkParent = (rule, value, callback) => {
		if(this.temp.type==2&&this.temp.parent_id<=0){
			return callback(new Error('所属菜单不能为空'));
		}
		callback();
	};
    return {
      btnLoading: false,

      ruleTop: [{ 'id': 0, 'title': '顶级' }],
      parent_id: [],
      props_pid: { 'label': 'title', 'value': 'id' },
      temp: {
        id: 0,
        parent_id: 0,
        title: '',
        name: '',
        status: 1,
		type:1,
        icon: '',
        path: '',
		route:'',
        component: '',
        is_show: 0,
        is_cache: 1,
        alwaysShow: 1,
        parent_id_1: 0,
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: '编辑',
        create: '添加'
      },
	  
      rules: {
        type: [{ required: true, message: '类型不能为空', trigger: 'blur' }],
        title: [{ required: true, message: '名称必填', trigger: 'blur' }],
        name: [{ required: true, message: '标识必填', trigger: 'blur' }],
        parent_id: [{ validator:checkParent ,trigger: 'blur',message: '所属菜单不能为空' }],
      }
    }
  },
  computed: {
    getRulesList() {
      return this.ruleTop.concat(tree.listToTreeMulti(this.ruleList))
    }
  },
  watch: {
    dialogFormVisible: function() {
      this.resetTemp()
    },
    temp: {
      handler(newVal, oldVal) {},
      immediate: true,
      deep: true
    }
  },
  created() {

  },
  destroyed() {

  },
  methods: {
    resetTemp() {
      this.temp = {
        id: 0,
        parent_id: 0,
        title: '',
        name: '',
        status: 1,
        icon: '',
        path: '/',
		type:1,
        component: 'layout/Layout',
        is_show: 0,
        is_cache: 0,
      }
    },
    handleCreate() {
      this.dialogStatus = 'create'
      this.dialogFormVisible = true
      this.currentIndex = -1
      this.parent_id = []
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
	showIcon(){
		this.$refs.icons.init();
	},
	selectIcon(val){
		this.temp.icon = val;
	},
    handleUpdate(id) {
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
      const _this = this
      this.$request({
      		url: '/api/system/getMenu',
      		method: 'post',
      		data:{id}
      }).then(response => {
        if (response.code === 1) {
			let menu = response.data.detail
          _this.temp.id = menu.id
          _this.temp.type = menu.type
          _this.temp.parent_id = menu.parent_id
          _this.temp.title = menu.title
          _this.temp.name = menu.name
          _this.temp.status = menu.status
          _this.temp.icon = menu.icon
          _this.temp.path = menu.path
          _this.temp.route = menu.route
          _this.temp.component = menu.component
          _this.temp.is_show = menu.is_show
          _this.temp.is_cache = menu.is_cache
		  if(menu.type==1){
			   _this.parent_id = tree.getParentsId(this.ruleList, id)
		  }else{
			   _this.parent_id = tree.getParentsId(this.ruleList, menu.parent_id)
		  }
         
        }
      })
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    saveData() {
	 let url='/api/system/addMenu';
      this.btnLoading = true
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          const _this = this
          const d = this.temp
		  if(d.id){
			   url = '/api/system/editMenu'
		  }
		  if(d.parent_id_1>0){
			  d.parent_id = d.parent_id_1;
		  }
          this.$request({
          		url: url,
          		method: 'post',
          		data: d
          }).then(response => {
            if (response.code === 1) {
              if (!d.id) {
                d.id2 = response.data.id
              }
              this.$emit('updateRow', d)
              _this.dialogFormVisible = false
              _this.$message.success('保存成功')
            } else {
              _this.$message.error(response.msg)
            }
            _this.btnLoading = false
          }).catch((error) => {
            this.btnLoading = false
          })
        } else {
          this.btnLoading = false
        }
      })
    },
    handleChange(value) {
      if (value.length) {
         this.temp.parent_id = value[value.length - 1];
      }
    },
	changeType(val){
		this.$refs['dataForm'].clearValidate()
	}
  }
}
</script>
<style lang="scss" scoped>
	.form-right{
		float: right;
		margin-right: 10px;
	}
</style>