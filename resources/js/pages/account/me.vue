<template>
  <div class="box">
    <div class="columns is-multiline">
      <div class="column is-one-third-tablet">
        <div class="box">
          <div class="level">
            <div class="level-item">
              <div class="has-text-centered">
                <strong>{{ user.name }}</strong>
                <figure class="image is-128x128">
                  <img
                    class="is-rounded"
                    :src="user.avatar"
                    :title="user.name"
                  />
                </figure>
              </div>
            </div>
          </div>
          <b-field label="Registered at">
            {{ new Date(user.created_at).toLocaleString() }}
          </b-field>
          <b-field label="Last updated at">
            {{ new Date(user.updated_at).toLocaleString() }}
          </b-field>
        </div>
      </div>
      <div class="column">
        <ValidationObserver ref="form" v-slot="{ passes }">
          <form @submit.prevent="passes(onSubmit)">
            <div class="columns is-multiline">
              <div class="column is-12">
                <FormInput v-model="user.name" label="Name" name="name" />
              </div>
              <div class="column is-12">
                <FormInput v-model="user.email" label="E-Mail" name="email" />
              </div>
              <div class="column is-12">
                <FormInput
                  v-model="my.password"
                  label="Password"
                  name="password"
                  type="password"
                />
              </div>
              <div class="column is-12">
                <FormInput
                  v-model="my.passwordRepeat"
                  label="Repeat Password"
                  name="password_repeat"
                  type="password"
                />
              </div>
              <div class="column is-12">
                <FormImage
                  v-model="my.photo"
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
    </div>
  </div>
</template>

<script>
import { userMixin } from '@/js/mixins/user'
import { ValidationObserver } from 'vee-validate'
import { serialize } from 'object-to-formdata'
import { api } from '@/js/api'
import { mapActions } from 'vuex'

export default {
  name: 'MyAccount',
  components: {
    ValidationObserver,
    FormInput: () => import('@/js/components/form/Input'),
    FormImage: () => import('@/js/components/form/Image'),
    SaveButton: () => import('@/js/components/SaveButton'),
  },
  mixins: [userMixin],
  data() {
    return {
      my: {
        password: null,
        passwordRepeat: null,
        photo: null,
      },
    }
  },
  methods: {
    ...mapActions({
      update: 'auth/me',
    }),
    async onSubmit() {
      const { name: userName, email: userEmail } = this.user
      const data = serialize(
        { name: userName, email: userEmail, ...this.my, _method: 'PUT' },
        {
          nullsAsUndefineds: true,
        }
      )
      await api
        .post(`/account/${this.user.id}`, data)
        .then(({ data }) => {
          if (data.success) {
            this.$buefy.toast.open({
              message: data.message,
              type: 'is-success',
            })
          }
          this.update()
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
