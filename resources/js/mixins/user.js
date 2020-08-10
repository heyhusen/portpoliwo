import { mapGetters } from 'vuex'

export const userMixin = {
  computed: {
    ...mapGetters({
      authenticated: 'auth/authenticated',
      user: 'auth/user',
    }),
  },
}
