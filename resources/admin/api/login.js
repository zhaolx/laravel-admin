import request from '@/utils/request'

export function loginByUsername(username, password) {
  const data = {
    account: username,
    passwd: password
  }
  return request({
    url: '/api/login',
    method: 'post',
    data
  })
}

export function getUserInfo() {
  return request({
    url: '/api/getuser',
    method: 'post'
  })
}

