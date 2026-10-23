<script setup>
defineProps({
  variant: { type: String, default: 'blue' }, // blue | purple | green
  icon: { type: String, required: true },
  label: { type: String, required: true },
  value: { type: [String, Number], default: 0 },
  hintIcon: { type: String, default: '' },
  hint: { type: String, default: '' },
  watermark: { type: String, default: '' }
})
</script>

<template>
  <article class="stat-card" :class="`stat-${variant}`">
    <div class="stat-icon"><i :class="icon"></i></div>
    <div class="stat-copy">
      <span>{{ label }}</span>
      <strong>{{ value }}</strong>
      <small><i :class="hintIcon"></i> {{ hint }}</small>
    </div>
    <i v-if="watermark" :class="[watermark, 'stat-watermark']"></i>
  </article>
</template>