<template>
  <footer class="footer has-background-light">
    <div class="container is-fullhd">
      <div class="content has-text-centered">
        Copyright &copy; 2020
        {{
          new Date().getFullYear() > 2020 ? `- ${new Date().getFullYear()}` : ''
        }}
        &middot; <a href="http://husenis.me" target="_blank">Ahmad Husen</a>
      </div>
    </div>
  </footer>
</template>

<script>
export default {}
</script>
