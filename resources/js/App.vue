<template>
  <router-view />
</template>

<script>
export default {
  name: 'App',
  metaInfo: {
    title: 'App',
    titleTemplate: `%s | ${process.env.MIX_APP_NAME}`,
  },
}
</script>
