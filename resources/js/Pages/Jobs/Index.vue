<template>
    <AppLayout title="Vagas">
        <template #actions>
            <Link href="/jobs/create">
                <PrimaryButton type="button">+ Nova Vaga</PrimaryButton>
            </Link>
        </template>

        <!-- Status filters -->
        <div class="flex flex-wrap gap-2 mb-6">
            <Link href="/jobs" :class="tabClass('')">Todas</Link>
            <Link
                v-for="s in allStatuses"
                :key="s.value"
                :href="`/jobs?status=${s.value}`"
                :class="tabClass(s.value)"
            >{{ s.label }}</Link>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vaga / Empresa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Candidatura</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pretensão</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <tr v-for="job in jobs" :key="job.id" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <Link :href="`/jobs/${job.id}`" class="block group">
                                <p class="text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition-colors">{{ job.title }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ job.company }}</p>
                            </Link>
                        </td>
                        <td class="px-6 py-4">
                            <StatusBadge :status="job.status" />
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ job.applied_at ? formatDate(job.applied_at) : '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ job.salary_expectation ? formatCurrency(job.salary_expectation) : '—' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <Link :href="`/jobs/${job.id}/edit`" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                    Editar
                                </Link>
                                <button @click="deleteJob(job)" class="text-xs text-red-500 hover:text-red-700 font-medium">
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!jobs.length">
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                            Nenhuma vaga encontrada.
                            <Link href="/jobs/create" class="text-indigo-600 hover:underline ml-1">Criar nova vaga</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import StatusBadge from '@/Components/StatusBadge.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    jobs: Array,
    currentStatus: String,
})

const allStatuses = [
    { value: 'identified',          label: 'Identificada' },
    { value: 'applied',             label: 'Candidatado' },
    { value: 'recruiter_interview', label: 'Entrevista RH' },
    { value: 'technical_interview', label: 'Entrevista Técnica' },
    { value: 'technical_test',      label: 'Teste Técnico' },
    { value: 'offer',               label: 'Oferta' },
    { value: 'hired',               label: 'Contratado' },
    { value: 'rejected',            label: 'Reprovado' },
    { value: 'ghosted',             label: 'Ghosted' },
]

function tabClass(value) {
    const active = props.currentStatus === value
    return [
        'px-3 py-1.5 rounded-full text-xs font-medium transition-colors',
        active
            ? 'bg-indigo-600 text-white'
            : 'bg-white border border-gray-300 text-gray-600 hover:bg-gray-50',
    ]
}

function formatDate(d) {
    const [y, m, day] = d.split('-')
    return `${day}/${m}/${y}`
}

function formatCurrency(v) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(v)
}

const deleteForm = useForm({})
function deleteJob(job) {
    if (!confirm(`Excluir "${job.title}" (${job.company})?`)) return
    deleteForm.delete(`/jobs/${job.id}`)
}
</script>
