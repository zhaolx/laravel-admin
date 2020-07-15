import { getToken, setToken, removeToken } from '@/utils/auth'
import request from '@/utils/request'

const user = {
  state: {
    token: getToken(),
    name: '',
    avatar: '',
    status: '',
    realName: '',
    phone: '',
    email: '',
    roles: 0,
    group: ''
  },

  mutations: {
    SET_TOKEN: (state, token) => {
      state.token = token
    },
    SET_REALNAME: (state, realName) => {
      state.realName = realName
    },
    SET_EMAIL: (state, email) => {
      state.email = email
    },
    SET_PHONE: (state, phone) => {
      state.phone = phone
    },
    SET_STATUS: (state, status) => {
      state.status = status
    },
    SET_NAME: (state, name) => {
      state.name = name
    },
    SET_AVATAR: (state, avatar) => {
      state.avatar = avatar
    },
    SET_ROLES: (state, roles) => {
      state.roles = roles
    },
    SET_GROUP: (state, group) => {
      state.group = group
    }
  },

  actions: {
    // 用户名登录
    LoginByUsername({ commit }, userInfo) {
      const username = userInfo.username.trim()
      return new Promise((resolve, reject) => {
        request({
			url: '/api/user/login',
			method: 'post',
			data:{account:username, passwd:userInfo.password}
		}).then(response => {
          const data = response.data
          commit('SET_TOKEN', data.userToken)
          setToken(response.data.userToken)
          resolve()
        }).catch(error => {
          reject(error)
        })
      })
    },

    // 获取用户信息
    GetUserInfo({ commit, state }) {
      return new Promise((resolve, reject) => {
	    request({
			url: '/api/user/getuser',
			method: 'post'
		}).then(response => {
          if(response.code!=1) { // 由于mockjs 不支持自定义状态码只能这样hack
            reject('error')
          }
          const data = response.data

          if (data.access && data.info.uid > 0) {
            commit('SET_ROLES', data.info.uid)
          } else {
            reject('拉取用户权限失败')
          } 
          commit('SET_NAME', data.info.nickname)
          commit('SET_AVATAR', data.info.avatar)
         // commit('SET_EMAIL', data.info.email)
          resolve(response)
        }).catch(error => {
          reject(error)
        })
      })
    },

    // 前端 登出
    FedLogOut({ commit }) {
      return new Promise(resolve => {
        commit('SET_TOKEN', '')
        commit('SET_ROLES', 0)
        removeToken()
        resolve()
      })
    },

    // 动态修改权限
    ChangeRoles({ commit, dispatch }) {
      return new Promise(resolve => {
        getUserInfo().then(response => {
          const data = response.data
          commit('SET_ROLES', data.info.uid)
          commit('SET_NAME', data.info.nickname)
          commit('SET_AVATAR', data.info.avatar)
          //commit('SET_INTRODUCTION', data.introduction)
          dispatch('GenerateRoutes', data) // 动态修改权限后 重绘侧边菜单
          resolve()
        })
      })
    }
  }
}

export default user
