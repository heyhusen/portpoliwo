<template>
  <div class="box">
    <ValidationObserver ref="form" v-slot="{ passes }">
      <form @submit.prevent="passes(onSubmit)">
        <div class="columns is-multiline">
          <div class="column is-half-tablet">
            <FormInput v-model="tag.name" label="Name" name="name" />
          </div>
          <div class="column is-half-tablet">
            <FormInput v-model="tag.slug" label="Slug (Optional)" name="slug" />
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
import { api } from '@/js/api'
import pick from 'lodash/pick'

export default {
  name: 'TagDetail',
  components: {
    ValidationObserver,
    FormInput: () => import('@/js/components/form/Input'),
    SaveButton: () => import('@/js/components/SaveButton'),
  },
  data() {
    return {
      tag: {
        name: '',
        slug: '',
      },
    }
  },
  mounted() {
    this.fetchData()
  },
  methods: {
    async fetchData() {
      await api
        .get(`/tag/${this.$route.params.id}`)
        .then(({ data: { data } }) => {
          this.tag = pick(data, ['name', 'slug'])
        })
        .catch(() => {
          this.tag = {
            name: '',
            slug: '',
          }
        })
    },
    async onSubmit() {
      await api
        .put(`/tag/${this.$route.params.id}`, this.tag)
        .then(({ data }) => {
          if (data.success) {
            this.$buefy.toast.open({
              message: data.message,
              type: 'is-success',
            })
          }
          this.fetchData()
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
