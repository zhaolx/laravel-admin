<template>
  <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible" width="450px">
    <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="70px" style="width: 100%; min-height: 150px;">
      <el-form-item label="名称" prop="name">
        <el-input v-model="temp.name" clearable/>
      </el-form-item>
	  <el-form-item label="描述">
	      <el-input type="textarea" v-model="temp.description"></el-input>
	    </el-form-item>
      <el-form-item label="状态">
        <el-radio-group v-model="temp.status">
          <el-radio :label="1">正常</el-radio>
          <el-radio :label="0">禁用</el-radio>
        </el-radio-group>
      </el-form-item>
    </el-form>
    <div slot="footer" class="dialog-footer">
      <el-button @click="dialogFormVisible = false">取消</el-button>
      <el-button :loading="btnLoading" type="primary" @click="saveData()">保存</el-button>
    </div>
  </el-dialog>
</template>

<script>

export default {
  name: 'RolesForm',
  data() {
    return {
      btnLoading: false,
      ruleList: [],
      temp: {
        id: 0,
        name: '',
        description: '',
        status: 1,
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: '编辑',
        create: '添加'
      },
      rules: {
        name: [{ required: true, message: '名称必填', trigger: 'blur' }]
      },
      defaultChecked: [],
      defaultProps: {
        children: 'children',
        label: 'title'
      }

    }
  },
  computed: {
    getRulesList() {
      return tree.listToTreeMulti(this.ruleList)
    }
  },
  watch: {
    dialogFormVisible: function() {
      this.resetTemp()
    }
  },
  created() {
    //this.getRules()
  },
  destroyed() {
  },
  methods: {
    getRules() {
     this.$request({
     		url: '/api/system/roleList',
     		method: 'post',
     		data:{page:1,pageSize:10000}
     }).then(response => {
        this.ruleList = response.data.list
      })
    },
    resetTemp() {
      this.temp = {
        id: 0,
        name: '',
        status: 1,
        description: ''
      }
    },
    checkHandle(data) {
      const halfCheckedKeys = this.$refs.tree.getHalfCheckedKeys().join(',')
      const checkedKeys = this.$refs.tree.getCheckedKeys().join(',')
      if (halfCheckedKeys.length && checkedKeys.length) {
        this.temp.rules = halfCheckedKeys + ',' + checkedKeys
      } else if (halfCheckedKeys.length && checkedKeys.length === 0) {
        this.temp.rules = halfCheckedKeys
      } else if (halfCheckedKeys.length === 0 && checkedKeys.length) {
        this.temp.rules = checkedKeys
      } else {
        this.temp.rules = ''
      }
    },
    handleCreate() {
      this.dialogStatus = 'create'
      this.dialogFormVisible = true
      this.currentIndex = -1
      this.$refs.tree.setCheckedKeys([])
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    handleUpdate(id) {
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
      const _this = this
      this.$request({
      		url: '/api/system/getRole',
      		method: 'post',
      		data:{id}
      }).then(response => {
        if (response.code === 1) {
			let detail = response.data.detail
			console.log(detail)
          _this.temp.id = detail.id
          _this.temp.name = detail.name
          _this.temp.description = detail.description
          _this.temp.status = detail.status
        }
      })
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    saveData() {
      this.btnLoading = true
	  let url = '/api/system/addRoles'
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          const _this = this
          const d = this.temp
		  if(d.id>0){
			  url = '/api/system/editRole'
		  }
          this.$request({
          		url: url,
          		method: 'post',
          		data:d
          }).then(response => {
            if (response.code === 1) {
              if (!d.id) {
                d.id = response.data.id
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
    }
  }
}
</script>
