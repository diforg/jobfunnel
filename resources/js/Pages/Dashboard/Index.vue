<template>
    <AppLayout title="Dashboard">
        <!-- KPI cards -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 lg:col-span-1">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Total</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ totalJobs }}</p>
            </div>
            <div
                v-for="s in highlight"
                :key="s.key"
                class="bg-white rounded-xl border border-gray-200 shadow-sm p-5"
            >
                <p class="text-xs text-gray-500 uppercase tracking-wide">{{ s.label }}</p>
                <p class="text-3xl font-bold mt-1" :class="s.color">{{ statusCounts[s.key] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Status breakdown -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">Vagas por Status</h3>
                </div>
                <ul class="divide-y divide-gray-50">
                    <li
                        v-for="(count, status) in statusCounts"
                        :key="status"
                        class="flex items-center justify-between px-5 py-3"
                    >
                        <StatusBadge :status="status" />
                        <span class="text-sm font-semibold text-gray-700">{{ count }}</span>
                    </li>
                </ul>
            </div>

            <!-- Recent jobs -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">Vagas Recentes</h3>
                </div>
                <ul class="divide-y divide-gray-50 flex-1">
                    <li v-for="job in recentJobs" :key="job.id">
                        <Link
                            :href="`/jobs/${job.id}`"
                            class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition-colors"
                        >
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ job.title }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ job.company }}</p>
                            </div>
                            <StatusBadge :status="job.status" />
                        </Link>
                    </li>
                    <li v-if="!recentJobs.length" class="px-5 py-6 text-sm text-gray-400 text-center">
                        Nenhuma vaga cadastrada ainda.
                    </li>
                </ul>
                <div class="px-5 py-3 border-t border-gray-100">
                    <Link href="/jobs" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                        Ver todas as vagas →
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import StatusBadge from '@/Components/StatusBadge.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    totalJobs: Number,
    statusCounts: Object,
    recentJobs: Array,
})

const highlight = [
    { key: 'applied',             label: 'Candidatado',  color: 'text-blue-600' },
    { key: 'technical_interview', label: 'Entrev. Técnica', color: 'text-purple-600' },
    { key: 'offer',               label: 'Oferta',       color: 'text-orange-600' },
    { key: 'hired',               label: 'Contratado',   color: 'text-green-600' },
]
</script>
