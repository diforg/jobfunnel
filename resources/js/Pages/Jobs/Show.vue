<template>
    <AppLayout :title="job.title">
        <template #actions>
            <Link :href="`/jobs/${job.id}/edit`">
                <SecondaryButton type="button">Editar</SecondaryButton>
            </Link>
            <DangerButton @click="deleteJob" :disabled="deleteForm.processing">Excluir</DangerButton>
        </template>

        <!-- Job header card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ job.title }}</h2>
                    <p class="text-gray-500 mt-0.5">{{ job.company }}</p>
                </div>
                <StatusBadge :status="job.status" class="mt-1 shrink-0" />
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-5">
                <div v-if="job.salary_expectation">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Pretensão</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ formatCurrency(job.salary_expectation) }}</p>
                </div>
                <div v-if="job.applied_at">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Candidatado em</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ formatDate(job.applied_at) }}</p>
                </div>
                <div v-if="job.source_name">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Fonte</p>
                    <p class="text-sm font-medium text-gray-800 mt-0.5">{{ job.source_name }}</p>
                </div>
                <div v-if="job.apply_url">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Link</p>
                    <a :href="job.apply_url" target="_blank" rel="noopener"
                        class="text-sm text-indigo-600 hover:underline mt-0.5 block truncate">
                        Ver vaga ↗
                    </a>
                </div>
            </div>

            <div v-if="job.description" class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Descrição</p>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ job.description }}</p>
            </div>
            <div v-if="job.notes" class="mt-3">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Anotações</p>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ job.notes }}</p>
            </div>
        </div>

        <!-- Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="flex gap-1">
                <button
                    v-for="t in tabs"
                    :key="t.key"
                    @click="activeTab = t.key"
                    :class="[
                        'px-4 py-2.5 text-sm font-medium border-b-2 transition-colors -mb-px',
                        activeTab === t.key
                            ? 'border-indigo-600 text-indigo-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700',
                    ]"
                >
                    {{ t.label }}
                    <span class="ml-1.5 text-xs bg-gray-100 text-gray-600 rounded-full px-1.5 py-0.5">{{ t.count }}</span>
                </button>
            </nav>
        </div>

        <!-- Tab: Contacts -->
        <div v-show="activeTab === 'contacts'">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div
                    v-for="c in job.contacts"
                    :key="c.id"
                    class="bg-white rounded-xl border border-gray-200 shadow-sm p-4"
                >
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ c.name }}</p>
                            <p v-if="c.role" class="text-xs text-gray-500 mt-0.5">{{ c.role }}</p>
                        </div>
                        <button @click="deleteContact(c)" class="text-red-400 hover:text-red-600 text-xs">✕</button>
                    </div>
                    <div class="mt-3 space-y-1 text-sm text-gray-600">
                        <p v-if="c.email">✉ <a :href="`mailto:${c.email}`" class="hover:underline">{{ c.email }}</a></p>
                        <p v-if="c.phone">📞 {{ c.phone }}</p>
                        <p v-if="c.linkedin_url">
                            <a :href="c.linkedin_url" target="_blank" rel="noopener" class="text-indigo-600 hover:underline">LinkedIn ↗</a>
                        </p>
                        <p v-if="c.notes" class="text-gray-500 text-xs mt-2 italic">{{ c.notes }}</p>
                    </div>
                </div>
            </div>

            <!-- Add contact form -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-4">Adicionar Contato</h4>
                <form @submit.prevent="submitContact" class="grid grid-cols-2 gap-3">
                    <div class="col-span-2 sm:col-span-1">
                        <InputLabel>Nome *</InputLabel>
                        <TextInput v-model="contactForm.name" type="text" required />
                        <InputError :message="contactForm.errors.name" />
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <InputLabel>Cargo / Função</InputLabel>
                        <TextInput v-model="contactForm.role" type="text" />
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <InputLabel>E-mail</InputLabel>
                        <TextInput v-model="contactForm.email" type="email" />
                        <InputError :message="contactForm.errors.email" />
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <InputLabel>Telefone</InputLabel>
                        <TextInput v-model="contactForm.phone" type="text" />
                    </div>
                    <div class="col-span-2">
                        <InputLabel>LinkedIn</InputLabel>
                        <TextInput v-model="contactForm.linkedin_url" type="url" placeholder="https://linkedin.com/in/..." />
                        <InputError :message="contactForm.errors.linkedin_url" />
                    </div>
                    <div class="col-span-2">
                        <InputLabel>Anotações</InputLabel>
                        <TextInput v-model="contactForm.notes" type="text" />
                    </div>
                    <div class="col-span-2 flex justify-end">
                        <PrimaryButton :disabled="contactForm.processing">Adicionar</PrimaryButton>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tab: Skills (job requirements) -->
        <div v-show="activeTab === 'skills'">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Habilidade</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nível</th>
                            <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tenho</th>
                            <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="s in job.skills" :key="s.id" class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ s.skill_name }}</td>
                            <td class="px-5 py-3">
                                <span :class="s.level === 'required' ? 'text-red-600 font-medium' : 'text-gray-500'" class="text-xs">
                                    {{ s.level === 'required' ? 'Obrigatório' : 'Diferencial' }}
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <button @click="toggleSkill(s)"
                                    :class="s.matched ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                                    class="px-2 py-0.5 rounded-full text-xs font-medium transition-colors hover:opacity-80">
                                    {{ s.matched ? '✓ Tenho' : '✗ Não tenho' }}
                                </button>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <button @click="deleteSkill(s)" class="text-red-400 hover:text-red-600 text-xs">✕</button>
                            </td>
                        </tr>
                        <tr v-if="!job.skills.length">
                            <td colspan="4" class="px-5 py-6 text-center text-gray-400 text-sm">Nenhuma habilidade mapeada.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Add skill form -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-4">Adicionar Habilidade Requisitada</h4>
                <form @submit.prevent="submitSkill" class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-40">
                        <InputLabel>Habilidade *</InputLabel>
                        <TextInput v-model="skillForm.skill_name" type="text" required placeholder="Ex: Laravel, Docker..." />
                        <InputError :message="skillForm.errors.skill_name" />
                    </div>
                    <div>
                        <InputLabel>Nível *</InputLabel>
                        <select v-model="skillForm.level" class="block rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="required">Obrigatório</option>
                            <option value="nice_to_have">Diferencial</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2 pb-0.5">
                        <input id="matched" type="checkbox" v-model="skillForm.matched" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                        <label for="matched" class="text-sm text-gray-700">Já tenho</label>
                    </div>
                    <PrimaryButton :disabled="skillForm.processing">Adicionar</PrimaryButton>
                </form>
            </div>
        </div>

        <!-- Tab: Timeline -->
        <div v-show="activeTab === 'timeline'">
            <div class="relative mb-6">
                <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                <ul class="space-y-4">
                    <li v-for="t in job.timelines" :key="t.id" class="flex gap-4 relative">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 border-2 border-indigo-300 flex items-center justify-center shrink-0 z-10">
                            <span class="text-indigo-600 text-xs font-bold">{{ t.happened_at.slice(8, 10) }}/{{ t.happened_at.slice(5, 7) }}</span>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex-1">
                            <div class="flex items-start justify-between">
                                <p class="text-sm font-semibold text-gray-900">{{ t.stage }}</p>
                                <button @click="deleteTimeline(t)" class="text-red-400 hover:text-red-600 text-xs ml-2">✕</button>
                            </div>
                            <p v-if="t.notes" class="text-xs text-gray-500 mt-1">{{ t.notes }}</p>
                        </div>
                    </li>
                    <li v-if="!job.timelines.length" class="pl-14 text-sm text-gray-400">Nenhum evento na timeline.</li>
                </ul>
            </div>

            <!-- Add timeline event -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h4 class="text-sm font-semibold text-gray-700 mb-4">Adicionar Evento</h4>
                <form @submit.prevent="submitTimeline" class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-40">
                        <InputLabel>Etapa *</InputLabel>
                        <TextInput v-model="timelineForm.stage" type="text" required placeholder="Ex: Entrevista técnica" />
                        <InputError :message="timelineForm.errors.stage" />
                    </div>
                    <div>
                        <InputLabel>Data *</InputLabel>
                        <TextInput v-model="timelineForm.happened_at" type="date" required />
                        <InputError :message="timelineForm.errors.happened_at" />
                    </div>
                    <div class="flex-1 min-w-48">
                        <InputLabel>Observação</InputLabel>
                        <TextInput v-model="timelineForm.notes" type="text" />
                    </div>
                    <PrimaryButton :disabled="timelineForm.processing">Adicionar</PrimaryButton>
                </form>
            </div>
        </div>

        <!-- Tab: Resumes -->
        <div v-show="activeTab === 'resumes'">
            <div v-if="job.resumes.length" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div v-for="r in job.resumes" :key="r.id" class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                    <p class="font-medium text-gray-900">{{ r.version_name }}</p>
                    <p v-if="r.sent_at" class="text-xs text-gray-500 mt-0.5">Enviado em {{ formatDate(r.sent_at) }}</p>
                    <p v-if="r.file_path" class="text-xs text-indigo-600 mt-1 truncate">{{ r.file_path }}</p>
                    <p v-if="r.notes" class="text-xs text-gray-500 mt-2 italic">{{ r.notes }}</p>
                </div>
            </div>
            <div v-else class="text-center py-12 text-gray-400 text-sm">
                Nenhum currículo vinculado a esta vaga.
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'
import StatusBadge from '@/Components/StatusBadge.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({ job: Object })

const activeTab = ref('contacts')

const tabs = [
    { key: 'contacts', label: 'Contatos',    get count() { return props.job.contacts.length } },
    { key: 'skills',   label: 'Habilidades', get count() { return props.job.skills.length } },
    { key: 'timeline', label: 'Timeline',    get count() { return props.job.timelines.length } },
    { key: 'resumes',  label: 'Currículos',  get count() { return props.job.resumes.length } },
]

function formatDate(d) {
    const [y, m, day] = d.split('-')
    return `${day}/${m}/${y}`
}

function formatCurrency(v) {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(v)
}

// Delete job
const deleteForm = useForm({})
function deleteJob() {
    if (!confirm(`Excluir a vaga "${props.job.title}"?`)) return
    deleteForm.delete(`/jobs/${props.job.id}`)
}

// Contacts
const contactForm = useForm({ name: '', role: '', email: '', phone: '', linkedin_url: '', notes: '' })
function submitContact() {
    contactForm.post(`/jobs/${props.job.id}/contacts`, {
        preserveScroll: true,
        onSuccess: () => contactForm.reset(),
    })
}
function deleteContact(c) {
    if (!confirm(`Remover o contato "${c.name}"?`)) return
    useForm({}).delete(`/jobs/${props.job.id}/contacts/${c.id}`, { preserveScroll: true })
}

// Skills
const skillForm = useForm({ skill_name: '', level: 'required', matched: false })
function submitSkill() {
    skillForm.post(`/jobs/${props.job.id}/skills`, {
        preserveScroll: true,
        onSuccess: () => skillForm.reset(),
    })
}
function toggleSkill(s) {
    useForm({ matched: !s.matched }).patch(`/jobs/${props.job.id}/skills/${s.id}`, { preserveScroll: true })
}
function deleteSkill(s) {
    if (!confirm(`Remover "${s.skill_name}"?`)) return
    useForm({}).delete(`/jobs/${props.job.id}/skills/${s.id}`, { preserveScroll: true })
}

// Timeline
const timelineForm = useForm({ stage: '', happened_at: '', notes: '' })
function submitTimeline() {
    timelineForm.post(`/jobs/${props.job.id}/timelines`, {
        preserveScroll: true,
        onSuccess: () => timelineForm.reset(),
    })
}
function deleteTimeline(t) {
    if (!confirm('Remover este evento da timeline?')) return
    useForm({}).delete(`/jobs/${props.job.id}/timelines/${t.id}`, { preserveScroll: true })
}
</script>
