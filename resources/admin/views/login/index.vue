<template>
  <div class="login-container">
	<el-card class="box-card">
    <el-form ref="loginForm" :model="loginForm" :rules="loginRules" class="login-form" auto-complete="off" label-position="left">

      <div class="title-container">
       <el-tabs v-model="activeName" stretch="true">
           <el-tab-pane label="欢迎登录" name="login"></el-tab-pane>
           
         </el-tabs>
      </div>

      <el-form-item prop="username">
        <span class="svg-container">
          <svg-icon icon-class="user" />
        </span>
        <el-input
          v-model="loginForm.username"
          :placeholder="$t('login.username')"
          name="username"
          type="text"
          auto-complete="off"
        />
      </el-form-item>

      <el-form-item prop="password">
        <span class="svg-container">
          <svg-icon icon-class="password" />
        </span>
        <el-input
          :type="passwordType"
          v-model="loginForm.password"
          :placeholder="$t('login.password')"
          name="password"
          auto-complete="off"
          @keyup.enter.native="handleLogin" />
        <span class="show-pwd" @click="showPwd">
          <svg-icon :icon-class="passwordType === 'password' ? 'eye' : 'eye-open'" />
        </span>
      </el-form-item>
	   <el-form-item prop="status">
		<JcRange  status="loginForm.status" :successFun="onMpanelSuccess" :errorFun="onMpanelError"></JcRange>
		 </el-form-item>
      <el-button :loading="loading" type="primary" style="width:100%;margin:23px 0" @click.native.prevent="handleLogin">{{ $t('login.logIn') }}</el-button>
    </el-form>
	</el-card>
  </div>
</template>

<script>
import JcRange from "@/components/JcRange";
export default {
  name: 'Login',
  components: {
	  JcRange
  },
  data() {
    const validateUsername = (rule, value, callback) => {
      if (!value) {
        callback(new Error('请输入账号'))
      } else {
        callback()
      }
    }
    const validatePassword = (rule, value, callback) => {
      if (value.length < 6) {
        callback(new Error('密码不能少于6位'))
      } else {
        callback()
      }
    }
	const checkStatus = (rule, value, callback) => {
	      console.log(value);
	      if (!value) {
	         callback(new Error("请拖动滑块完成验证"));
	      } else {
	        callback();
	      }
	    };
    return {
      loginForm: {
        username: '',
        password: '',
		status:false
      },
      loginRules: {
        username: [{ required: true, trigger: 'blur', validator: validateUsername }],
        password: [{ required: true, trigger: 'blur', validator: validatePassword }],
		status: [{ required: true,validator: checkStatus, trigger: "change" }]
      },
      passwordType: 'password',
      loading: false,
      showDialog: false,
      redirect: undefined,
	  activeName:'login',
    }
  },
  watch: {
    $route: {
      handler: function(route) {
        this.redirect = route.query && route.query.redirect
      },
      immediate: true
    }

  },
  created() {

  },
  destroyed() {

  },
  methods: {
    showPwd() {
      if (this.passwordType === 'password') {
        this.passwordType = ''
      } else {
        this.passwordType = 'password'
      }
    },
	onMpanelSuccess(){
		this.loginForm.status = true;
	},
	onMpanelError(){
		this.loginForm.status = false;
	},
    handleLogin() {
      this.$refs.loginForm.validate(valid => {
        if (valid) {
          this.loading = true
          this.$store.dispatch('LoginByUsername', this.loginForm).then(() => {
            this.$router.push({ path: this.redirect || '/' })
          }).catch((error) => {
            this.loading = false
          })
        } else {
          console.log('请输入正确的账号和密码')
          return false
        }
      })
    }
  }
}
</script>

<style rel="stylesheet/scss" lang="scss">
  $bg:#283443;
  $light_gray:#eee;
  $cursor: #fff;

  @supports (-webkit-mask: none) and (not (cater-color: $cursor)) {
    .login-container .el-input input{
      color: $cursor;
      &::first-line {
        color: $light_gray;
      }
    }
  }

  /* reset element-ui css */
  .login-container {
    .el-input {
      display: inline-block;
      height: 35px;
      width: 85%;
	 
      input {
        background: transparent;
        border: 0px;
        -webkit-appearance: none;
        border-radius: 0px;
        padding: 12px 5px 12px 15px;
        color: $light_gray;
        height: 35px;
        caret-color: #656363;
       
		
		&:first-line{
			color: #656363;
		}
      }
    }
    .el-form-item {
      border: 1px solid #f7f5fb;
      background: #f7f5fb;
      border-radius: 5px;
      color: #606266;
    }
  }
  .el-tabs__item{
	  line-height: 35px;
	  font-size: 18px;
  }
  .el-tabs__active-bar{
	  margin-left: 118px;
	  width: 120px !important;
  }
</style>

<style rel="stylesheet/scss" lang="scss" scoped>
$bg:#2d3a4b;
$dark_gray:#889aa4;
$light_gray:#eee;


.login-container {
  position: fixed;
  height: 100%;
  width: 100%;
 // background-color: $bg;
  background-image: url('../../assets/login_page_bg.jpg');
     background-size: cover;
     background-position: center;
     position: relative;
	 .box-card {
	         position: relative;
	         border-radius: 8px;
	         z-index: 99;
	         border: 0;
	         box-shadow: 0 2px 12px 0 rgba(0, 0, 0, .5);
	         width: 425px;
	         height: 380px;
	         right: 360px;
			 float: right;
			 top:30%;
	         background-repeat: no-repeat;
	         background-attachment: fixed;
	         background-position: center;
	         background-size: 100% 100%;
	     }
  .login-form {
    position: absolute;
    left: 0;
    right: 0;
    width: 520px;
    max-width: 100%;
    padding: 5px 35px 15px 35px;
   // margin: 120px auto;
  }
  .tips {
    font-size: 14px;
    color: #fff;
    margin-bottom: 10px;
    span {
      &:first-of-type {
        margin-right: 16px;
      }
    }
  }
  .svg-container {
    padding: 6px 5px 6px 15px;
    color: $dark_gray;
    vertical-align: middle;
    width: 30px;
    display: inline-block;
  }
  .title-container {
    position: relative;
    .title {
      font-size: 26px;
      color: $light_gray;
      margin: 0px auto 40px auto;
      text-align: center;
      font-weight: bold;
    }
    .set-language {
      color: #fff;
      position: absolute;
      top: 5px;
      right: 0px;
    }
  }
  .show-pwd {
    position: absolute;
    right: 10px;
    top: 7px;
    font-size: 16px;
    color: $dark_gray;
    cursor: pointer;
    user-select: none;
  }
  .thirdparty-button {
    position: absolute;
    right: 35px;
    bottom: 28px;
  }
}
</style>
