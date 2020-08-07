<template>
  <section class="hero is-bold is-fullheight" type="is-light">
    <div class="hero-body">
      <div class="container is-fullhd">
        <div class="columns">
          <div class="column is-one-third is-offset-one-third">
            <div class="box">
              <div class="section has-text-centered">
                <router-link :to="{ name: 'home' }" title="Portpoliwo"
                  ><h1 class="title">Portpoliwo</h1></router-link
                >
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
                          >Log in</b-button
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
import { mapActions } from 'vuex'
import store from '@/js/store'

export default {
  name: 'LogIn',
  data() {
    return {
      user: {
        email: '',
        password: '',
      },
    }
  },
  methods: {
    ...mapActions({
      logIn: 'auth/logIn',
    }),

    async onSubmit() {
      try {
        await this.logIn(this.user)
        this.$router.push({ name: 'home' })
      } catch (error) {
        if (error.response.data.errors) {
          this.$refs.form.setErrors(error.response.data.errors)
        }
      }
    },
  },
  beforeRouteEnter(to, from, next) {
    if (store.getters['auth/authenticated']) {
      return next({ name: 'home' })
    }
    next()
  },
}
</script>
