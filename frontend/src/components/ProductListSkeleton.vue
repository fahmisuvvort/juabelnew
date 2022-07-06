<template>
  <q-list class="q-gutter-y-sm">
    <q-item class="q-px-sm q-py-md bg-white" v-for="a in 3" :key="a" >
      <q-item-section avatar top>
         <q-skeleton width="100px" height="100px" />
      </q-item-section>
      <q-item-section top>
         <q-skeleton type="text" />
          <q-skeleton type="text" width="50%" class="text-subtitle1" />
        <div class="flex justify-between items-center" style="margin-top:auto;">
          <q-skeleton type="text" width="20%" class="text-subtitle1" />
          <div class="row q-gutter-x-sm">
          <q-skeleton  type="circle" size="26px"/>
          <q-skeleton type="QChip" height="26px" width="80px"/>
          </div>
        </div>
      </q-item-section>
    </q-item>
  </q-list>
</template>