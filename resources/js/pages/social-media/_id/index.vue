<template>
  <div class="box">
    <ValidationObserver ref="form" v-slot="{ passes }">
      <form @submit.prevent="passes(onSubmit)">
        <div class="columns is-multiline">
          <div class="column is-half-tablet">
            <FormInput v-model="socialMedia.name" label="Name" name="name" />
          </div>
          <div class="column is-half-tablet">
            <FormInput
              v-model="socialMedia.icon"
              label="Icon"
              name="icon"
              :icon-right="socialMedia.icon"
            />
          </div>
          <div class="column is-half-tablet">
            <FormInput v-model="socialMedia.url" label="URL" name="url" />
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
  name: 'SocialMediaDetail',
  components: {
    ValidationObserver,
    FormInput: () => import('@/js/components/form/Input'),
    SaveButton: () => import('@/js/components/SaveButton'),
  },
  data() {
    return {
      socialMedia: {
        name: '',
        icon: '',
        url: '',
      },
    }
  },
  mounted() {
    this.fetchData()
  },
  methods: {
    async fetchData() {
      await api
        .get(`/social-media/${this.$route.params.id}`)
        .then((response) => {
          this.socialMedia.name = response.data.data.name
          this.socialMedia.icon = response.data.data.icon
          this.socialMedia.url = response.data.data.url
        })
        .catch(() => {
          this.socialMedia = {
            name: '',
            icon: '',
            url: '',
          }
        })
    },
    async onSubmit() {
      await api
        .put(`/social-media/${this.$route.params.id}`, this.socialMedia)
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
