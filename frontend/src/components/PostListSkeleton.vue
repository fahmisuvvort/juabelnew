<template>
  <div class="relative bg-white border-b">
    <div class="row q-pa-md no-wrap">
      <q-skeleton width="100px" height="100px"/>
      <div class="q-ml-md column flex-1">
        <div class="column">
          <q-skeleton type="text" width="20%"></q-skeleton>
          <q-skeleton  height="15px" width="94%"></q-skeleton>
          <q-skeleton class="q-mt-xs" height="15px" width="60%"></q-skeleton>
        </div>
        <div style="margin-top:auto;">
          <q-skeleton width="80px"></q-skeleton>
        </div>
      </div>
     
    </div>
  </div>
</template>
<script>
export default {
  name: 'PostListSkeleton',
}
</script>