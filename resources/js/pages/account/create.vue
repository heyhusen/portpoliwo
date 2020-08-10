<template>
  <div>
    <div class="box">
      <ValidationObserver ref="form" v-slot="{ passes }">
        <form @submit.prevent="passes(onSubmit)">
          <div class="columns is-multiline">
            <div class="column is-half-tablet">
              <FormInput v-model="user.name" label="Name" name="name" />
            </div>
            <div class="column is-half-tablet">
              <FormInput v-model="user.email" label="E-Mail" name="email" />
            </div>
            <div class="column is-half-tablet">
              <FormInput
                v-model="user.password"
                label="Password"
                name="password"
                type="password"
              />
            </div>
            <div class="column is-half-tablet">
              <FormInput
                v-model="user.passwordRepeat"
                label="Repeat Password"
                name="password_repeat"
                type="password"
              />
            </div>
            <div class="column is-half-tablet">
              <FormImage
                v-model="user.photo"
                label="Photo"
                name="photo"
                message="For best results, use an image with an aspect ratio of 1:1 with a minimum size of 256x256 px."
                @change="selectPhoto"
              />
            </div>
          </div>
          <hr />
          <SaveButton />
        </form>
      </ValidationObserver>
    </div>
  </div>
</template>

<script>
import { ValidationObserver } from 'vee-validate'
import { api } from '@/js/api'

export default {
  name: 'CreateAccount',
  components: {
    ValidationObserver,
    FormInput: () => import('@/js/components/form/Input'),
    FormImage: () => import('@/js/components/form/Image'),
    SaveButton: () => import('@/js/components/SaveButton'),
  },
  data() {
    return {
      user: {
        name: '',
        email: '',
        password: '',
        passwordRepeat: '',
        photo: null,
      },
    }
  },
  methods: {
    selectPhoto(event) {
      this.user.photo = event.target.files[0]
    },
    async onSubmit() {
      const data = new FormData()
      data.append('name', this.user.name)
      data.append('email', this.user.email)
      data.append('password', this.user.password)
      data.append('password_repeat', this.user.passwordRepeat)
      if (this.user.photo) {
        data.append('photo', this.user.photo)
      }
      await api
        .post('/account', data)
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
