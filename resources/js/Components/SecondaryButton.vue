<template>
    <button
        :type="type"
        class="inline-flex items-center px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
    >
        <slot />
    </button>
</template>

<script setup>
defineProps({ type: { type: String, default: 'button' } })
</script>
