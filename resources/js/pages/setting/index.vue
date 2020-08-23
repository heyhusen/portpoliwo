<template>
  <div class="box">
    <ValidationObserver ref="form" v-slot="{ passes }">
      <form @submit.prevent="passes(onSubmit)">
        <div class="columns is-multiline">
          <div class="column is-12">
            <FormInput
              v-model="settings.site_name"
              label="Site name"
              name="site_name"
            />
          </div>
          <div class="column is-12">
            <FormInput
              v-model="settings.site_description"
              label="Site description"
              name="site_description"
            />
          </div>
          <div class="column is-12">
            <FormInput
              v-model="settings.site_name"
              label="Company name"
              name="company_name"
            />
          </div>
          <div class="column is-12">
            <FormInput
              v-model="settings.company_address"
              label="Company address"
              name="company_address"
              type="textarea"
            />
          </div>
          <div class="column is-12">
            <FormInput
              v-model="settings.company_phone_number"
              label="Company phone number"
              name="company_phone_number"
            />
          </div>
          <div class="column is-12">
            <FormInput
              v-model="settings.company_email"
              label="Company email"
              name="company_email"
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
import { api } from '@/js/api'

export default {
  name: 'SettingIndex',
  metaInfo: {
    title: 'Setting',
  },
  components: {
    ValidationObserver,
    FormInput: () => import('@/js/components/form/Input'),
    SaveButton: () => import('@/js/components/SaveButton'),
  },
  data() {
    return {
      settings: {
        site_name: '',
        site_description: '',
        company_name: '',
        company_address: '',
        company_phone_number: '',
        company_email: '',
      },
    }
  },
  mounted() {
    this.fetchData()
  },
  methods: {
    async fetchData() {
      await api
        .get(`/setting`)
        .then(({ data: { data } }) => {
          data.forEach(({ name, value }) => {
            this.settings[name] = value
          })
        })
        .catch(() => {
          this.settings = {}
        })
    },
    async onSubmit() {
      await api
        .post(`/setting`, this.settings)
        .then(({ data }) => {
          if (data.success) {
            this.$buefy.toast.open({
              message: data.message,
              type: 'is-success',
            })
          }
          this.fetchData()
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
