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
import { api } from '@/js/api'

export default {
  name: 'CreateBlogPage',
  metaInfo: {
    title: 'Blog: Create Page',
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
      },
    }
  },
  methods: {
    async onSubmit() {
      const data = serialize(this.page, {
        nullsAsUndefineds: true,
      })
      await api
        .post('/blog/page', data)
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
