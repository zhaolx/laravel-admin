export default {

  // 应用appid
  appid: 'ty9fd2848a039ab554',

  // 应用秘钥
  appSecret: 'ec32286d0718118861afdbf6e401ee81',

  // 配置显示在浏览器标签的title
  title: 'vue-admin',

  // token在Cookie中存储的天数，默认1天
  cookieExpires: 1,

  // api请求基础路径,注意和代理配合使用
  baseUrl: {
    dev: '',
    pro: 'http://api.hardphp.com/index.php'
  },

  // 上传路径
  uploadUrl: {
    img: 'http://laravel.admin.cc/api/common/uploadImg',
    video: '',
    file: ''
  },
  autoOpenBrowser:false,

}
