<template>
  <div class="dashboard-layout">
    <Sidebar
      :user-data="currentUser"
      @profile-update="updateUserProfile"
      @logout="handleUserLogout"
    />

    <div class="main-content">
      <header class="topbar">
        <div class="header-content">
          <h2 class="page-title">Dashboard Overview</h2>
          <div class="user-profile">
            <span class="username">{{ currentUser.name || 'User' }}</span>
            <div class="avatar">
              <i class="fas fa-user"></i>
            </div>
          </div>
        </div>
      </header>

      <div class="container-fluid py-4">
        <!-- Loading State -->
        <div v-if="loading" class="loading-container">
          <div class="spinner"></div>
          <p>Loading dashboard data...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="error-container">
          <i class="fas fa-exclamation-triangle"></i>
          <p>{{ error }}</p>
          <button @click="retryFetch" class="btn btn-primary">Retry</button>
        </div>

        <!-- Main Content -->
        <div v-else>
          <!-- Stats Cards with Icons -->
          <div class="stats-grid">
            <StatCard
              icon="file-alt"
              title="Total Posts"
              :value="stats.total_posts"
              color="#4e73df"
              trend="up"
              change="12%"
            />
            <StatCard
              icon="comments"
              title="Total Comments"
              :value="stats.total_comments"
              color="#1cc88a"
              trend="up"
              change="8%"
            />
            <StatCard
              icon="heart"
              title="Total Likes"
              :value="stats.total_likes"
              color="#e74a3b"
              trend="down"
              change="3%"
            />
            <StatCard
              icon="users"
              title="Total Users"
              :value="stats.total_users"
              color="#f6c23e"
              trend="up"
              change="15%"
            />
          </div>

          <!-- Main Content Area -->
          <div class="content-grid">
            <!-- Left Column -->
            <div class="main-column">
              <!-- Activity Chart -->
              <div class="card chart-card">
                <div class="card-header">
                  <h5>Activity Overview</h5>
                  <div class="time-filter">
                    <button
                      v-for="period in ['Week', 'Month', 'Year']"
                      :key="period"
                      :class="{ active: selectedPeriod === period }"
                      @click="changePeriod(period)"
                    >
                      {{ period }}
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <ActivityChart :chartData="chartData" />
                </div>
              </div>

              <!-- Recent Posts Table -->
              <div class="card">
                <div class="card-header">
                  <h5>Recent Posts</h5>
                  <router-link to="/dashboard/posts" class="view-all"
                    >View All</router-link
                  >
                </div>
                <div class="card-body">
                  <div v-if="recentPosts.length > 0" class="table-responsive">
                    <table class="posts-table">
                      <thead>
                        <tr>
                          <th>Title</th>
                          <th>Date</th>
                          <th>Comments</th>
                          <th>Likes</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="post in recentPosts" :key="post.id">
                          <td class="post-title">
                            <router-link :to="`/dashboard/posts/${post.id}`">{{
                              post.title
                            }}</router-link>
                          </td>
                          <td>{{ formatDate(post.created_at) }}</td>
                          <td>{{ post.comment_count || 0 }}</td>
                          <td>{{ post.like_count || 0 }}</td>
                          <td>
                            <span :class="`status-badge ${post.status}`">{{
                              post.status
                            }}</span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div v-else class="empty-state">
                    <i class="fas fa-file-alt"></i>
                    <p>No recent posts</p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column -->
            <div class="sidebar-column">
              <!-- Quick Stats -->
              <div class="card quick-stats">
                <div class="card-header">
                  <h5>Quick Stats</h5>
                </div>
                <div class="card-body">
                  <ul class="stats-list">
                    <li>
                      <div class="stat-item">
                        <span class="stat-label">Active Users</span>
                        <span class="stat-value">{{
                          stats.active_users || 0
                        }}</span>
                      </div>
                    </li>
                    <li>
                      <div class="stat-item">
                        <span class="stat-label">Posts Today</span>
                        <span class="stat-value">{{
                          stats.posts_today || 0
                        }}</span>
                      </div>
                    </li>
                    <li>
                      <div class="stat-item">
                        <span class="stat-label">Comments Today</span>
                        <span class="stat-value">{{
                          stats.comments_today || 0
                        }}</span>
                      </div>
                    </li>
                    <li>
                      <div class="stat-item">
                        <span class="stat-label">Avg. Session</span>
                        <span class="stat-value">{{ avgSessionTime }}</span>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>

              <!-- Recent Comments -->
              <div class="card recent-comments">
                <div class="card-header">
                  <h5>Recent Comments</h5>
                </div>
                <div class="card-body">
                  <div v-if="recentComments.length > 0" class="comments-list">
                    <div
                      v-for="comment in recentComments"
                      :key="comment.id"
                      class="comment-item"
                    >
                      <div class="comment-avatar">
                        <i class="fas fa-user"></i>
                      </div>
                      <div class="comment-content">
                        <div class="comment-meta">
                          <strong>{{
                            comment.user?.name || 'Anonymous'
                          }}</strong>
                          <span>{{ formatTimeAgo(comment.created_at) }}</span>
                        </div>
                        <p class="comment-text">
                          {{ truncateText(comment.content, 60) }}
                        </p>
                        <small
                          >On:
                          {{ comment.post?.title || 'Unknown Post' }}</small
                        >
                      </div>
                    </div>
                  </div>
                  <div v-else class="empty-state">
                    <i class="fas fa-comment-slash"></i>
                    <p>No recent comments</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Router View for Child Routes -->
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import Sidebar from './Sidebar.vue';
import StatCard from './StatsCard.vue';
import ActivityChart from './ActivityChart.vue';
import PostForm from '../posts/PostForm.vue';
import api from '@/services/api';

export default {
  name: 'Dashboard',
  components: {
    Sidebar,
    StatCard,
    ActivityChart,
    PostForm,
  },
  data() {
    return {
      currentUser: {
        loading: false,
        name: '',
        email: '',
        avatar: null,
        role: '',
      },
      stats: {
        total_posts: 0,
        total_comments: 0,
        total_likes: 0,
        total_users: 0,
        active_users: 0,
        posts_today: 0,
        comments_today: 0,
      },
      recentPosts: [],
      recentComments: [],
      chartData: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [
          {
            label: 'Posts',
            backgroundColor: '#4e73df',
            data: [12, 19, 8, 15, 12, 6, 9],
          },
          {
            label: 'Comments',
            backgroundColor: '#1cc88a',
            data: [8, 12, 5, 9, 7, 3, 5],
          },
        ],
      },
      loading: false,
      error: null,
      selectedPeriod: 'Week', // Default period
      avgSessionTime: '0s', // Default value
    };
  },
  computed: {
    statCards() {
      return [
        {
          icon: 'file-alt',
          title: 'Total Posts',
          value: this.stats.total_posts,
          color: '#4e73df',
          trend: 'up',
          change: '12%',
        },
        {
          icon: 'comments',
          title: 'Total Comments',
          value: this.stats.total_comments,
          color: '#1cc88a',
          trend: 'up',
          change: '8%',
        },
        {
          icon: 'heart',
          title: 'Total Likes',
          value: this.stats.total_likes,
          color: '#e74a3b',
          trend: 'down',
          change: '3%',
        },
        {
          icon: 'users',
          title: 'Total Users',
          value: this.stats.total_users,
          color: '#f6c23e',
          trend: 'up',
          change: '15%',
        },
      ];
    },
    quickStats() {
      return {
        activeUsers: this.stats.active_users,
        postsToday: this.stats.posts_today,
        commentsToday: this.stats.comments_today,
        avgSession: this.avgSessionTime,
      };
    },
  },
  async created() {
    await Promise.all([this.fetchUserData(), this.fetchDashboardData()]);
  },
  methods: {
    async fetchDashboardData() {
      this.loading = true;
      this.error = null;
      try {
        const [statsRes, postsRes, commentsRes] = await Promise.all([
          api.getDashboardStats({ period: this.selectedPeriod }),
          api.getRecentPosts(),
          api.getRecentComments(),
        ]);

        this.stats = statsRes.data.stats || {
          total_posts: 0,
          total_comments: 0,
          total_likes: 0,
          total_users: 0,
          active_users: 0,
          posts_today: 0,
          comments_today: 0,
        };
        this.recentPosts = postsRes.data.posts || [];
        this.recentComments = commentsRes.data.comments || [];
        this.avgSessionTime = statsRes.data.stats?.avg_session_time || '0s';

        // Update chartData based on API response or fallback
        this.chartData = {
          labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
          datasets: [
            {
              label: 'Posts',
              backgroundColor: '#4e73df',
              data: statsRes.data.stats?.posts_data || [12, 19, 8, 15, 12, 6, 9],
            },
            {
              label: 'Comments',
              backgroundColor: '#1cc88a',
              data: statsRes.data.stats?.comments_data || [8, 12, 5, 9, 7, 3, 5],
            },
          ],
        };
      } catch (error) {
        console.error('Error fetching dashboard data:', error);
        this.error = 'Failed to load dashboard data. Using fallback data.';
        // Fallback data for optional API
        this.stats = {
          total_posts: 0,
          total_comments: 0,
          total_likes: 0,
          total_users: 0,
          active_users: 0,
          posts_today: 0,
          comments_today: 0,
        };
        this.recentPosts = [];
        this.recentComments = [];
        this.avgSessionTime = '0s';
        this.chartData = {
          labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
          datasets: [
            {
              label: 'Posts',
              backgroundColor: '#4e73df',
              data: [12, 19, 8, 15, 12, 6, 9],
            },
            {
              label: 'Comments',
              backgroundColor: '#1cc88a',
              data: [8, 12, 5, 9, 7, 3, 5],
            },
          ],
        };
      } finally {
        this.loading = false;
      }
    },
    async fetchUserData() {
      this.currentUser.loading = true;
      try {
        const response = await api.getUserData();
        this.currentUser = response.data.user || response.data || {
          name: '',
          email: '',
          avatar: null,
          role: '',
        };
        localStorage.setItem('user', JSON.stringify(this.currentUser));
      } catch (error) {
        console.error('Error fetching user data:', error);
        this.error = 'Failed to load user data. Using fallback data.';
        // Fallback to localStorage
        const userStr = localStorage.getItem('user');
        if (userStr) {
          this.currentUser = { ...JSON.parse(userStr), loading: false };
        } else {
          this.currentUser = {
            loading: false,
            name: 'Guest',
            email: '',
            avatar: null,
            role: '',
          };
        }
      }
    },
    async updateUserProfile(updatedData) {
      try {
        const updateData = {
          name: updatedData.name || this.currentUser.name,
          email: updatedData.email || this.currentUser.email,
          bio: updatedData.bio || this.currentUser.bio || '',
          avatar: updatedData.avatar || this.currentUser.avatar || '',
          gender: updatedData.gender || this.currentUser.gender || '',
          ...updatedData,
        };
        const response = await api.updateProfile(updateData);
        this.currentUser = response.data.user || { ...this.currentUser, ...updateData };
        localStorage.setItem('user', JSON.stringify(this.currentUser));
        this.$toast?.success('Profile updated successfully');
        return response.data;
      } catch (error) {
        console.error('Profile update error:', error);
        const errorMessage = error.response?.data?.message || 'Failed to update profile';
        this.$toast?.error(errorMessage);
        throw error;
      }
    },
    async handleUserLogout() {
      try {
        await api.logout();
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        this.$router.push('/login');
      } catch (error) {
        console.error('Logout failed:', error);
        this.$toast?.error('Failed to log out');
      }
    },
    retryFetch() {
      this.fetchDashboardData();
    },
    changePeriod(period) {
      this.selectedPeriod = period;
      this.fetchDashboardData();
    },
    formatDate(dateString) {
      if (!dateString) return 'N/A';
      return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
      });
    },
    formatTimeAgo(dateString) {
      if (!dateString) return 'N/A';
      const date = new Date(dateString);
      const now = new Date();
      const diffInHours = Math.floor((now - date) / (1000 * 60 * 60));

      if (diffInHours < 1) return 'Just now';
      if (diffInHours < 24) return `${diffInHours}h ago`;
      if (diffInHours < 168) return `${Math.floor(diffInHours / 24)}d ago`;
      return this.formatDate(dateString);
    },
    truncateText(text, length) {
      if (!text) return '';
      return text.length > length ? text.substring(0, length) + '...' : text;
    },
  },
};
</script>

<style scoped>
/* Your existing styles remain unchanged */
.dashboard-layout {
  display: flex;
  min-height: 100vh;
  background-color: #f8f9fc;
}

.main-content {
  flex: 1;
  margin-left: 250px;
}

.topbar {
  background: #fff;
  box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
  padding: 1rem 1.5rem;
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #4e73df;
  margin: 0;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.username {
  font-weight: 500;
}

.avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: #4e73df;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.content-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
}

@media (min-width: 1200px) {
  .content-grid {
    grid-template-columns: 2fr 1fr;
  }
}

.card {
  background: #fff;
  border: none;
  border-radius: 0.35rem;
  box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
  margin-bottom: 1.5rem;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 0.5rem 1.5rem 0 rgba(58, 59, 69, 0.2);
}

.card-header {
  padding: 1rem 1.35rem;
  background-color: #f8f9fc;
  border-bottom: 1px solid #e3e6f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card-header h5 {
  font-size: 1rem;
  font-weight: 600;
  margin: 0;
  color: #4e73df;
}

.card-body {
  padding: 1.35rem;
}

.chart-card {
  height: 100%;
}

.chart-card .card-body {
  padding: 0.5rem;
  height: calc(100% - 56px);
}

.time-filter {
  display: flex;
  gap: 0.5rem;
}

.time-filter button {
  border: none;
  background: transparent;
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
  color: #858796;
  cursor: pointer;
  border-radius: 0.25rem;
}

.time-filter button.active {
  background-color: #4e73df;
  color: white;
}

.posts-table {
  width: 100%;
  border-collapse: collapse;
}

.posts-table th {
  text-align: left;
  padding: 0.75rem 1rem;
  font-size: 0.75rem;
  text-transform: uppercase;
  color: #858796;
  border-bottom: 1px solid #e3e6f0;
}

.posts-table td {
  padding: 1rem;
  border-bottom: 1px solid #e3e6f0;
  vertical-align: middle;
}

.post-title a {
  color: #4e73df;
  text-decoration: none;
  font-weight: 500;
}

.post-title a:hover {
  text-decoration: underline;
}

.status-badge {
  display: inline-block;
  padding: 0.35em 0.65em;
  font-size: 0.75em;
  font-weight: 700;
  line-height: 1;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: 0.25rem;
}

.status-badge.published {
  color: #fff;
  background-color: #1cc88a;
}

.status-badge.draft {
  color: #fff;
  background-color: #f6c23e;
}

.status-badge.pending {
  color: #fff;
  background-color: #e74a3b;
}

.stats-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.stats-list li {
  padding: 0.75rem 0;
  border-bottom: 1px solid #e3e6f0;
}

.stats-list li:last-child {
  border-bottom: none;
}

.stat-item {
  display: flex;
  justify-content: space-between;
}

.stat-label {
  color: #858796;
}

.stat-value {
  font-weight: 600;
}

.comments-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.comment-item {
  display: flex;
  gap: 1rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #e3e6f0;
}

.comment-item:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.comment-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: #f8f9fc;
  color: #4e73df;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.comment-content {
  flex: 1;
}

.comment-meta {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.25rem;
}

.comment-meta span {
  font-size: 0.75rem;
  color: #858796;
}

.comment-text {
  margin: 0.25rem 0;
  font-size: 0.875rem;
}

.empty-state {
  text-align: center;
  padding: 2rem 0;
  color: #858796;
}

.empty-state i {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  opacity: 0.5;
}

.empty-state p {
  margin: 0;
}

.view-all {
  font-size: 0.875rem;
  color: #4e73df;
  text-decoration: none;
}

.view-all:hover {
  text-decoration: underline;
}

@media (max-width: 992px) {
  .main-content {
    margin-left: 0;
  }
}
</style>