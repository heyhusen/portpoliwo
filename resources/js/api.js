import axios from 'axios'

const $axios = axios.create({
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    Accept: 'application/json',
  },
  withCredentials: true,
})

export default $axios
