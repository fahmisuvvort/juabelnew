<template>
  <q-list class="relative bg-white border-b">
    <q-item class="q-pa-md" clickable :to="{name: 'FrontPostShow', params:{slug: slug}}">
      <q-item-section avatar top>
          <q-img v-if="image_url" :src="image_url" ratio="1" class="image-list cursor-pointer rounded-borders"/>
      </q-item-section>
      <q-item-section top>
        <div class="ellipsis-2-lines text-weight-medium q-mb-xs" style="font-size:16px;">{{ title }}</div>
        <div class="text-grey-7 text-sm ellipsis-2-lines" v-html="getTeaser(body)"></div>
        <div class="q-pt-sm flex justify-between" style="margin-top:auto;">
          <div class="text-orange-7 text-weight-medium">{{ created_locale }}</div>
        </div>
      </q-item-section>
     
    </q-item>
  </q-list>
</template>
<script>
export default {
  name: 'PostList',
  props:{
    title: String,
    slug: String,
    image_url: String,
    tags: String,
    body: String,
    created_locale: String
  },
  methods: {
    getTeaser(html) {
      if(html) {
        let strippedString = html.replace(/(<([^>]+)>)/gi, "");
        return strippedString.substr(0, 120)
      } else {
        return ''
      }
    },
  }
}
</script>