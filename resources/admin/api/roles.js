import request from '@/utils/request'

export function getList(query) {
  return request({
    url: '/admin/roles/index',
    method: 'post',
    data: query
  })
}

export function getListAll() {
  return request({
    url: '/admin/roles/getLists',
    method: 'post'
  })
}

export function getinfo(id) {
  return request({
    url: '/admin/roles/getinfo',
    method: 'get',
    params: { id }
  })
}

export function save(data) {
  return request({
    url: '/admin/roles/save',
    method: 'post',
    data
  })
}

export function del(id) {
  return request({
    url: '/admin/roles/del',
    method: 'get',
    params: { id }
  })
}

export function change(id, field, value) {
  const data = {
    val: id,
    field: field,
    value: value
  }
  return request({
    url: '/admin/roles/change',
    method: 'post',
    data
  })
}
export function delAll(data) {
  return request({
    url: '/admin/roles/delall',
    method: 'post',
    data
  })
}

export function changeAll(data) {
  return request({
    url: '/admin/roles/changeall',
    method: 'post',
    data
  })
}
