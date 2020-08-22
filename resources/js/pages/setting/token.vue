<template>
  <div class="box">
    <ValidationObserver ref="form" v-slot="{ passes }">
      <form @submit.prevent="passes(onSubmit)">
        <div class="columns is-multiline">
          <div class="column is-12">
            <FormInput
              v-model="token.name"
              label="Create API token"
              placeholder="Token name"
              name="name"
            >
              <SaveButton type="is-primary" :rounded="false">Create</SaveButton>
            </FormInput>
          </div>
          <div v-if="createdToken" class="column is-12">
            Make sure to copy your new API token now. You won’t be able to see
            it again!
            <br />
            <b-notification
              type="is-success is-light"
              aria-close-label="Close notification"
              :closable="false"
            >
              {{ createdToken }}
            </b-notification>
          </div>
        </div>
        <hr />
      </form>
    </ValidationObserver>
    <div v-if="tokens.length == 0" class="has-text-centered has-text-danger">
      No API token yet.
    </div>
    <div
      v-for="(item, key) of tokens"
      :key="key"
      class="has-background-light px-2 py-2 mb-1"
    >
      {{ item.name }}
      <span class="tag is-danger is-pulled-right" @click="deleteToken(item.id)"
        >Delete</span
      >
    </div>
  </div>
</template>

<script>
import { ValidationObserver } from 'vee-validate'
import { api } from '@/js/api'

export default {
  name: 'SettingToken',
  metaInfo: {
    title: 'Setting: API Token',
  },
  components: {
    ValidationObserver,
    FormInput: () => import('@/js/components/form/Input'),
    SaveButton: () => import('@/js/components/SaveButton'),
  },
  data() {
    return {
      token: {
        name: '',
      },
      createdToken: '',
      tokens: [],
    }
  },
  mounted() {
    this.fetchData()
  },
  methods: {
    async fetchData() {
      await api
        .get(`/setting/token`)
        .then(({ data: { data } }) => {
          this.tokens = data
        })
        .catch(() => {
          this.tokens = []
        })
    },
    async onSubmit() {
      this.createdToken = ''
      await api
        .post(`/setting/token`, this.token)
        .then(({ data }) => {
          if (data.success) {
            this.$buefy.toast.open({
              message: data.message,
              type: 'is-success',
            })
          }
          this.fetchData()
          this.createdToken = data.data.token
          this.token.name = ''
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
    async deleteToken(id) {
      this.$buefy.dialog.confirm({
        title: 'Deleting data',
        message:
          'Are you sure you want to <b>delete</b> selected token? This action cannot be undone.',
        confirmText: 'Delete Token',
        type: 'is-danger',
        hasIcon: true,
        onConfirm: async () => {
          await api
            .delete(`/setting/token/${id}`)
            .then(({ data }) => {
              this.$buefy.toast.open({
                message: data.message,
                type: 'is-danger',
              })
            })
            .catch(({ response: { data } }) => {
              this.$buefy.toast.open({
                message: data.message,
                type: 'is-danger',
              })
            })
          await this.fetchData()
        },
      })
    },
  },
}
</script>

<style scoped>
.tag {
  cursor: pointer;
}
</style>
