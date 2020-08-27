<template>
  <div class="box">
    <ValidationObserver ref="form" v-slot="{ passes }">
      <form @submit.prevent="passes(onSubmit)">
        <div class="columns is-multiline">
          <div class="column is-12">
            <FormInput v-model="page.title" label="Title" name="title" />
          </div>
          <div class="column is-12">
            <FormInput
              v-model="page.slug"
              label="Slug (Optional)"
              name="slug"
              message="If the slug is not available, it will be automatically generated based on the title."
            />
          </div>
          <div class="column is-12">
            <FormRichtext
              v-model="page.content"
              label="Content"
              name="content"
            />
          </div>
          <div class="column is-12">
            <FormImage
              v-model="page.image"
              label="Image"
              name="image"
              message="For best results, use an image with an aspect ratio of 16:9."
              :preview="page.thumbnail"
              :rounded="false"
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
import pick from 'lodash/pick'
import { api } from '@/js/api'

export default {
  name: 'BlogPageDetail',
  metaInfo: {
    title: 'Blog: Page Detail',
  },
  components: {
    ValidationObserver,
    FormInput: () => import('@/js/components/form/Input'),
    FormImage: () => import('@/js/components/form/Image'),
    FormRichtext: () => import('@/js/components/form/Richtext'),
    SaveButton: () => import('@/js/components/SaveButton'),
  },
  data() {
    return {
      page: {
        title: '',
        slug: null,
        content: '',
        image: null,
        thumbnail: null,
      },
    }
  },
  mounted() {
    this.fetchData()
  },
  methods: {
    async fetchData() {
      await api
        .get(`/blog/page/${this.$route.params.id}`)
        .then(({ data: { data } }) => {
          this.page = pick(data, ['title', 'slug', 'content', 'thumbnail'])
        })
        .catch(() => {
          this.page = {
            title: '',
            slug: null,
            content: '',
            image: null,
            thumbnail: null,
          }
        })
    },
    async onSubmit() {
      const data = serialize(
        {
          ...this.page,
          _method: 'PUT',
        },
        {
          nullsAsUndefineds: true,
        }
      )
      await api
        .post(`/blog/page/${this.$route.params.id}`, data)
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
