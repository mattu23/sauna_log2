import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from 'ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _5cce9434 = () => interopDefault(import('../pages/editPassword.vue' /* webpackChunkName: "pages/editPassword" */))
const _9e77d438 = () => interopDefault(import('../pages/editUser.vue' /* webpackChunkName: "pages/editUser" */))
const _75858560 = () => interopDefault(import('../pages/list/index.vue' /* webpackChunkName: "pages/list/index" */))
const _7917f6dc = () => interopDefault(import('../pages/register.vue' /* webpackChunkName: "pages/register" */))
const _157ac8f1 = () => interopDefault(import('../pages/auth/login.vue' /* webpackChunkName: "pages/auth/login" */))
const _a0c5a340 = () => interopDefault(import('../pages/auth/signUp.vue' /* webpackChunkName: "pages/auth/signUp" */))
const _e654061a = () => interopDefault(import('../pages/index.vue' /* webpackChunkName: "pages/index" */))
const _5acb5890 = () => interopDefault(import('../pages/list/_id.vue' /* webpackChunkName: "pages/list/_id" */))

const emptyFn = () => {}

Vue.use(Router)

export const routerOptions = {
  mode: 'history',
  base: '/',
  linkActiveClass: 'nuxt-link-active',
  linkExactActiveClass: 'nuxt-link-exact-active',
  scrollBehavior,

  routes: [{
    path: "/editPassword",
    component: _5cce9434,
    name: "editPassword"
  }, {
    path: "/editUser",
    component: _9e77d438,
    name: "editUser"
  }, {
    path: "/list",
    component: _75858560,
    name: "list"
  }, {
    path: "/register",
    component: _7917f6dc,
    name: "register"
  }, {
    path: "/auth/login",
    component: _157ac8f1,
    name: "auth-login"
  }, {
    path: "/auth/signUp",
    component: _a0c5a340,
    name: "auth-signUp"
  }, {
    path: "/",
    component: _e654061a,
    name: "index"
  }, {
    path: "/list/:id",
    component: _5acb5890,
    name: "list-id"
  }],

  fallback: false
}

export function createRouter (ssrContext, config) {
  const base = (config._app && config._app.basePath) || routerOptions.base
  const router = new Router({ ...routerOptions, base  })

  // TODO: remove in Nuxt 3
  const originalPush = router.push
  router.push = function push (location, onComplete = emptyFn, onAbort) {
    return originalPush.call(this, location, onComplete, onAbort)
  }

  const resolve = router.resolve.bind(router)
  router.resolve = (to, current, append) => {
    if (typeof to === 'string') {
      to = normalizeURL(to)
    }
    return resolve(to, current, append)
  }

  return router
}
