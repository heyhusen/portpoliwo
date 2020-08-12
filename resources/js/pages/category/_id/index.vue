<template>
  <div class="box">
    <ValidationObserver ref="form" v-slot="{ passes }">
      <form @submit.prevent="passes(onSubmit)">
        <div class="columns is-multiline">
          <div class="column is-half-tablet">
            <FormInput v-model="category.name" label="Name" name="name" />
          </div>
          <div class="column is-half-tablet">
            <FormInput
              v-model="category.slug"
              label="Slug (Optional)"
              name="slug"
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
import { api } from '@/js/api'

export default {
  name: 'CategoryDetail',
  components: {
    ValidationObserver,
    FormInput: () => import('@/js/components/form/Input'),
    SaveButton: () => import('@/js/components/SaveButton'),
  },
  data() {
    return {
      category: {
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
        .get(`/category/${this.$route.params.id}`)
        .then((response) => {
          this.category.name = response.data.data.name
          this.category.slug = response.data.data.slug
        })
        .catch(() => {
          this.category = {
            name: '',
            slug: '',
          }
        })
    },
    async onSubmit() {
      await api
        .put(`/category/${this.$route.params.id}`, this.category)
        .then((response) => {
          if (response.data.success) {
            this.$buefy.toast.open({
              message: response.data.message,
              type: 'is-success',
            })
          }
          this.fetchData()
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
