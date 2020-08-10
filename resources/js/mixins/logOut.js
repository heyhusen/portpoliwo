import { mapActions } from 'vuex'

export const logOutMixin = {
  methods: {
    ...mapActions({
      logOut: 'auth/logOut',
    }),

    async signOut() {
      await this.logOut()
      this.$router.push({ name: 'login' })
    },
  },
}
