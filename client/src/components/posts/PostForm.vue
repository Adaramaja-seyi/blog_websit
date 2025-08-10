<template>
  <div>
    <h2>{{ isEdit ? "Edit Post" : "Create Post" }}</h2>
    <form @submit.prevent="handleSubmit">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input v-model="form.title" class="form-control" required />
      </div>
      <div class="mb-3">
        <label class="form-label">Content</label>
        <textarea
          v-model="form.content"
          class="form-control"
          rows="5"
          required
        ></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Status</label>
        <select v-model="form.is_published" class="form-select" required>
          <option :value="false">Draft</option>
          <option :value="true">Published</option>
        </select>
      </div>
      <button class="btn btn-success" type="submit">
        {{ isEdit ? "Update" : "Create" }}
      </button>
      <router-link class="btn btn-secondary ms-2" :to="{ name: 'posts' }"
        >Cancel</router-link
      >
    </form>
  </div>
</template>

<script>
import api from "../../services/api";
export default {
  name: "PostForm",
  data() {
    return {
      form: {
        title: "",
        content: "",
        is_published: false,
      },
      isEdit: false,
    };
  },
  created() {
    if (this.$route.params.id) {
      this.isEdit = true;
      this.fetchPost();
    }
  },
  methods: {
    async fetchPost() {
      try {
        const res = await api.getPost(this.$route.params.id);
        const postData = res.data.data || res.data;
        this.form = {
          title: postData.title,
          content: postData.content,
          is_published: postData.is_published,
        };
      } catch (e) {
        this.$toast && this.$toast.error("Failed to load post");
      }
    },
    async handleSubmit() {
      try {
        if (this.isEdit) {
          await api.updatePost(this.$route.params.id, this.form);
          this.$toast && this.$toast.success("Post updated successfully!");
        } else {
          await api.createPost(this.form);
          this.$toast && this.$toast.success("Post created successfully!");
        }
        // Emit event to parent (dashboard) to refresh posts if needed
        this.$emit("post-updated");
        // Try to go back to dashboard or posts list
        if (this.$route.name === "post-create") {
          this.$router.push({ name: "dashboard" });
        } else {
          this.$router.push({ name: "posts" });
        }
      } catch (e) {
        this.$toast && this.$toast.error("Failed to save post");
      }
    },
  },
};
</script>
