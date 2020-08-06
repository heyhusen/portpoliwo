<template>
  <section class="hero is-bold is-fullheight">
    <div class="hero-body">
      <div class="container is-fullhd">
        <div class="columns">
          <div class="column is-one-third is-offset-one-third">
            <div class="box">
              <div class="section has-text-centered">
                <h1 class="title">Portpoliwo</h1>
              </div>
              <ValidationObserver ref="form" v-slot="{ passes }">
                <form @submit.prevent="passes(onSubmit)">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <FormInput
                        v-model="user.email"
                        label="E-Mail"
                        name="email"
                        icon="email"
                      />
                    </div>
                    <div class="column is-12">
                      <FormInput
                        v-model="user.password"
                        label="Password"
                        name="password"
                        type="password"
                        icon="lock"
                      />
                    </div>
                    <div class="column is-12">
                      <b-field>
                        <b-button
                          type="is-primary"
                          native-type="submit"
                          rounded
                          expanded
                          >Login</b-button
                        >
                      </b-field>
                    </div>
                  </div>
                </form>
              </ValidationObserver>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import $axios from '../../api.js'

export default {
  name: 'Login',
  data() {
    return {
      user: {
        email: '',
        password: '',
      },
    }
  },
  methods: {
    async onSubmit() {
      await $axios.get('/sanctum/csrf-cookie').then(() => {
        $axios
          .post('/login', this.user)
          .then((response) => {
            if (response.data.success) {
              this.$buefy.toast.open({
                message: response.data.message,
                type: 'is-success',
              })
            }
            this.$router.push({ name: 'home' })
          })
          .catch((error) => {
            if (error.response.data.errors) {
              this.$refs.form.setErrors(error.response.data.errors)
            }
            this.$buefy.toast.open({
              message: error.response.data.message,
              type: 'is-danger',
            })
          }) // credentials didn't match
      })
    },
  },
}
</script>
