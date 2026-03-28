<template>
    <input
        v-bind="$attrs"
        :value="modelValue"
        class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>

<script setup>
defineOptions({ inheritAttrs: false })
defineProps({ modelValue: { default: '' } })
defineEmits(['update:modelValue'])
</script>
