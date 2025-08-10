import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import DashboardLayout from '../components/DashboardLayout.vue';
import Dashboard from '../components/Dashboard.vue';
import PostList from '../components/posts/PostList.vue';
import PostForm from '../components/posts/PostForm.vue';
import PostDetail from '../components/posts/PostDetail.vue';
// You need to create these components if not present:
import CommentList from '../components/comments/CommentList.vue';
import Profile from '../components/Profile.vue';
import Login from '../components/auth/Login.vue';
import Register from '../components/auth/Register.vue';

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
    meta: { requiresAuth: false },
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { requiresAuth: false },
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: { requiresAuth: false },
  },
  {
    path: '/feed',
    name: 'landing-feed',
    component: () => import('../components/LandingLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'landing-home',
        component: () => import('../components/LandingFeed.vue'),
      }
    ]
  },
  {
    path: '/dashboard',
    component: DashboardLayout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'dashboard',
        component: Dashboard,
      },
      {
        path: 'posts',
        name: 'posts',
        component: PostList
      },
      {
        path: 'posts/create',
        name: 'post-create',
        component: PostForm
      },
      {
        path: 'posts/:id',
        name: 'post-detail',
        component: PostDetail
      },
      {
        path: 'posts/:id/edit',
        name: 'post-edit',
        component: PostForm
      },
      {
        path: 'comments',
        name: 'comments',
        component: CommentList
      },
      {
        path: 'profile',
        name: 'profile',
        component: Profile
      },
    ]
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('token');
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login');
  } else {
    next();
  }
});

export default router;