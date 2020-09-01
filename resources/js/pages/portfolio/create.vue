<template>
  <div class="box">
    <ValidationObserver ref="form" v-slot="{ passes }">
      <form @submit.prevent="passes(onSubmit)">
        <div class="columns is-multiline">
          <div class="column is-half-tablet">
            <FormInput v-model="work.name" label="Name" name="name" />
          </div>
          <div class="column is-half-tablet">
            <FormInput
              v-model="work.description"
              label="Description"
              name="description"
              type="textarea"
            />
          </div>
          <div class="column is-half-tablet">
            <FormInput v-model="work.url" label="URL" name="url" />
          </div>
          <div class="column is-half-tablet">
            <FormImage
              v-model="work.photo"
              label="Photo"
              name="photo"
              message="For best results, use an image with an aspect ratio of 16:9."
              :rounded="false"
            />
          </div>
          <div class="column is-half-tablet">
            <FormTagInput
              v-model="work.category_id"
              label="Categories"
              name="category_id"
              :data="category.data"
              :loading="category.isFetching"
              field="name"
              icon="folder-table"
              placeholder="Add a category"
              @typing="getFilteredCategories"
            />
          </div>
          <div class="column is-half-tablet">
            <FormTagInput
              v-model="work.tag_id"
              label="Tags"
              name="tag_id"
              :data="tag.data"
              :loading="tag.isFetching"
              field="name"
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
  name: 'CreatePortfolioWork',
  metaInfo: {
    title: 'Portfolio: Create Work',
  },
  components: {
    ValidationObserver,
    FormInput: () => import('@/js/components/form/Input'),
    FormImage: () => import('@/js/components/form/Image'),
    FormTagInput: () => import('@/js/components/form/TagInput'),
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
      work: {
        name: '',
        description: '',
        url: '',
        photo: null,
        category_id: [],
        tag_id: [],
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
        .get(`/portfolio/category?search=${name}`)
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
        .get(`/portfolio/tag?search=${name}`)
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
          ...this.work,
          category_id: map(this.work.category_id, 'id'),
          tag_id: map(this.work.tag_id, 'id'),
        },
        {
          nullsAsUndefineds: true,
        }
      )
      await api
        .post('/portfolio', data)
        .then((response) => {
          if (response.data.success) {
            this.$buefy.toast.open({
              message: response.data.message,
              type: 'is-success',
            })
          }
          this.$router.back()
        })
        .catch((error) => {
          if (error.response.data.errors) {
            this.$refs.form.setErrors(error.response.data.errors)
          }
          this.$buefy.toast.open({
            message: error.response.data.message,
            type: 'is-danger',
          })
        })
    },
  },
}
</script>
