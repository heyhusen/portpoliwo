<template>
  <div class="box">
    <ValidationObserver ref="form" v-slot="{ passes }">
      <form @submit.prevent="passes(onSubmit)">
        <div class="columns is-multiline">
          <div class="column is-12">
            <FormInput v-model="post.title" label="Title" name="title" />
          </div>
          <div class="column is-12">
            <FormInput
              v-model="post.slug"
              label="Slug (Optional)"
              name="slug"
              message="If the slug is not available, it will be automatically generated based on the title."
            />
          </div>
          <div class="column is-12">
            <FormInput
              v-model="post.summary"
              label="Summary (Optional)"
              name="summary"
              type="textarea"
            />
          </div>
          <div class="column is-12">
            <FormRichtext
              v-model="post.content"
              label="Content"
              name="content"
            />
          </div>
          <div class="column is-12">
            <FormImage
              v-model="post.image"
              label="Image"
              name="image"
              message="For best results, use an image with an aspect ratio of 16:9."
              :rounded="false"
            />
          </div>
          <div class="column is-12">
            <FormTagInput
              v-model="post.blog_category_id"
              label="Categories"
              name="blog_category_id"
              :data="category.data"
              :loading="category.isFetching"
              field="title"
              icon="folder-table"
              placeholder="Add a category"
              @typing="getFilteredCategories"
            />
          </div>
          <div class="column is-12">
            <FormTagInput
              v-model="post.blog_tag_id"
              label="Tags"
              name="blog_tag_id"
              :data="tag.data"
              :loading="tag.isFetching"
              field="title"
              icon="folder-table"
              placeholder="Add a tag"
              @typing="getFilteredTags"
            />
          </div>
        </div>
        <hr />
        <SaveButton />
      </form>
    </ValidationObserver>
  </div>
</template>

<script>
import { ValidationObserver } from 'vee-validate'
import { serialize } from 'object-to-formdata'
import map from 'lodash/map'
import { api } from '@/js/api'

export default {
  name: 'CreateBlogPost',
  metaInfo: {
    title: 'Blog: Create Post',
  },
  components: {
    ValidationObserver,
    FormInput: () => import('@/js/components/form/Input'),
    FormImage: () => import('@/js/components/form/Image'),
    FormTagInput: () => import('@/js/components/form/TagInput'),
    FormRichtext: () => import('@/js/components/form/Richtext'),
    SaveButton: () => import('@/js/components/SaveButton'),
  },
  data() {
    return {
      category: {
        data: [],
        isFetching: false,
      },
      tag: {
        data: [],
        isFetching: false,
      },
      post: {
        title: '',
        slug: null,
        summary: null,
        content: '',
        image: null,
        blog_category_id: [],
        blog_tag_id: [],
      },
    }
  },
  methods: {
    async getFilteredCategories(name) {
      if (!name.length) {
        this.category.data = []
        return
      }
      this.category.isFetching = true
      await api
        .get(`/blog/category?search=${name}`)
        .then(({ data }) => {
          this.category.data = []
          data.data.forEach((item) => this.category.data.push(item))
        })
        .catch((error) => {
          this.category.data = []
          throw error
        })
        .finally(() => {
          this.category.isFetching = false
        })
    },
    async getFilteredTags(name) {
      if (!name.length) {
        this.tag.data = []
        return
      }
      this.tag.isFetching = true
      await api
        .get(`/blog/tag?search=${name}`)
        .then(({ data }) => {
          this.tag.data = []
          data.data.forEach((item) => this.tag.data.push(item))
        })
        .catch((error) => {
          this.tag.data = []
          throw error
        })
        .finally(() => {
          this.tag.isFetching = false
        })
    },
    async onSubmit() {
      const data = serialize(
        {
          ...this.post,
          blog_category_id: map(this.post.blog_category_id, 'id'),
          blog_tag_id: map(this.post.blog_tag_id, 'id'),
        },
        {
          nullsAsUndefineds: true,
        }
      )
      await api
        .post('/blog', data)
        .then(({ data }) => {
          if (data.success) {
            this.$buefy.toast.open({
              message: data.message,
              type: 'is-success',
            })
          }
          this.$router.back()
        })
        .catch(({ response: { data } }) => {
          if (data.errors) {
            this.$refs.form.setErrors(data.errors)
          }
          this.$buefy.toast.open({
            message: data.message,
            type: 'is-danger',
          })
        })
    },
  },
}
</script>
