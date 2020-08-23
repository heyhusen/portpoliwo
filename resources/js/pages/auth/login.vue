<template>
  <div>
    <div class="has-background-primary pt-1"></div>
    <section class="hero is-bold" type="is-light">
      <div class="hero-body">
        <div class="container is-fullhd">
          <div class="columns">
            <div class="column is-three-fifths is-offset-one-fifth">
              <div class="box">
                <div class="columns is-vcentered">
                  <div class="column is-half has-text-centered">
                    <router-link :to="{ name: 'home' }" title="Portpoliwo"
                      ><h1 class="title is-size-1">{{ title }}</h1></router-link
                    >
                  </div>
                  <div class="column is-half">
                    <div class="section has-text-centered">
                      <h1 class="title">Sign in</h1>
                    </div>
                    <ValidationObserver ref="form" v-slot="{ passes }">
                      <form @submit.prevent="passes(onSubmit)">
                        <div class="columns is-multiline">
                          <div class="column is-12">
                            <FormInput
                              v-model="user.email"
                              size="is-medium"
                              label="E-Mail"
                              name="email"
                              icon="email"
                            />
                          </div>
                          <div class="column is-12">
                            <FormInput
                              v-model="user.password"
                              size="is-medium"
                              label="Password"
                              name="password"
                              type="password"
                              icon="lock"
                            />
                          </div>
                          <div class="column is-12">
                            <b-field>
                              <b-button
                                size="is-medium"
                                type="is-primary"
                                native-type="submit"
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
        </div>
      </div>
    </section>

    <Footer />
  </div>
</template>

<script>
import { mapActions } from 'vuex'
import store from '@/js/store'

export default {
  name: 'LogIn',
  metaInfo: {
    title: 'Log in',
  },
  components: {
    Footer: () => import('@/js/components/themes/Footer'),
  },
  data() {
    return {
      title: process.env.MIX_APP_NAME,
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
