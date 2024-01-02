import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/index',
      name: 'index',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/Index.vue')
    },
    {
      path: '/page',
      name: 'page',
      component: () => import('../views/Page.vue')
    },
    {
      path: '/user/login',
      name: 'user.login',
      component: () => import('../views/user/Login.vue')
    },
    {
      path: '/user/registration',
      name: 'user.registration',
      component: () => import('../views/user/Registration.vue')
    },
    {
      path: '/user/personal',
      name: 'user.personal',
      component: () => import('../views/user/Personal.vue')
    }
  ]
})

router.beforeEach((to, from, next) => {

  axios.get('http://localhost:8000/api/user')
      .catch(e => {
        if (e.response.status === 401) {
          localStorage.key('x_xsrf_token') ? localStorage.removeItem('x_xsrf_token'): ''
        }
      })

  const token = localStorage.getItem('x_xsrf_token')

  if (!token) {
    if (to.name === 'user.login' || to.name === 'user.registration') {
      return next()
    } else {
      return next({
        name: 'user.login'
      })
    }
  }
  if (to.name === 'user.login' || to.name === 'user.registration' && token) {
    return next({
      name: 'user.personal'
    })
  }

  next()
})

export default router
