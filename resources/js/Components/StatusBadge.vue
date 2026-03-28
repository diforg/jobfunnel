<template>
    <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap', cfg.color]">
        {{ cfg.label }}
    </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    status: { type: String, required: true },
})

const map = {
    identified:          { color: 'bg-gray-100 text-gray-700',    label: 'Identificada' },
    applied:             { color: 'bg-blue-100 text-blue-800',    label: 'Candidatado' },
    recruiter_interview: { color: 'bg-indigo-100 text-indigo-800', label: 'Entrevista RH' },
    technical_interview: { color: 'bg-purple-100 text-purple-800', label: 'Entrevista Técnica' },
    technical_test:      { color: 'bg-amber-100 text-amber-800',  label: 'Teste Técnico' },
    offer:               { color: 'bg-orange-100 text-orange-800', label: 'Oferta Recebida' },
    hired:               { color: 'bg-green-100 text-green-800',  label: 'Contratado ✓' },
    rejected:            { color: 'bg-red-100 text-red-800',      label: 'Reprovado' },
    ghosted:             { color: 'bg-gray-200 text-gray-600',    label: 'Ghosted' },
}

const cfg = computed(() => map[props.status] ?? { color: 'bg-gray-100 text-gray-600', label: props.status })
</script>
