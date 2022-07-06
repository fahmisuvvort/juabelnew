<template>
  <q-item clickable v-ripple :to="{name: path}" exact>
    <q-item-section avatar>
      <q-icon :name="icon" />
    </q-item-section>

    <q-item-section>
      {{title }}
    </q-item-section>
  </q-item>
</template>

<script>
export default {
  name: 'navigation',
   props: {
    title: {
      type: String,
      required: true
    },
    icon: {
      type: String,
      default: ''
    },
    path: {
      type: String,
      default: 'Home'
    }
  }
}
</script>

<style>

</style>