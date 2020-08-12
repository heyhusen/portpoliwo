<template>
  <nav class="level">
    <div class="level-left">
      <div class="level-item">
        <h1 class="title is-capitalized">{{ title }}</h1>
      </div>
    </div>
    <div class="level-right">
      <div class="level-item">
        <nav class="breadcrumb is-right" aria-label="breadcrumbs">
          <ul>
            <li>
              <router-link to="/">Home</router-link>
            </li>
            <li v-for="(item, i) in breadcrumbs" :key="i" :class="item.classes">
              <router-link :to="item.path" class="is-capitalized">{{
                item.name
              }}</router-link>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Breadcrumb',
  computed: {
    title() {
      return this.$route.name
    },
    breadcrumbs() {
      const breadcrumbs = []
      this.$route.matched.map((item, i, { length }) => {
        const breadcrumb = {}
        breadcrumb.path = item.path
        breadcrumb.name = item.name || item.path

        // is last item?
        if (i === length - 1) {
          // is param route? .../.../:id
          if (item.regex.keys.length > 0) {
            breadcrumbs.push({
              path: item.path.replace(/\/:[^/:]*$/, ''),
              name: item.name.replace(/-[^-]*$/, ''),
            })
            breadcrumb.path = this.$route.path
            breadcrumb.name = this.$route.name
          }
          breadcrumb.classes = 'is-active'
        }

        breadcrumbs.push(breadcrumb)
      })

      return breadcrumbs
    },
  },
}
</script>
