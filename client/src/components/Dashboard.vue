<template>
  <div class="dashboard-pro">
    <div class="dashboard-header-pro">
      <div class="user-summary" v-if="user">
        <img
          v-if="user.profile_picture"
          :src="user.profile_picture"
          class="avatar-lg"
          :alt="user.name"
        />
        <div v-else class="avatar-lg-alt">
          {{ user.name ? user.name.charAt(0).toUpperCase() : "?" }}
        </div>
        <div class="user-info">
          <h2>{{ user.name }}</h2>
          <p class="user-email">{{ user.email }}</p>
        </div>
      </div>
      <div class="quick-stats">
        <div class="quick-stat-card">
          <div class="quick-stat-label">Posts</div>
          <div class="quick-stat-value">{{ stats.totalPosts }}</div>
        </div>
        <div class="quick-stat-card">
          <div class="quick-stat-label">Published</div>
          <div class="quick-stat-value">{{ stats.published }}</div>
        </div>
        <div class="quick-stat-card">
          <div class="quick-stat-label">Drafts</div>
          <div class="quick-stat-value">{{ stats.drafts }}</div>
        </div>
        <div class="quick-stat-card">
          <div class="quick-stat-label">Comments</div>
          <div class="quick-stat-value">{{ stats.comments }}</div>
        </div>
        <div class="quick-stat-card">
          <div class="quick-stat-label">Likes</div>
          <div class="quick-stat-value">{{ stats.likes }}</div>
        </div>
      </div>
    </div>

    <div class="dashboard-content-pro">
      <div class="dashboard-row">
        <div class="dashboard-col dashboard-col-8">
          <div class="card-pro">
            <div class="card-pro-header">
              <h3>My Posts</h3>
              <button
                class="btn btn-primary"
                @click="$router.push({ name: 'post-create' })"
              >
                <i class="bi bi-plus-lg"></i> New Post
              </button>
            </div>
            <div class="filters-pro">
              <select class="form-select" v-model="filters.status">
                <option value="">All Status</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
              </select>
              <select class="form-select" v-model="filters.tag">
                <option value="">All Tags</option>
                <option v-for="tag in tags" :key="tag.id" :value="tag.name">
                  {{ tag.name }}
                </option>
              </select>
              <div class="search-box-pro">
                <input
                  type="text"
                  class="form-control"
                  v-model="filters.search"
                  placeholder="Search posts..."
                />
                <i class="bi bi-search"></i>
              </div>
            </div>
            <div class="table-responsive-pro">
              <table class="table-pro">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Tags</th>
                    <th>Likes</th>
                    <th>Comments</th>
                    <th>Created</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="post in filteredPosts" :key="post.id">
                    <td class="post-title-pro">{{ post.title }}</td>
                    <td>
                      <span class="status-badge-pro" :class="post.status">{{
                        post.status
                      }}</span>
                    </td>
                    <td>
                      <div class="tags-pro">
                        <span
                          v-for="tag in post.tags"
                          :key="tag.id"
                          class="tag-pro"
                          >{{ tag.name }}</span
                        >
                      </div>
                    </td>
                    <td class="text-center">{{ post.likes_count }}</td>
                    <td class="text-center">{{ post.comments_count }}</td>
                    <td>{{ formatDate(post.created_at) }}</td>
                    <td class="actions-pro">
                      <button class="btn-icon-pro">
                        <i class="bi bi-pencil-square"></i>
                      </button>
                      <button class="btn-icon-pro">
                        <i class="bi bi-trash"></i>
                      </button>
                    </td>
                  </tr>
                  <tr v-if="filteredPosts.length === 0">
                    <td colspan="7" class="no-data-pro">
                      <i class="bi bi-exclamation-circle"></i> No posts found
                      matching your criteria
                    </td>
                  </tr>
                </tbody>
              </table>
              <!-- Listen for post-updated event from PostForm -->
              <router-view @post-updated="fetchUserAndStats" />
            </div>
          </div>
        </div>
        <div class="dashboard-col dashboard-col-4">
          <div class="card-pro analytics-pro">
            <div class="card-pro-header">
              <h3>Engagement Analytics</h3>
            </div>
            <div class="analytics-content-pro">
              <div class="analytics-metric-pro">
                <span class="analytics-value-pro"
                  >{{ engagementPercent }}%</span
                >
                <span class="analytics-label-pro">Engagement Rate</span>
              </div>
              <div class="progress-pro">
                <div class="progress-bar-pro">
                  <div
                    class="progress-fill-pro"
                    :style="{ width: engagementPercent + '%' }"
                  ></div>
                </div>
                <div class="progress-labels-pro">
                  <span>0%</span>
                  <span>100%</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Sidebar from "./shared/Sidebar.vue";

export default {
  name: "Dashboard",
  components: {
    Sidebar,
  },
  data() {
    return {
      user: null,
      stats: {
        totalPosts: 0,
        published: 0,
        drafts: 0,
        likes: 0,
      },
      tags: [],
      posts: [],
      filters: {
        status: "",
        tag: "",
        search: "",
      },
    };
  },
  created() {
    this.fetchUserAndStats();
  },
  computed: {
    filteredPosts() {
      return this.posts.filter((post) => {
        const matchesStatus = this.filters.status
          ? post.status === this.filters.status
          : true;
        const matchesTag = this.filters.tag
          ? post.tags.some((tag) => tag.name === this.filters.tag)
          : true;
        const matchesSearch = this.filters.search
          ? post.title.toLowerCase().includes(this.filters.search.toLowerCase())
          : true;
        return matchesStatus && matchesTag && matchesSearch;
      });
    },
    engagementPercent() {
      if (!this.stats.totalPosts) return 0;
      const engaged = this.posts.filter(
        (p) => p.status === "published" && p.likes_count > 0
      ).length;
      return Math.round((engaged / this.stats.totalPosts) * 100);
    },
  },
  methods: {
    async fetchUserAndStats() {
      try {
        const [userRes, statsRes] = await Promise.all([
          this.$api.getUserData(),
          this.$api.getDashboardStats(),
        ]);
        this.user = userRes.data;
        this.stats = statsRes.data.stats || {};
        this.posts = statsRes.data.posts || [];
        this.tags = statsRes.data.tags || [];
      } catch (e) {
        // fallback: try to get user only
        try {
          const userRes = await this.$api.getUserData();
          this.user = userRes.data;
        } catch {}
      }
    },
    handleLogout() {
      localStorage.removeItem("token");
      this.$router.push("/login");
    },
    formatDate(dateString) {
      const options = { year: "numeric", month: "short", day: "numeric" };
      return new Date(dateString).toLocaleDateString(undefined, options);
    },
  },
};
</script>

<style scoped>
.dashboard-pro {
  background: #f6f8fa;
  min-height: 100vh;
  padding: 0 0 2rem 0;
}
.dashboard-header-pro {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  justify-content: space-between;
  background: #fff;
  border-radius: 0 0 18px 18px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
  padding: 2rem 2.5rem 1.5rem 2.5rem;
  margin-bottom: 2.5rem;
  gap: 2rem;
}
.user-summary {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}
.avatar-lg {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #e5e7eb;
}
.avatar-lg-alt {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  background: #4361ee;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.2rem;
  font-weight: 700;
}
.user-info h2 {
  margin: 0 0 0.25rem 0;
  font-size: 1.5rem;
  font-weight: 700;
}
.user-email {
  color: #6c757d;
  font-size: 1rem;
}
.quick-stats {
  display: flex;
  gap: 2rem;
}
.quick-stat-card {
  background: #f8fafd;
  border-radius: 12px;
  box-shadow: 0 1px 4px rgba(67, 97, 238, 0.04);
  padding: 1.25rem 2rem;
  min-width: 120px;
  text-align: center;
}
.quick-stat-label {
  color: #6c757d;
  font-size: 0.95rem;
  margin-bottom: 0.25rem;
}
.quick-stat-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #4361ee;
}
.dashboard-content-pro {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 2rem;
}
.dashboard-row {
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
}
.dashboard-col {
  flex: 1 1 0;
  min-width: 320px;
}
.dashboard-col-8 {
  flex-basis: 0;
  flex-grow: 2;
}
.dashboard-col-4 {
  flex-basis: 0;
  flex-grow: 1;
  min-width: 320px;
}
.card-pro {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
  margin-bottom: 2rem;
  padding: 2rem 2rem 1.5rem 2rem;
}
.card-pro-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
}
.filters-pro {
  display: flex;
  gap: 1rem;
  margin-bottom: 1.5rem;
}
.search-box-pro {
  position: relative;
  min-width: 200px;
}
.search-box-pro input {
  padding-right: 2.2rem;
}
.search-box-pro i {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  color: #6c757d;
}
.table-responsive-pro {
  overflow-x: auto;
}
.table-pro {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 1px 4px rgba(67, 97, 238, 0.04);
}
.table-pro th,
.table-pro td {
  padding: 1rem 1.25rem;
  text-align: left;
}
.table-pro th {
  background: #f8fafd;
  color: #6c757d;
  font-size: 0.95rem;
  font-weight: 600;
  border-bottom: 1px solid #e5e7eb;
}
.table-pro td {
  border-bottom: 1px solid #f1f3f5;
  vertical-align: middle;
}
.table-pro tr:last-child td {
  border-bottom: none;
}
.post-title-pro {
  font-weight: 600;
  color: #212529;
}
.status-badge-pro {
  display: inline-block;
  padding: 0.35rem 0.85rem;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
  text-transform: capitalize;
  background: #e6f7ff;
  color: #0c8599;
}
.status-badge-pro.draft {
  background: #fff4e6;
  color: #e67700;
}
.tags-pro {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}
.tag-pro {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  background: #f1f3f5;
  border-radius: 20px;
  font-size: 0.85rem;
  color: #6c757d;
}
.actions-pro {
  display: flex;
  gap: 0.5rem;
}
.btn-icon-pro {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: transparent;
  border: none;
  color: #6c757d;
  cursor: pointer;
  transition: background 0.2s;
}
.btn-icon-pro:hover {
  background: #f8fafd;
  color: #212529;
}
.no-data-pro {
  padding: 2rem;
  text-align: center;
  color: #6c757d;
}
.analytics-pro {
  min-height: 320px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.analytics-content-pro {
  width: 100%;
  margin-top: 1.5rem;
}
.analytics-metric-pro {
  text-align: center;
  margin-bottom: 1.5rem;
}
.analytics-value-pro {
  font-size: 2.5rem;
  font-weight: 700;
  color: #4361ee;
}
.analytics-label-pro {
  color: #6c757d;
  font-size: 1.1rem;
}
.progress-pro {
  margin-top: 1rem;
}
.progress-bar-pro {
  height: 12px;
  background: #e9ecef;
  border-radius: 6px;
  overflow: hidden;
}
.progress-fill-pro {
  height: 100%;
  background: #4361ee;
  border-radius: 6px;
  transition: width 0.6s ease;
}
.progress-labels-pro {
  display: flex;
  justify-content: space-between;
  margin-top: 0.5rem;
  font-size: 0.9rem;
  color: #6c757d;
}
@media (max-width: 1100px) {
  .dashboard-row {
    flex-direction: column;
  }
  .dashboard-col-8,
  .dashboard-col-4 {
    min-width: 0;
    width: 100%;
  }
}
@media (max-width: 700px) {
  .dashboard-header-pro {
    flex-direction: column;
    align-items: flex-start;
    padding: 1.5rem 1rem 1rem 1rem;
    gap: 1rem;
  }
  .dashboard-content-pro {
    padding: 0 0.5rem;
  }
  .card-pro {
    padding: 1rem 0.5rem 1rem 0.5rem;
  }
  .quick-stats {
    gap: 0.5rem;
  }
}
</style>
