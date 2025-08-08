// src/router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import Dashboard from '../components/dashboard/Dashboard.vue';
import Login from '../components/auth/Login.vue';
import Register from '../components/auth/Register.vue';
import PostList from '../components/posts/PostList.vue';
import PostForm from '../components/posts/PostForm.vue';
import Comments from '../components/comments/Comments.vue'; // Create if not exists
import Users from '../components/comments/Users.vue'; // Create if not exists
import Media from '../components/comments/Media.vue'; // Create if not exists

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: { requiresAuth: false },
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: false },
    children: [
      { path: '', name: 'DashboardHome', component: PostList }, // Default child route
      { path: 'posts', name: 'DashboardPosts', component: PostList },
      { path: 'posts/:id', name: 'DashboardPostForm', component: PostForm },
      { path: 'comments', name: 'Comments', component: Comments },
      { path: 'users', name: 'Users', component: Users },
      { path: 'media', name: 'Media', component: Media },
    ],
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { requiresAuth: false },
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: { requiresAuth: false },
  },
  {
    path: '/posts',
    name: 'Posts',
    component: PostList,
    meta: { requiresAuth: true },
  },
  {
    path: '/posts/create',
    name: 'CreatePost',
    component: PostForm,
    meta: { requiresAuth: true },
  },
  {
    path: '/profile',
    name: 'Profile',
    component: Dashboard,
    meta: { requiresAuth: true },
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    redirect: '/dashboard',
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('token');

  // Redirect to login for routes requiring authentication
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login');
  } else {
    next();
  }
});

export default router;