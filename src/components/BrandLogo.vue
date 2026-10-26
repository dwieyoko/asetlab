<template>
  <router-link :to="to" class="inline-flex items-center group select-none transition-transform duration-200 hover:scale-[1.02]">
    <!-- Wordmark with Superscript ID & Optional Subtitle -->
    <div class="flex flex-col leading-none">
      <div class="flex items-baseline gap-1">
        <span 
          class="font-quicksand font-extrabold tracking-tight text-[#00f2fe] drop-shadow-[0_0_12px_rgba(0,242,254,0.3)]"
          :class="textSizeClass"
        >
          aset<span class="text-[#4edea3] drop-shadow-[0_0_12px_rgba(78,222,163,0.3)]">lab</span>
        </span>
        
        <!-- Superscript ID Badge (seperti kuadrat) -->
        <span 
          v-if="showBadge"
          class="inline-flex items-center justify-center font-bold font-sans bg-[#00f2fe]/10 text-[#00f2fe] border border-[#00f2fe]/30 tracking-wider uppercase select-none rounded shadow-[0_0_8px_rgba(0,242,254,0.15)] relative"
          :class="[badgeSizeClass, superscriptPositionClass]"
        >
          ID
        </span>
      </div>

      <span 
        v-if="subtitle" 
        class="font-sans text-[10px] text-[#849495] tracking-widest uppercase mt-1 font-semibold"
      >
        {{ subtitle }}
      </span>
    </div>
  </router-link>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  to: {
    type: String,
    default: '/'
  },
  size: {
    type: String,
    default: 'md', // 'sm', 'md', 'lg'
    validator: (v) => ['sm', 'md', 'lg'].includes(v)
  },
  subtitle: {
    type: String,
    default: ''
  },
  showBadge: {
    type: Boolean,
    default: true
  }
});

const textSizeClass = computed(() => {
  switch (props.size) {
    case 'sm': return 'text-xl';
    case 'lg': return 'text-3xl sm:text-4xl';
    default: return 'text-2xl sm:text-[26px]';
  }
});

const badgeSizeClass = computed(() => {
  switch (props.size) {
    case 'sm': return 'text-[8px] px-1 py-0.2';
    case 'lg': return 'text-[10px] sm:text-[11px] px-1.5 py-0.5';
    default: return 'text-[9px] px-1.5 py-0.5';
  }
});

const superscriptPositionClass = computed(() => {
  switch (props.size) {
    case 'sm': return '-top-2.5';
    case 'lg': return '-top-4 sm:-top-5';
    default: return '-top-3 sm:-top-3.5';
  }
});
</script>
