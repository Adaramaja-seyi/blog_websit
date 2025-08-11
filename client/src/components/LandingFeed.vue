<template>
  <div class="app-container">
    <!-- Header/Navbar -->
    <header class="app-header">
      <div class="header-content">
        <div class="logo">
          <h1>SocialConnect</h1>
        </div>

        <div class="search-bar">
          <i class="bi bi-search"></i>
          <input type="text" placeholder="Search SocialConnect" />
        </div>

        <nav class="main-nav">
          <router-link to="/" class="nav-item active">
            <i class="bi bi-house-door"></i>
          </router-link>
          <router-link to="/friends" class="nav-item">
            <i class="bi bi-people"></i>
          </router-link>
          <router-link to="/watch" class="nav-item">
            <i class="bi bi-play-circle"></i>
          </router-link>
          <router-link to="/marketplace" class="nav-item">
            <i class="bi bi-shop"></i>
          </router-link>
        </nav>
      </div>
    </header>

    <!-- Main Content -->
    <main class="app-main">
      <div class="main-content">
        <!-- Left Sidebar -->
        <aside class="left-sidebar">
          <div class="sidebar-footer">
            <p>Privacy · Terms · Advertising · Cookies · More</p>
            <p>© 2025 SocialConnect</p>
          </div>
        </aside>

        <!-- Main Feed -->
        <div class="feed-container">
          <!-- Stories Section -->

          <!-- Post Creation -->
          <div class="post-creator">
            <div class="creator-header">
              <img
                :src="userProfilePicture"
                class="creator-avatar"
                alt="User"
              />
              <button class="creator-prompt" @click="showPostModal = true">
                What's on your mind, {{ userName.split(" ")[0] }}?
              </button>
            </div>

            <div class="creator-options">
              <button class="option-item">
                <i class="bi bi-camera-video-fill live-video"></i>
                <span>Live Video</span>
              </button>
              <button class="option-item">
                <i class="bi bi-image-fill photo"></i>
                <span>Photo/Video</span>
              </button>
              <button class="option-item">
                <i class="bi bi-emoji-smile-fill feeling"></i>
                <span>Feeling/Activity</span>
              </button>
            </div>
          </div>

          <!-- Modal for PostForm -->
          <div v-if="showPostModal" class="modal-overlay">
            <div class="modal-content">
              <button class="modal-close" @click="showPostModal = false">
                &times;
              </button>
              <PostForm
                @post-updated="handlePostCreated"
                @close="showPostModal = false"
              />
            </div>
          </div>

          <!-- Posts Feed -->
          <div class="posts-feed">
            

            <div class="posts-list">
              <div
                v-for="post in filteredPosts"
                :key="post.id"
                class="post-card"
              >
                <div class="post-header">
                  <div class="post-user">
                    <img
                      :src="post.user?.profile_picture || '/default-avatar.png'"
                      class="post-avatar"
                      alt="User"
                    />
                    <div class="post-user-info">
                      <h4>{{ post.user?.name || "Anonymous" }}</h4>
                      <span class="post-time">{{
                        formatTime(post.created_at)
                      }}</span>
                      <span class="post-audience">
                        <i
                          class="bi bi-globe"
                          v-if="post.audience === 'public'"
                        ></i>
                        <i
                          class="bi bi-people-fill"
                          v-else-if="post.audience === 'friends'"
                        ></i>
                        <i class="bi bi-lock-fill" v-else></i>
                      </span>
                    </div>
                  </div>
                  <button class="post-menu">
                    <i class="bi bi-three-dots"></i>
                  </button>
                </div>

                <div class="post-content">
                  <p>
                    {{ getPostContent(post) }}
                    <span v-if="post.content && post.content.length > 250">
                      <button
                        class="see-more-btn"
                        @click="toggleSeeMore(post.id)"
                      >
                        {{
                          expandedPosts.includes(post.id)
                            ? "See less"
                            : "See more"
                        }}
                      </button>
                    </span>
                  </p>
                  <div class="post-image" v-if="post.image">
                    <img :src="post.image" alt="Post image" />
                  </div>
                </div>

                <div class="post-stats">
                  <div class="stats-item">
                    <i class="bi bi-hand-thumbs-up"></i>
                    <span>{{ post.likes_count || 0 }}</span>
                  </div>
                  <div class="stats-item">
                    <span>{{ post.comments_count || 0 }} comments</span>
                    <span>{{ post.shares_count || 0 }} shares</span>
                  </div>
                </div>

                <div class="post-actions">
                  <button
                    @click="likePost(post.id)"
                    class="action-btn"
                    :class="{ liked: post.is_liked }"
                  >
                    <i
                      class="bi"
                      :class="
                        post.is_liked
                          ? 'bi-hand-thumbs-up-fill'
                          : 'bi-hand-thumbs-up'
                      "
                    ></i>
                    <span>Like</span>
                  </button>

                  <button @click="toggleComments(post.id)" class="action-btn">
                    <i class="bi bi-chat"></i>
                    <span>Comment</span>
                  </button>

                  <button @click="sharePost(post.id)" class="action-btn">
                    <i class="bi bi-share"></i>
                    <span>Share</span>
                  </button>
                </div>

                <!-- Comments Section -->
                <div v-if="post.showComments" class="comments-section">
                  <div class="comment-form">
                    <img
                      :src="userProfilePicture"
                      class="comment-avatar"
                      alt="User"
                    />
                    <div class="comment-input-container">
                      <input
                        v-model="newComment[post.id]"
                        type="text"
                        placeholder="Write a comment..."
                        class="comment-input"
                        @keyup.enter="addComment(post.id)"
                      />
                      <button
                        class="send-comment-btn"
                        @click="addComment(post.id)"
                      >
                        <i class="bi bi-send"></i> Send
                      </button>
                      <div class="comment-options">
                        <button class="comment-option">
                          <i class="bi bi-emoji-smile"></i>
                        </button>
                        <button class="comment-option">
                          <i class="bi bi-camera"></i>
                        </button>
                        <button class="comment-option">
                          <i class="bi bi-gift"></i>
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="comments-list">
                    <div
                      v-for="comment in post.comments"
                      :key="comment.id"
                      class="comment-item"
                    >
                      <img
                        :src="
                          comment.user && comment.user.profile_picture
                            ? comment.user.profile_picture
                            : '/default-avatar.png'
                        "
                        class="comment-avatar"
                        alt="User"
                      />
                      <div class="comment-content">
                        <div class="comment-user">
                          <span class="comment-name">{{
                            comment.user && comment.user.name
                              ? comment.user.name
                              : "Anonymous"
                          }}</span>
                          <span class="comment-text">{{
                            comment.content
                          }}</span>
                        </div>
                        <div class="comment-actions">
                          <span class="comment-time">{{
                            formatTime(comment.created_at)
                          }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Load More -->
            <div v-if="hasMorePosts" class="load-more">
              <button
                @click="loadMorePosts"
                class="load-more-btn"
                :disabled="loading"
              >
                {{ loading ? "Loading..." : "Load More Posts" }}
              </button>
            </div>
          </div>
        </div>

        <!-- Right Sidebar -->
        <aside class="right-sidebar">
          <div class="sidebar-section">
            <div class="sidebar-header">
              <h3>Contacts</h3>
              <div class="contact-actions">
                <button class="contact-action">
                  <i class="bi bi-search"></i>
                </button>
                <button class="contact-action">
                  <i class="bi bi-three-dots"></i>
                </button>
              </div>
            </div>
            <div class="contacts-list">
              <div
                class="contact-item"
                v-for="contact in contacts"
                :key="contact.id"
              >
                <div
                  class="contact-status"
                  :class="{ online: contact.online }"
                ></div>
                <img
                  :src="contact.profile_picture"
                  class="contact-avatar"
                  alt="Contact"
                />
                <span>{{ contact.name }}</span>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </main>
  </div>
</template>

<script>
import PostForm from "@/components/posts/PostForm.vue";
import api from "@/services/api";

export default {
  name: "FacebookFeed",
  components: {
    PostForm,
  },
  data() {
    return {
      userProfilePicture: "/default-avatar.png",
      userName: "User Name",
      stories: [],
      contacts: [],
      posts: [],
      expandedPosts: [],
      newPost: {
        content: "",
        audience: "public",
      },
      newComment: {},
      showPostModal: false,
      loading: false,
      hasMorePosts: false,
      page: 1,
    };
  },
  created() {
    this.fetchUserData();
    this.fetchStories();
    this.fetchContacts();
    this.fetchPosts();
  },
  computed: {
    filteredPosts() {
      return this.posts; // Filtering logic can be added here
    },
  },
  methods: {
    async fetchUserData() {
      try {
        const user = JSON.parse(localStorage.getItem("user"));
        if (user) {
          this.userName = user.name;
          this.userProfilePicture =
            user.profile_picture || "/default-avatar.png";
        }
      } catch (error) {
        console.error("Error fetching user data:", error);
      }
    },

    async fetchStories() {
      try {
        const response = await api.getStories();
        this.stories = response.data || [];
      } catch (error) {
        console.error("Error fetching stories:", error);
      }
    },

    async fetchContacts() {
      try {
        const response = await api.getContacts();
        this.contacts = response.data || [];
      } catch (error) {
        console.error("Error fetching contacts:", error);
      }
    },

    async fetchPosts() {
      try {
        this.loading = true;
        const response = await api.getPosts({ page: this.page });
        this.posts = response.data.data || [];
        this.hasMorePosts =
          response.data.data && response.data.data.length >= 10;
      } catch (error) {
        console.error("Error fetching posts:", error);
      } finally {
        this.loading = false;
      }
    },

    focusPostInput() {
      this.showPostForm = true;
      this.$nextTick(() => {
        this.$refs.postInput.focus();
      });
    },

    async createPost() {
      try {
        this.loading = true;
        const response = await api.createPost(this.newPost);
        this.posts.unshift(response.data);
        this.newPost = { content: "", audience: "public" };
        this.showPostForm = false;
        this.$toast.success("Post created successfully!");
      } catch (error) {
        this.$toast.error("Error creating post");
      } finally {
        this.loading = false;
      }
    },

    async likePost(postId) {
      const post = this.posts.find((p) => p.id === postId);
      if (!post) return;

      try {
        if (post.is_liked) {
          // Unlike
          await api.unlikePost(postId);
          post.is_liked = false;
          post.likes_count = (post.likes_count || 1) - 1;
        } else {
          // Like
          await api.likePost(postId);
          post.is_liked = true;
          post.likes_count = (post.likes_count || 0) + 1;
        }
      } catch (error) {
        this.$toast.error("Error updating like status");
      }
    },

    async addComment(postId) {
      if (!this.newComment[postId]?.trim()) return;

      try {
        const response = await api.addComment(postId, {
          content: this.newComment[postId],
        });
        const post = this.posts.find((p) => p.id === postId);
        if (post) {
          post.comments = post.comments || [];
          post.comments.unshift(response.data.comment);
          post.comments_count = post.comments.length;
          this.newComment[postId] = "";
        }
      } catch (error) {
        this.$toast.error("Error adding comment");
      }
    },

    async loadMorePosts() {
      this.page += 1;
      await this.fetchPosts();
    },

    formatTime(date) {
      const now = new Date();
      const postDate = new Date(date);
      const diffHours = Math.floor((now - postDate) / (1000 * 60 * 60));

      if (diffHours < 1) {
        const diffMinutes = Math.floor((now - postDate) / (1000 * 60));
        return `${diffMinutes}m ago`;
      } else if (diffHours < 24) {
        return `${diffHours}h ago`;
      } else {
        return postDate.toLocaleDateString("en-US", {
          month: "short",
          day: "numeric",
        });
      }
    },

    handleLogout() {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      this.$router.push("/login");
    },

    toggleComments(postId) {
      const post = this.posts.find((p) => p.id === postId);
      if (post) {
        post.showComments = !post.showComments;
        if (post.showComments && !post.comments) {
          this.fetchComments(postId);
        }
      }
    },

    async fetchComments(postId) {
      try {
        const response = await api.getComments(postId);
        const post = this.posts.find((p) => p.id === postId);
        if (post) {
          post.comments = response.data || [];
        }
      } catch (error) {
        console.error("Error fetching comments:", error);
      }
    },

    sharePost(postId) {
      const post = this.posts.find((p) => p.id === postId);
      if (!post) return;
      const postUrl = `${window.location.origin}/post/${postId}`;
      if (navigator.share) {
        navigator
          .share({
            title: post.title || "Check out this post!",
            text: post.content || "",
            url: postUrl,
          })
          .then(() => {
            this.$toast.success("Post shared successfully!");
          })
          .catch(() => {
            this.$toast.error("Sharing failed.");
          });
      } else {
        // Fallback: copy link to clipboard
        navigator.clipboard
          .writeText(postUrl)
          .then(() => {
            this.$toast.success("Post link copied to clipboard!");
          })
          .catch(() => {
            this.$toast.error("Could not copy link.");
          });
      }
    },

    getPostContent(post) {
      const maxLength = 250;
      if (
        this.expandedPosts.includes(post.id) ||
        !post.content ||
        post.content.length <= maxLength
      ) {
        return post.content;
      }
      return post.content.slice(0, maxLength) + "...";
    },
    toggleSeeMore(postId) {
      if (this.expandedPosts.includes(postId)) {
        this.expandedPosts = this.expandedPosts.filter((id) => id !== postId);
      } else {
        this.expandedPosts.push(postId);
      }
    },

    handlePostCreated() {
      this.showPostModal = false;
      this.fetchPosts();
    },
  },
};
</script>

<style scoped>
/* Base Styles */
:root {
  --primary-color: #1877f2;
  --secondary-color: #42b72a;
  --bg-color: #f0f2f5;
  --card-bg: #ffffff;
  --text-primary: #050505;
  --text-secondary: #65676b;
  --border-color: #dddfe2;
  --hover-bg: #f2f2f2;
  --divider-color: #ced0d4;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

body {
  background-color: var(--bg-color);
}

/* Header Styles */
.app-header {
  background-color: var(--card-bg);
  height: 56px;
  padding: 0 16px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo h1 {
  color: var(--primary-color);
  font-size: 28px;
  font-weight: 700;
  cursor: pointer;
}

.search-bar {
  display: flex;
  align-items: center;
  background-color: #f0f2f5;
  border-radius: 50px;
  padding: 8px 12px;
  width: 240px;
}

.search-bar i {
  color: var(--text-secondary);
  margin-right: 8px;
}

.search-bar input {
  border: none;
  background: transparent;
  outline: none;
  width: 100%;
  font-size: 15px;
}

.main-nav {
  display: flex;
  height: 100%;
}

.nav-item {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 120px;
  color: var(--text-secondary);
  text-decoration: none;
  font-size: 24px;
  position: relative;
}

.nav-item.active {
  color: var(--primary-color);
}

.nav-item.active::after {
  content: "";
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 3px;
  background-color: var(--primary-color);
}

.user-actions {
  display: flex;
  align-items: center;
  gap: 8px;
}

.user-menu {
  display: flex;
  align-items: center;
  padding: 4px 8px;
  border-radius: 50px;
  cursor: pointer;
}

.user-menu:hover {
  background-color: var(--hover-bg);
}

.user-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 8px;
}

.user-name {
  font-weight: 600;
  font-size: 15px;
}

.action-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: #e4e6eb;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
  cursor: pointer;
}

.action-icon:hover {
  background-color: #d8dadf;
}

/* Main Content Layout */
.app-main {
  padding-top: 16px;
  min-height: calc(100vh - 56px);
}

.main-content {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 280px 1fr 280px;
  gap: 16px;
}

/* Sidebar Styles */
.left-sidebar,
.right-sidebar {
  position: sticky;
  top: 72px;
  height: calc(100vh - 72px);
  overflow-y: auto;
  padding-bottom: 20px;
}

.left-sidebar {
  padding-right: 8px;
}

.sidebar-section {
  background-color: var(--card-bg);
  border-radius: 8px;
  padding: 12px 0;
  margin-bottom: 16px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.sidebar-item {
  display: flex;
  align-items: center;
  padding: 8px 12px;
  margin: 0 8px;
  border-radius: 8px;
  text-decoration: none;
  color: var(--text-primary);
  font-weight: 500;
}

.sidebar-item:hover {
  background-color: var(--hover-bg);
}

.sidebar-item i {
  font-size: 24px;
  margin-right: 12px;
  color: var(--primary-color);
}

.sidebar-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 12px;
}

.sidebar-footer {
  padding: 16px;
  font-size: 13px;
  color: var(--text-secondary);
  line-height: 1.5;
}

/* Right Sidebar Styles */
.sidebar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 12px 12px;
}

.sidebar-header h3 {
  font-size: 17px;
  color: var(--text-secondary);
}

.contact-actions {
  display: flex;
  gap: 8px;
}

.contact-action {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background-color: #e4e6eb;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  color: var(--text-primary);
  cursor: pointer;
}

.sponsored-item {
  padding: 8px 12px;
  display: flex;
  align-items: center;
}

.sponsored-item img {
  width: 100px;
  height: 100px;
  border-radius: 8px;
  object-fit: cover;
  margin-right: 12px;
}

.sponsored-info h4 {
  font-size: 15px;
  margin-bottom: 4px;
}

.sponsored-info p {
  font-size: 13px;
  color: var(--text-secondary);
}

.birthday-item {
  display: flex;
  align-items: center;
  padding: 8px 12px;
}

.birthday-item i {
  font-size: 24px;
  color: var(--primary-color);
  margin-right: 12px;
}

.birthday-item p {
  font-size: 15px;
}

.contacts-list {
  padding: 0 8px;
}

.contact-item {
  display: flex;
  align-items: center;
  padding: 8px;
  border-radius: 8px;
  position: relative;
  cursor: pointer;
}

.contact-item:hover {
  background-color: var(--hover-bg);
}

.contact-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 12px;
}

.contact-status {
  position: absolute;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background-color: #bcc0c4;
  border: 2px solid var(--card-bg);
  bottom: 10px;
  left: 30px;
}

.contact-status.online {
  background-color: #31a24c;
}

/* Feed Container */
.feed-container {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

/* Stories Section */
.stories-section {
  display: flex;
  gap: 8px;
  padding: 16px;
  background-color: var(--card-bg);
  border-radius: 8px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  overflow-x: auto;
}

.story {
  min-width: 110px;
  height: 200px;
  border-radius: 8px;
  position: relative;
  cursor: pointer;
  flex-shrink: 0;
}

.story::after {
  content: "";
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 40%;
  background: linear-gradient(transparent, rgba(0, 0, 0, 0.5));
  border-radius: 0 0 8px 8px;
}

.story-content {
  width: 100%;
  height: 100%;
  border-radius: 8px;
  overflow: hidden;
  position: relative;
}

.story-content img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.story-footer {
  position: absolute;
  bottom: 8px;
  left: 8px;
  right: 8px;
  z-index: 1;
  color: white;
  font-weight: 600;
  font-size: 13px;
}

.create-story {
  background-color: var(--card-bg);
  border: 1px solid var(--border-color);
}

.create-story .story-content {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.create-story .story-avatar {
  width: 100%;
  height: 70%;
  border-bottom: 1px solid var(--border-color);
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #f0f2f5;
}

.create-story .story-avatar img {
  width: 40px;
  height: 40px;
  border: 4px solid var(--primary-color);
}

.create-story .story-footer {
  color: var(--text-primary);
  text-align: center;
}

.create-story .story-footer i {
  color: var(--primary-color);
  font-size: 20px;
  display: block;
  margin-bottom: 4px;
}

/* Post Creator */
.post-creator {
  background-color: var(--card-bg);
  border-radius: 8px;
  padding: 12px 16px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.creator-header {
  display: flex;
  align-items: center;
  margin-bottom: 12px;
}

.creator-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 12px;
}

.creator-prompt {
  flex: 1;
  height: 40px;
  border-radius: 20px;
  background-color: #f0f2f5;
  border: none;
  padding: 0 16px;
  text-align: left;
  font-size: 15px;
  color: var(--text-secondary);
  cursor: pointer;
}

.creator-prompt:hover {
  background-color: #e4e6e9;
}

.creator-options {
  display: flex;
  justify-content: space-between;
  padding-top: 8px;
  border-top: 1px solid var(--border-color);
}

.option-item {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 8px;
  border-radius: 4px;
  border: none;
  background: none;
  font-weight: 600;
  font-size: 14px;
  color: var(--text-secondary);
  cursor: pointer;
}

.option-item:hover {
  background-color: var(--hover-bg);
}

.option-item i {
  margin-right: 8px;
  font-size: 20px;
}

.option-item .live-video {
  color: #f3425f;
}

.option-item .photo {
  color: #45bd62;
}

.option-item .feeling {
  color: #f7b928;
}

/* Post Form */
.post-form-container {
  background-color: var(--card-bg);
  border-radius: 8px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.post-form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px;
  border-bottom: 1px solid var(--border-color);
}

.post-form-header h3 {
  font-size: 20px;
  font-weight: 700;
}

.close-btn {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: none;
  background-color: #e4e6eb;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.close-btn:hover {
  background-color: #d8dadf;
}

.post-form {
  padding: 16px;
}

.form-user {
  display: flex;
  align-items: center;
  margin-bottom: 16px;
}

.form-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 12px;
}

.form-user h4 {
  font-size: 15px;
  margin-bottom: 4px;
}

.audience-select {
  background-color: #e4e6eb;
  border: none;
  border-radius: 6px;
  padding: 4px 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}

.post-textarea {
  width: 100%;
  border: none;
  outline: none;
  resize: none;
  font-size: 24px;
  margin-bottom: 16px;
  padding: 8px;
}

.form-actions {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px;
  border: 1px solid var(--border-color);
  border-radius: 8px;
}

.add-to-post {
  display: flex;
  gap: 4px;
}

.add-item {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: none;
  background: none;
  font-size: 20px;
  color: var(--text-secondary);
  cursor: pointer;
}

.add-item:hover {
  background-color: var(--hover-bg);
}

.post-btn {
  background-color: var(--primary-color);
  color: white;
  border: none;
  border-radius: 6px;
  padding: 8px 16px;
  font-weight: 600;
  cursor: pointer;
}

.post-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.post-btn:hover:not(:disabled) {
  background-color: #166fe5;
}

/* Posts Feed */
.feed-filter {
  background-color: var(--card-bg);
  border-radius: 8px;
  padding: 12px;
  display: flex;
  gap: 8px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.filter-btn {
  flex: 1;
  padding: 8px;
  border: none;
  border-radius: 6px;
  background: none;
  font-weight: 600;
  cursor: pointer;
}

.filter-btn.active {
  background-color: #e7f3ff;
  color: var(--primary-color);
}

.filter-btn:hover:not(.active) {
  background-color: var(--hover-bg);
}

/* Post Card */
.post-card {
  background-color: var(--card-bg);
  border-radius: 8px;
  padding: 12px 16px;
  margin-bottom: 16px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.post-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}

.post-user {
  display: flex;
  align-items: center;
}

.post-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 12px;
}

.post-user-info {
  display: flex;
  flex-direction: column;
}

.post-user-info h4 {
  font-size: 15px;
  font-weight: 600;
}

.post-time {
  font-size: 13px;
  color: var(--text-secondary);
}

.post-audience {
  font-size: 12px;
  color: var(--text-secondary);
}

.post-menu {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: none;
  background: none;
  font-size: 20px;
  color: var(--text-secondary);
  cursor: pointer;
}

.post-menu:hover {
  background-color: var(--hover-bg);
}

.post-content {
  margin-bottom: 12px;
}

.post-content p {
  font-size: 15px;
  line-height: 1.4;
  margin-bottom: 12px;
}

.post-image {
  width: 100%;
  border-radius: 8px;
  overflow: hidden;
}

.post-image img {
  width: 100%;
  max-height: 500px;
  object-fit: cover;
}

.post-stats {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 8px 0;
  border-bottom: 1px solid var(--border-color);
  margin-bottom: 8px;
  font-size: 15px;
  color: var(--text-secondary);
}

.stats-item {
  display: flex;
  align-items: center;
}

.stats-item i {
  margin-right: 4px;
  color: var(--primary-color);
}

.post-actions {
  display: flex;
  justify-content: space-between;
  padding: 4px 0;
}

.action-btn {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 8px;
  border: none;
  background: none;
  border-radius: 4px;
  font-weight: 600;
  color: var(--text-secondary);
  cursor: pointer;
}

.action-btn:hover {
  background-color: var(--hover-bg);
}

.action-btn.liked {
  color: var(--primary-color);
}

/* Comments Section */
.comments-section {
  margin-top: 12px;
  padding-top: 12px;
  border-top: 1px solid var(--border-color);
}

.comment-form {
  display: flex;
  align-items: center;
  margin-bottom: 12px;
}

.comment-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 8px;
}

.comment-input-container {
  flex: 1;
  position: relative;
}

.comment-input {
  width: 100%;
  padding: 8px 12px;
  border-radius: 18px;
  border: none;
  background-color: #f0f2f5;
  outline: none;
  font-size: 15px;
}

.comment-options {
  position: absolute;
  right: 8px;
  top: 50%;
  transform: translateY(-50%);
  display: flex;
  gap: 4px;
}

.comment-option {
  width: 24px;
  height: 24px;
  border: none;
  background: none;
  color: var(--text-secondary);
  cursor: pointer;
}

.comments-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.comment-item {
  display: flex;
  gap: 8px;
}

.comment-content {
  flex: 1;
  background-color: #f0f2f5;
  padding: 8px 12px;
  border-radius: 18px;
}

.comment-user {
  display: flex;
  align-items: center;
  margin-bottom: 4px;
}

.comment-name {
  font-weight: 600;
  font-size: 13px;
  margin-right: 8px;
}

.comment-text {
  font-size: 15px;
}

.comment-actions {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 4px;
  font-size: 13px;
  color: var(--text-secondary);
}

.comment-action {
  background: none;
  border: none;
  color: var(--text-secondary);
  font-weight: 600;
  cursor: pointer;
}

.comment-time {
  font-size: 11px;
}

/* Load More Button */
.load-more {
  display: flex;
  justify-content: center;
  margin: 16px 0;
}

.load-more-btn {
  background-color: var(--primary-color);
  color: white;
  border: none;
  border-radius: 6px;
  padding: 12px 24px;
  font-weight: 600;
  cursor: pointer;
}

.load-more-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.load-more-btn:hover:not(:disabled) {
  background-color: #166fe5;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.modal-content {
  background: #fff;
  padding: 2rem;
  border-radius: 8px;
  min-width: 350px;
  max-width: 90vw;
  box-shadow: 0 2px 16px rgba(0, 0, 0, 0.2);
  position: relative;
}

.modal-close {
  position: absolute;
  top: 10px;
  right: 10px;
  background: none;
  border: none;
  font-size: 2rem;
  cursor: pointer;
}

/* Responsive Adjustments */
@media (max-width: 1200px) {
  .main-content {
    grid-template-columns: 80px 1fr 280px;
  }

  .left-sidebar .sidebar-item span {
    display: none;
  }

  .left-sidebar .sidebar-item i {
    margin-right: 0;
    font-size: 20px;
  }
}

@media (max-width: 900px) {
  .main-content {
    grid-template-columns: 1fr;
    padding: 0 16px;
  }

  .left-sidebar,
  .right-sidebar {
    display: none;
  }

  .header-content {
    padding: 0 8px;
  }

  .search-bar {
    width: 40px;
    justify-content: center;
  }

  .search-bar input {
    display: none;
  }

  .nav-item {
    width: 60px;
  }

  .user-name {
    display: none;
  }
}
</style>
