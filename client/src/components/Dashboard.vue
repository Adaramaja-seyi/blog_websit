<template>
  <div class="dashboard-container">
    <main class="content">
      <!-- Header Section -->
      <header class="content-header">
        <div class="header-title">
          <h1>Content Dashboard</h1>
        </div>
        <div class="header-actions">
          <button
            class="btn primary"
            @click="$router.push({ name: 'CreatePost' })"
          >
            + New Post
          </button>
          <div class="search-box">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Search your posts..."
            />
            <i class="icon-search"></i>
          </div>
        </div>
      </header>

      <!-- Stats Cards Grid -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-value">{{ stats.total_posts }}</div>
          <div class="stat-label">Total Posts</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">{{ stats.total_comments }}</div>
          <div class="stat-label">Total Comments</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">{{ stats.total_likes }}</div>
          <div class="stat-label">Total Likes</div>
        </div>
        <div class="stat-card">
          <div class="stat-value">{{ engagementRate }}%</div>
          <div class="stat-label">Engagement Rate</div>
          <progress-bar :value="engagementRate" :max="100"></progress-bar>
        </div>
      </div>

      <!-- Engagement Chart -->
      <div class="chart-container">
        <h2>Engagement Overview</h2>
        <div class="chart-wrapper">
          <engagement-chart :data="chartData"></engagement-chart>
        </div>
      </div>

      <!-- Posts Section -->
      <div class="posts-section">
        <PostForm v-if="showPostForm" @post-updated="handlePostUpdated" />
        <div class="section-header">
          <h2>Your Posts</h2>
          <div class="filters">
            <div class="filter-group">
              <label for="status-filter">Status</label>
              <select
                id="status-filter"
                v-model="statusFilter"
                class="filter-select"
              >
                <option value="all">All Status</option>
                <option value="published">Published</option>
                <option value="draft">Drafts</option>
                <option value="scheduled">Scheduled</option>
              </select>
            </div>
            <button class="btn secondary" @click="applyFilters">
              Apply Filters
            </button>
          </div>
        </div>

        <!-- Responsive Table -->
        <div class="posts-table">
          <div class="table-responsive">
            <table>
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Status</th>
                  <th>Likes</th>
                  <th>Comments</th>
                  <th>Publish Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="post in paginatedPosts" :key="post.id">
                  <td data-label="Title">
                    <router-link :to="`/post/${post.id}`">
                      {{ post.title }}
                    </router-link>
                  </td>
                  <td data-label="Status">
                    <span :class="`status-badge ${post.status}`">
                      {{ post.status }}
                    </span>
                  </td>
                  <td data-label="Likes">{{ post.likes }}</td>
                  <td data-label="Comments">{{ post.comments }}</td>
                  <td data-label="Publish Date">
                    {{ formatDate(post.publishDate) }}
                  </td>
                  <td class="actions" data-label="Actions">
                    <button
                      class="icon-btn"
                      title="Edit"
                      @click="editPost(post.id)"
                    >
                      <i class="icon-edit"></i>
                    </button>
                    <button
                      class="icon-btn"
                      title="View"
                      @click="viewPost(post.id)"
                    >
                      <i class="icon-view"></i>
                    </button>
                    <button
                      class="icon-btn"
                      title="Delete"
                      @click="deletePost(post.id)"
                    >
                      <i class="icon-delete"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Pagination -->
        <div class="pagination">
          <button
            class="btn secondary"
            :disabled="currentPage === 1"
            @click="prevPage"
          >
            Previous
          </button>
          <span>Page {{ currentPage }} of {{ totalPages }}</span>
          <button
            class="btn secondary"
            :disabled="currentPage === totalPages"
            @click="nextPage"
          >
            Next
          </button>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import ProgressBar from "@/components/shared/ProgressBar.vue";
import EngagementChart from "@/components/shared/EngagementChart.vue";
import PostForm from "@/components/posts/PostForm.vue";
export default {
  name: "Dashboard",
  components: {
    ProgressBar,
    EngagementChart,
    PostForm,
  },
  data() {
    return {
      stats: {
        total_posts: 0,
        total_comments: 0,
        total_likes: 0,
      },
      chartData: {},
      posts: [],
      statusFilter: "all",
      searchQuery: "",
      currentPage: 1,
      itemsPerPage: 10,
      loading: false,
      user: {},
      showPostForm: false,
    };
  },
  computed: {
    filteredPosts() {
      let filtered = this.posts;
      if (this.statusFilter !== "all") {
        filtered = filtered.filter((post) => post.status === this.statusFilter);
      }
      if (this.searchQuery) {
        filtered = filtered.filter((post) =>
          post.title.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      }
      if (this.user && this.user.id) {
        filtered = filtered.filter((post) => post.user_id === this.user.id);
      }
      return filtered;
    },
    paginatedPosts() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      return this.filteredPosts.slice(start, start + this.itemsPerPage);
    },
    totalPages() {
      return Math.max(
        1,
        Math.ceil(this.filteredPosts.length / this.itemsPerPage)
      );
    },
    engagementRate() {
      if (this.stats.total_posts === 0) return 0;
      return Math.round(
        ((this.stats.total_likes + this.stats.total_comments) /
          (this.stats.total_posts * 2)) *
          100
      );
    },
  },
  methods: {
    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString();
    },
    prevPage() {
      if (this.currentPage > 1) this.currentPage--;
    },
    nextPage() {
      if (this.currentPage < this.totalPages) this.currentPage++;
    },
    applyFilters() {
      this.currentPage = 1;
    },
    async fetchUser() {
      try {
        const res = await this.$api.getUserData();
        this.user = res.data;
      } catch (e) {
        this.user = JSON.parse(localStorage.getItem("user")) || { id: null };
      }
    },
    recalculateStatsAndChart() {
      const posts = this.filteredPosts;
      this.stats.total_posts = posts.length;
      this.stats.total_comments = posts.reduce(
        (sum, p) => sum + (p.comments || 0),
        0
      );
      this.stats.total_likes = posts.reduce(
        (sum, p) => sum + (p.likes || 0),
        0
      );
      this.chartData = {
        labels: Array.isArray(posts)
          ? posts.map((p) =>
              p.publishDate ? this.formatDate(p.publishDate) : ""
            )
          : [],
        likes: Array.isArray(posts)
          ? posts.map((p) =>
              typeof p.likes === "number" ? p.likes : p.likes || 0
            )
          : [],
        comments: Array.isArray(posts)
          ? posts.map((p) =>
              typeof p.comments === "number" ? p.comments : p.comments || 0
            )
          : [],
      };
    },
    async fetchPosts() {
      try {
        this.loading = true;
        const res = await this.$api.getPosts();
        this.posts = (res.data.data || [])
          .filter((post) => post.user_id === this.user.id)
          .map((post) => ({
            ...post,
            publishDate: post.publishDate || post.created_at || "",
            likes:
              typeof post.likes === "number" ? post.likes : post.likes || 0,
            comments:
              typeof post.comments === "number"
                ? post.comments
                : post.comments || 0,
          }));
      } catch (e) {
        this.posts = [];
      } finally {
        this.loading = false;
        this.recalculateStatsAndChart();
      }
    },
    editPost(id) {
      this.$router.push({ name: "DashboardPostForm", params: { id } });
    },
    viewPost(id) {
      this.$router.push({ name: "DashboardPosts", params: { id } });
    },
    async deletePost(id) {
      if (confirm("Are you sure you want to delete this post?")) {
        await this.$api.deletePost(id);
        this.fetchPosts();
      }
    },
    async handlePostUpdated() {
      await this.fetchPosts();
    },
  },
  async created() {
    await this.fetchUser();
    await this.fetchPosts();
  },
};
</script>

<style lang="scss" scoped>
// Variables
$primary-color: #4361ee;
$secondary-color: #3a0ca3;
$light-bg: #f8f9fa;
$dark-text: #2b2d42;
$light-text: #6c757d;
$success: #4cc9f0;
$warning: #f8961e;
$danger: #f72585;
$border-radius: 8px;
$box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
$breakpoint-md: 768px;
$breakpoint-lg: 992px;

// Base styles
.dashboard-container {
  width: 100%;
  min-height: 100vh;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  color: $dark-text;
  background: $light-bg;
}

.content {
  width: 100%;
  padding: 1.5rem;
  margin-left: auto;
  margin-right: auto;
  max-width: 1400px;

  @media (min-width: $breakpoint-lg) {
    padding: 2rem;
  }
}

// Header styles
.content-header {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 2rem;

  @media (min-width: $breakpoint-md) {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
  }

  .header-title h1 {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;

    @media (min-width: $breakpoint-md) {
      font-size: 1.75rem;
    }
  }

  .header-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 100%;

    @media (min-width: $breakpoint-md) {
      flex-direction: row;
      align-items: center;
      justify-content: flex-end;
      width: auto;
    }
  }
}

.search-box {
  position: relative;
  width: 100%;

  @media (min-width: $breakpoint-md) {
    width: 250px;
  }

  input {
    width: 100%;
    padding: 0.5rem 1rem 0.5rem 2.5rem;
    border-radius: $border-radius;
    border: 1px solid #ddd;
    font-size: 0.9rem;

    &:focus {
      outline: none;
      border-color: $primary-color;
    }
  }

  .icon-search {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: $light-text;
  }
}

// Stats grid
.stats-grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 1rem;
  margin-bottom: 2rem;

  @media (min-width: 500px) {
    grid-template-columns: repeat(2, 1fr);
  }

  @media (min-width: $breakpoint-md) {
    grid-template-columns: repeat(4, 1fr);
  }

  .stat-card {
    background: white;
    padding: 1.25rem;
    border-radius: $border-radius;
    box-shadow: $box-shadow;

    .stat-value {
      font-size: 1.75rem;
      font-weight: 700;
      margin-bottom: 0.25rem;
      line-height: 1;

      @media (min-width: $breakpoint-md) {
        font-size: 2rem;
      }
    }

    .stat-label {
      color: $light-text;
      margin-bottom: 0.5rem;
      font-size: 0.9rem;
    }

    .stat-trend {
      font-size: 0.875rem;

      &.positive {
        color: $success;
      }

      &.negative {
        color: $danger;
      }
    }
  }
}

// Chart container
.chart-container {
  background: white;
  padding: 1.25rem;
  border-radius: $border-radius;
  box-shadow: $box-shadow;
  margin-bottom: 2rem;

  h2 {
    margin: 0 0 1rem 0;
    font-size: 1.25rem;
  }

  .chart-wrapper {
    position: relative;
    width: 100%;
    min-height: 250px;
  }
}

// Posts section
.posts-section {
  background: white;
  padding: 1.25rem;
  border-radius: $border-radius;
  box-shadow: $box-shadow;

  .section-header {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;

    @media (min-width: $breakpoint-md) {
      flex-direction: row;
      justify-content: space-between;
      align-items: center;
    }

    h2 {
      margin: 0;
      font-size: 1.25rem;
    }
  }

  .filters {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 100%;

    @media (min-width: $breakpoint-md) {
      flex-direction: row;
      align-items: center;
      width: auto;
    }
  }

  .filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    width: 100%;

    @media (min-width: $breakpoint-md) {
      width: auto;
    }

    label {
      font-size: 0.8rem;
      color: $light-text;
    }
  }

  .filter-select {
    padding: 0.5rem 1rem;
    border-radius: $border-radius;
    border: 1px solid #ddd;
    background: white;
    font-size: 0.9rem;
    width: 100%;

    @media (min-width: $breakpoint-md) {
      width: auto;
      min-width: 150px;
    }

    &:focus {
      outline: none;
      border-color: $primary-color;
    }
  }
}

// Table styles
.posts-table {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;

  .table-responsive {
    min-width: 600px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;

    th,
    td {
      padding: 0.75rem 1rem;
      text-align: left;
      border-bottom: 1px solid #eee;

      @media (min-width: $breakpoint-md) {
        padding: 1rem;
      }
    }

    td {
      a {
        color: $primary-color;
        text-decoration: none;
        font-weight: 500;

        &:hover {
          text-decoration: underline;
        }
      }
    }

    tr:hover td {
      background: lighten($primary-color, 45%);
    }
  }
}

// Status badges
.status-badge {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 500;
  text-transform: capitalize;

  &.published {
    background: rgba($success, 0.1);
    color: darken($success, 20%);
  }

  &.draft {
    background: rgba($warning, 0.1);
    color: darken($warning, 20%);
  }

  &.scheduled {
    background: rgba($primary-color, 0.1);
    color: darken($primary-color, 20%);
  }
}

// Actions
.actions {
  display: flex;
  gap: 0.5rem;
}

// Pagination
.pagination {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
  margin-top: 1.5rem;
  padding-top: 1.5rem;
  border-top: 1px solid #eee;

  @media (min-width: $breakpoint-md) {
    flex-direction: row;
    justify-content: center;
  }

  > span {
    font-size: 0.9rem;
    color: $light-text;
  }
}

// Button styles
.btn {
  padding: 0.5rem 1rem;
  border-radius: $border-radius;
  border: none;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  font-size: 0.9rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  white-space: nowrap;

  &.primary {
    background: $primary-color;
    color: white;

    &:hover {
      background: darken($primary-color, 10%);
    }
  }

  &.secondary {
    background: white;
    border: 1px solid #ddd;
    color: $light-text;

    &:hover {
      border-color: $primary-color;
      color: $primary-color;
    }
  }

  &:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
}

.icon-btn {
  background: none;
  border: none;
  color: $light-text;
  cursor: pointer;
  padding: 0.25rem;
  font-size: 1rem;

  &:hover {
    color: $primary-color;
  }
}

// Mobile table styles
@media (max-width: $breakpoint-md) {
  .table-responsive {
    thead {
      display: none;
    }

    tr {
      display: block;
      margin-bottom: 1rem;
      border-bottom: 2px solid #eee;
    }

    td {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.5rem 1rem;
      text-align: right;
      border-bottom: 1px dotted #eee;

      &::before {
        content: attr(data-label);
        font-weight: 600;
        color: $light-text;
        margin-right: 1rem;
        text-align: left;
      }

      &.actions {
        justify-content: flex-end;
      }
    }
  }
}
</style>
