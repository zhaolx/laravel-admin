const getters = {
  sidebar: state => state.app.sidebar,
  rihgtbar: state => state.app.rihgtbar,
  language: state => state.app.language,
  size: state => state.app.size,
  device: state => state.app.device,
  visitedViews: state => state.tagsView.visitedViews,
  cachedViews: state => state.tagsView.cachedViews,
  token: state => state.user.token,
  avatar: state => state.user.avatar,
  name: state => state.user.name,
  realName: state => state.user.realName,
  status: state => state.user.status,
  roles: state => state.user.roles,
  group: state => state.user.group,
  phone: state => state.user.phone,
  email: state => state.user.email,
  permission_routers: state => state.permission.routers,
  addRouters: state => state.permission.addRouters,
  errorLogs: state => state.errorLog.logs
}
export default getters
