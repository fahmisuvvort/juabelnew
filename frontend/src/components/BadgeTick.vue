<template>
  <div class="product-variation__tick" >
      <svg enable-background="new 0 0 12 12" viewBox="0 0 12 12" x="0" y="0" class="svg-icon icon-tick-bold"><g><path d="m5.2 10.9c-.2 0-.5-.1-.7-.2l-4.2-3.7c-.4-.4-.5-1-.1-1.4s1-.5 1.4-.1l3.4 3 5.1-7c .3-.4 1-.5 1.4-.2s.5 1 .2 1.4l-5.7 7.9c-.2.2-.4.4-.7.4 0-.1 0-.1-.1-.1z"></path></g></svg>
    </div>
</template>

<script>
export default {
  props: {
    color: {
      type: String,
      default: 'accent'
    }
  },
  computed: {
    classColor() {
      return `${'text-' + this.color}`
    }
  }
}
</script>

<style scoped lang="scss">

.svg-icon {
  display: inline-block;
  width: 1em;
  height: 1em;
  fill: currentColor;
  position: relative;
}
.product-variation {
  
  &__tick {

    width: .9375rem;
    height: .9375rem;
    position: absolute;
    overflow: hidden;
    right: 0;
    bottom: 0;

    > .icon-tick-bold {
      position: absolute;
      right: 0.07rem;
      bottom: 0.06rem;
      color: #fff;
      font-size: 8px;
    }

    &:before {
      border: .9375rem solid transparent;
      border-bottom: .9375rem solid var(--q-color-accent,#ee4d2d);
      content: "";
      position: absolute;
      right: -.9375rem;
      bottom: 0;
    }
  }
}
</style>