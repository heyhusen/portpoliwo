<template>
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
    async onSubmit() {
      const data = serialize(this.user, {
        nullsAsUndefineds: true,
      })
      await api
        .post('/account', data)
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
