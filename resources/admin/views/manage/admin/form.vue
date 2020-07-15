<template>
  <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible" width="530px">
    <el-form ref="dataForm" :rules="rules" :model="temp" label-position="left" label-width="70px" style="width: 100%; height: 40vh;padding:0 40px;min-height: 420px;">
      <el-form-item label="角色" prop="role_id">
        <el-select v-model="temp.role_id" class="filter-item" placeholder="请选择">
          <el-option v-for="item in roleList" :key="item.id" :label="item.name" :value="item.id" />
        </el-select>
      </el-form-item>
      <el-form-item label="账号" prop="account">
        <el-input v-model="temp.account" clearable/>
      </el-form-item>
      <el-form-item label="密码" prop="password">
        <el-input v-model="temp.password" placeholder="不填默认为:123456" clearable/>
      </el-form-item>
      <el-form-item label="头像" prop="avatar">
        <Upload v-model="temp.avatar" :config="config"/>
      </el-form-item>
      <el-form-item label="昵称" prop="nickname">
        <el-input v-model="temp.nickname" clearable/>
      </el-form-item>
	  <!--
      <el-form-item label="手机" prop="phone">
        <el-input v-model="temp.phone" clearable/>
      </el-form-item>
      <el-form-item label="邮箱" prop="email">
        <el-input v-model="temp.email" clearable/>
      </el-form-item>
	  -->
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
import Upload from '@/components/Upload/image'
import { formatImgToArr, getNowTime } from '@/utils'
import { validatePhone, validateEmail } from '@/utils/validate'
import myconfig from '@/config'

export default {
  name: 'AdminForm',
  components: { Upload },
  data() {
    var checkPhone = (rule, value, callback) => {
      if (validatePhone(value)) {
        callback()
      } else {
        return callback(new Error())
      }
    }
    var checkEmail = (rule, value, callback) => {
      if (validateEmail(value)) {
        callback()
      } else {
        return callback(new Error())
      }
    }
	var checkPasswd = (rule, value, callback) => {
      if (value.length>6) {
        callback()
      } else if(value.length<=6&&value.length>0) {
        return callback(new Error('密码不能少于六个字符'))
      }else{
		 callback()
	  }
    }
    return {
      btnLoading: false,
      roleList: [],
      temp: {
        id: 0,
        role_id: '',
        account: '',
        password: '',
        nickname: '',
        status: 1,
        phone: '',
        email: '',
        regTime: getNowTime(),
        avatar: []
      },
      config: {
        fileName: 'img',
        limit: 1,
        multiple: true,
        accept: 'image/*',
        action: myconfig.uploadUrl.img
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: '编辑',
        create: '添加'
      },
      rules: {
        role_id: [{ required: true, message: '角色必选', trigger: 'change' }],
        account: [{ required: true, message: '账号必填', trigger: 'blur' }],
        password: [{ validator: checkPasswd, message: '', trigger: 'blur' }],
      }

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
    getRoles() {
      this.$request({
      		url: '/api/system/roleList',
      		method: 'post',
      		data:{status:1,page:1,pageSize:10000}
      }).then(response => {
        this.roleList = response.data.list
      })
    },
    resetTemp() {
      this.temp = {
        id: 0,
        role_id: '',
        account: '',
        password: '',
        nickname: '',
        status: 1,
        regTime: getNowTime(),
        avatar: []
      }
    },
    handleCreate() {
      this.dialogStatus = 'create'
      this.dialogFormVisible = true
      this.currentIndex = -1
	  this.getRoles()
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    handleUpdate(id) {
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
      const _this = this
	   this.getRoles()
      this.$request({
      		url: '/api/system/getUser',
      		method: 'post',
      		data:{id}
      }).then(response => {
        if (response.code === 1) {
          _this.temp.id = response.data.detail.id
          _this.temp.role_id = response.data.detail.role_id
          _this.temp.account = response.data.detail.account
          _this.temp.nickname = response.data.detail.nickname
          _this.temp.status = response.data.detail.status
          _this.temp.password = ''
          _this.temp.avatar = formatImgToArr(response.data.detail.avatar)
        }
      })
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    saveData() {
      
	  let url = '/api/system/addUser'
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
			this.btnLoading = true
          const _this = this
          const d = this.temp
          if (typeof (d.avatar) === 'object') {
            d.avatar = d.avatar.join(',')
          }
		  if(d.id>0){
			  url = '/api/system/editUser'
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
              // todo
              this.$emit('updateRow', d)
              _this.dialogFormVisible = false
			  if(d.id){
				  _this.$message.success('修改成功')
			  }else{
				  _this.$message.success('添加成功')
			  }
              
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
<style lang="scss">
	.upload-container .el-upload{
		width: 110px !important;
		height: 110px !important;
		line-height: 115px;
		
	}
	.el-input{
		max-width: 80%;
	}
	.image-uploader .el-upload-list__item{
		width: 110px;
		height: 110px;
	}
</style>
