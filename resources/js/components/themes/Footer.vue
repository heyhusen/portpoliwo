<template>
  <div class="has-text-light">
    <div class="is-relative is-clipped">
      <svg
        class="is-block"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 1440 135"
      >
        <path
          fill="#273c75"
          fill-opacity="1"
          d="M0,32L30,37.3C60,43,120,53,180,74.7C240,96,300,128,360,133.3C420,139,480,117,540,101.3C600,85,660,75,720,80C780,85,840,107,900,112C960,117,1020,107,1080,106.7C1140,107,1200,117,1260,106.7C1320,96,1380,64,1410,48L1440,32L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z"
        ></path>
      </svg>
    </div>
    <footer class="footer has-background-primary">
      <div class="has-background-primary">
        <div class="container is-fullhd">
          <div class="content has-text-centered">
            Copyright &copy; 2020
            {{
              new Date().getFullYear() > 2020
                ? `- ${new Date().getFullYear()}`
                : ''
            }}
            &middot;
            <a class="has-text-light" href="http://husenis.me" target="_blank"
              >Ahmad Husen</a
            >
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
export default {}
</script>
