<template>
  <div class="app-container">
    <el-form ref="dataForm" :model="temp" label-position="left" label-width="70px" style="width: 100%; ">
      <el-form-item label="角色" prop="groupId">
        <el-input v-model="group" :disabled="true"/>
      </el-form-item>
      <el-form-item label="账号" prop="userName">
        <el-input v-model="name" :disabled="true"/>
      </el-form-item>
      <el-form-item label="密码" prop="password">
        <el-input v-model="temp.password" clearable placeholder="不修改，则留空"/>
      </el-form-item>
      <el-form-item label="头像" prop="img">
        <Upload v-model="temp.img" :config="config"/>
      </el-form-item>
      <el-form-item label="姓名" prop="realName">
        <el-input v-model="temp.realName" clearable/>
      </el-form-item>
      <el-form-item label="手机" prop="phone">
        <el-input v-model="temp.phone" clearable/>
      </el-form-item>
      <el-form-item label="邮箱" prop="email">
        <el-input v-model="temp.email" clearable/>
      </el-form-item>
    </el-form>
    <el-button :loading="btnLoading" type="primary" @click="saveData()">保存</el-button>
  </div>
</template>

<script>
import Upload from '@/components/Upload/image'
import { modify } from '@/api/admin'
import { mapGetters } from 'vuex'
import store from '@/store'
import myconfig from '@/config'

export default {
  name: 'MyInfo',
  components: { Upload },
  data() {
    return {
      btnLoading: false,
      temp: {
        password: '',
        realName: store.getters.realName,
        phone: store.getters.phone,
        email: store.getters.email,
        img: [store.getters.avatar]
      },
      config: {
        fileName: 'img',
        limit: 1,
        multiple: true,
        accept: 'image/*',
        action: myconfig.uploadUrl.img
      }

    }
  },
  computed: {
    ...mapGetters([
      'name',
      'group'
    ])
  },
  watch: {
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
    saveData() {
      this.btnLoading = true
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          const _this = this
          const d = this.temp
          if (typeof (d.img) === 'object') {
            d.img = d.img.join(',')
          }
          modify(d).then(response => {
            if (response.status === 1) {
              store.commit('SET_AVATAR', _this.temp.img)
              store.commit('SET_REALNAME', _this.temp.realName)
              store.commit('SET_PHONE', _this.temp.phone)
              store.commit('SET_EMAIL', _this.temp.email)
              _this.$message.success(response.msg)
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
