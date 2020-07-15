import { constantRouterMap } from '@/router'

const permission = {
  state: {
    routers: constantRouterMap,
    addRouters: []
  },
  mutations: {
    SET_ROUTERS: (state, routers) => {
      state.addRouters = routers
      state.routers = constantRouterMap.concat(routers)
    }
  },
  actions: {
    GenerateRoutes({ commit }, data) {
      return new Promise(resolve => {
        const accessedRouters = data
        accessedRouters.map(function(item) {
          const item_component = item.component
          item.component = (resolve) => require([`@/views/${item_component}`],resolve)
          item.children.map(function(child) {
            const child_component = child.component
            child.component = (resolve) => require([`@/views/${child_component}`],resolve)
          })
        })
        accessedRouters.push({ path: '*', redirect: '/404', hidden: true })
       // console.log(data)
        console.log(accessedRouters)
        commit('SET_ROUTERS', accessedRouters)
        resolve()
      })
    }
  }
}

export default permission
