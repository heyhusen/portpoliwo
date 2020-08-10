<template>
  <div class="columns is-multiline">
    <div class="column is-half-tablet">
      <FormInput v-model="name" label="Name" name="name" />
    </div>
    <div class="column is-half-tablet">
      <FormInput v-model="email" label="E-Mail" name="email" />
    </div>
    <div class="column is-half-tablet">
      <FormInput
        v-model="password"
        label="Password"
        name="password"
        type="password"
      />
    </div>
    <div class="column is-half-tablet">
      <FormInput
        v-model="passwordRepeat"
        label="Repeat Password"
        name="password_repeat"
        type="password"
      />
    </div>
    <div class="column is-half-tablet">
      <FormImage
        v-model="photo"
        label="Photo"
        name="photo"
        message="For best results, use an image with an aspect ratio of 1:1 with a minimum size of 256x256 px."
        @change="$emit('change', $event)"
      />
    </div>
    <div class="column is-12">
      <hr />
      <SaveButton />
    </div>
  </div>
</template>

<script>
export default {
  name: 'AccountForm',
  components: {
    FormInput: () => import('@/js/components/form/Input'),
    FormImage: () => import('@/js/components/form/Image'),
    SaveButton: () => import('@/js/components/SaveButton'),
  },
  props: {
    name: {
      type: String,
      default: 'name',
    },
    email: {
      type: String,
      default: 'email',
    },
    password: {
      type: String,
      default: 'password',
    },
    passwordRepeat: {
      type: String,
      default: 'passwordRepeat',
    },
    photo: {
      type: String,
      default: 'photo',
    },
  },
}
</script>
