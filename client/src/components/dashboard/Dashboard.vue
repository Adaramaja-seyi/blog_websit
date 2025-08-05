<template>
  <div class="dashboard-layout">
    <Sidebar />

    <div class="main-content">
      <div class="container-fluid py-4">
        <h1 class="mb-4">Dashboard Overview</h1>

        <!-- Simple Stats Cards -->
        <div class="row mb-4">
          <div class="col-md-3">
            <div class="card bg-primary text-white">
              <div class="card-body">
                <h5 class="card-title">Total Posts</h5>
                <p class="card-text display-4">{{ stats.total_posts }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-success text-white">
              <div class="card-body">
                <h5 class="card-title">Total Comments</h5>
                <p class="card-text display-4">{{ stats.total_comments }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-danger text-white">
              <div class="card-body">
                <h5 class="card-title">Total Likes</h5>
                <p class="card-text display-4">{{ stats.total_likes }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card bg-warning text-white">
              <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <p class="card-text display-4">{{ stats.total_users }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="row">
          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h5 class="mb-0">Recent Posts</h5>
              </div>
              <div class="card-body">
                <div v-if="recentPosts.length > 0">
                  <div
                    v-for="post in recentPosts"
                    :key="post.id"
                    class="mb-3 pb-3 border-bottom"
                  >
                    <h6>{{ post.title }}</h6>
                    <small class="text-muted">{{
                      formatDate(post.created_at)
                    }}</small>
                  </div>
                </div>
                <div v-else>
                  <p class="text-muted">No recent posts found.</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card">
              <div class="card-header">
                <h5 class="mb-0">Quick Stats</h5>
              </div>
              <div class="card-body">
                <ul class="list-unstyled">
                  <li class="mb-2">
                    <strong>Active Users:</strong> {{ stats.active_users }}
                  </li>
                  <li class="mb-2">
                    <strong>Posts Today:</strong> {{ stats.posts_today }}
                  </li>
                  <li class="mb-2">
                    <strong>Comments Today:</strong> {{ stats.comments_today }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Sidebar from "./Sidebar.vue";
import { ref, onMounted } from "vue";
import api from "@/services/api";

export default {
  name: "Dashboard",
  components: {
    Sidebar,
  },
  setup() {
    const stats = ref({
      total_posts: 0,
      total_comments: 0,
      total_likes: 0,
      total_users: 0,
      active_users: 0,
      posts_today: 0,
      comments_today: 0,
    });

    const recentPosts = ref([]);

    const fetchDashboardData = async () => {
      try {
        const response = await api.get("/dashboard/stats");
        stats.value = response.data.stats;

        const postsResponse = await api.get("/posts/recent");
        recentPosts.value = postsResponse.data.posts;
      } catch (error) {
        console.error("Error fetching dashboard data:", error);
      }
    };

    const formatDate = (dateString) => {
      return new Date(dateString).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
      });
    };

    onMounted(() => {
      fetchDashboardData();
    });

    return {
      stats,
      recentPosts,
      formatDate,
    };
  },
};
</script>

<style scoped>
.dashboard-layout {
  display: flex;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  padding-left: 280px; /* Sidebar width */
}

@media (max-width: 992px) {
  .main-content {
    padding-left: 0;
  }
}

.card {
  border: none;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  transition: transform 0.2s;
}

.card:hover {
  transform: translateY(-2px);
}

.display-4 {
  font-size: 2.5rem;
  font-weight: 300;
  line-height: 1.2;
}
</style>
