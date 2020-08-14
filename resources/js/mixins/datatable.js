import { api } from '@/js/api'

export const datatableMixin = {
  components: {
    AddButton: () => import('@/js/components/AddButton'),
    ActionButton: () => import('@/js/components/ActionButton'),
  },
  data() {
    return {
      url: '/',
      data: [],
      total: 0,
      loading: false,
      checkboxPosition: 'left',
      checkedRows: [],
      sortField: 'created_at',
      sortOrder: 'desc',
      defaultSortOrder: 'desc',
      page: 1,
      perPage: 10,
    }
  },
  mounted() {
    this.fetchData()
  },
  methods: {
    async fetchData() {
      this.loading = true
      await api
        .post(`/${this.url}/list`, {
          sort_field: this.sortField,
          sort_order: this.sortOrder,
          per_page: this.perPage,
          page: this.page,
        })
        .then(({ data: { data } }) => {
          this.data = data.data
          let currentTotal = data.total
          if (data.total / this.perPage > 1000) {
            currentTotal = this.perPage * 1000
          }
          this.total = currentTotal
          this.loading = false
        })
        .catch((error) => {
          this.data = []
          this.total = 0
          this.loading = false
          throw error
        })
    },
    onPageChange(page) {
      this.page = page
      this.checkedRows = []
      this.fetchData()
    },
    onSort(field, order) {
      this.sortField = field
      this.sortOrder = order
      this.checkedRows = []
      this.fetchData()
    },
    deleteData() {
      this.$buefy.dialog.confirm({
        title: 'Deleting data',
        message:
          'Are you sure you want to <b>delete</b> selected data? This action cannot be undone.',
        confirmText: 'Delete Data',
        type: 'is-danger',
        hasIcon: true,
        onConfirm: async () => {
          const selectData = this.checkedRows.map(({ id }) => id)
          this.checkedRows = []
          await api({
            method: 'delete',
            url: `/${this.url}`,
            data: {
              selectedData: selectData,
            },
          })
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
