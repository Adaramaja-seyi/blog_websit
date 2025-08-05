import { createRouter, createWebHistory } from 'vue-router'

import Home from '../components/Home.vue'
import Dashboard from '../components/dashboard/Dashboard.vue'
import Login from '../components/auth/Login.vue'
import Register from '../components/auth/Register.vue'
import PostList from '../components/posts/PostList.vue'
import PostForm from '../components/posts/PostForm.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: { requiresAuth: false }
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: false } // Allow direct access without authentication
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { requiresAuth: false }
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: { requiresAuth: false }
  },
  {
    path: '/posts',
    name: 'Posts',
    component: PostList,
    meta: { requiresAuth: true }
  },
  {
    path: '/posts/create',
    name: 'CreatePost',
    component: PostForm,
    meta: { requiresAuth: true }
  },
  {
    path: '/profile',
    name: 'Profile',
    component: Dashboard,
    meta: { requiresAuth: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('token')

  // Allow access to all routes without authentication checks
  // Users can access login/register even if authenticated
  next()
})

export default router
