<template>
    <label class="block text-sm font-medium text-gray-700 mb-1">
        <slot>{{ value }}</slot>
    </label>
</template>

<script setup>
defineProps({
    value: { type: String, default: '' },
})
</script>
