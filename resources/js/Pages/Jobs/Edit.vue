<template>
    <AppLayout :title="`Editar: ${job.title}`">
        <div class="max-w-2xl">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <form @submit.prevent="submit" class="space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <InputLabel>Título da Vaga *</InputLabel>
                            <TextInput v-model="form.title" type="text" required />
                            <InputError :message="form.errors.title" />
                        </div>
                        <div class="col-span-2">
                            <InputLabel>Empresa *</InputLabel>
                            <TextInput v-model="form.company" type="text" required />
                            <InputError :message="form.errors.company" />
                        </div>
                        <div class="col-span-2">
                            <InputLabel>Status *</InputLabel>
                            <select v-model="form.status" class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option v-for="s in statuses" :key="s.value" :value="s.value">{{ s.label }}</option>
                            </select>
                            <InputError :message="form.errors.status" />
                        </div>
                        <div>
                            <InputLabel>Fonte</InputLabel>
                            <TextInput v-model="form.source_name" type="text" />
                            <InputError :message="form.errors.source_name" />
                        </div>
                        <div>
                            <InputLabel>Data de Candidatura</InputLabel>
                            <TextInput v-model="form.applied_at" type="date" />
                            <InputError :message="form.errors.applied_at" />
                        </div>
                        <div class="col-span-2">
                            <InputLabel>URL da Vaga (fonte)</InputLabel>
                            <TextInput v-model="form.source_url" type="url" />
                            <InputError :message="form.errors.source_url" />
                        </div>
                        <div class="col-span-2">
                            <InputLabel>URL de Candidatura</InputLabel>
                            <TextInput v-model="form.apply_url" type="url" />
                            <InputError :message="form.errors.apply_url" />
                        </div>
                        <div class="col-span-2">
                            <InputLabel>Pretensão Salarial (R$)</InputLabel>
                            <TextInput v-model="form.salary_expectation" type="number" step="100" min="0" />
                            <InputError :message="form.errors.salary_expectation" />
                        </div>
                        <div class="col-span-2">
                            <InputLabel>Descrição da Vaga</InputLabel>
                            <textarea v-model="form.description" rows="4"
                                class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <InputError :message="form.errors.description" />
                        </div>
                        <div class="col-span-2">
                            <InputLabel>Anotações</InputLabel>
                            <textarea v-model="form.notes" rows="3"
                                class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            />
                            <InputError :message="form.errors.notes" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <Link :href="`/jobs/${job.id}`"><SecondaryButton type="button">Cancelar</SecondaryButton></Link>
                        <PrimaryButton :disabled="form.processing">
                            {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({ job: Object })

const statuses = [
    { value: 'identified',          label: 'Identificada' },
    { value: 'applied',             label: 'Candidatado' },
    { value: 'recruiter_interview', label: 'Entrevista RH' },
    { value: 'technical_interview', label: 'Entrevista Técnica' },
    { value: 'technical_test',      label: 'Teste Técnico' },
    { value: 'offer',               label: 'Oferta Recebida' },
    { value: 'hired',               label: 'Contratado' },
    { value: 'rejected',            label: 'Reprovado' },
    { value: 'ghosted',             label: 'Ghosted' },
]

const form = useForm({
    title:              props.job.title,
    company:            props.job.company,
    status:             props.job.status,
    source_name:        props.job.source_name ?? '',
    source_url:         props.job.source_url ?? '',
    apply_url:          props.job.apply_url ?? '',
    applied_at:         props.job.applied_at ?? '',
    salary_expectation: props.job.salary_expectation ?? '',
    description:        props.job.description ?? '',
    notes:              props.job.notes ?? '',
})

function submit() {
    form.put(`/jobs/${props.job.id}`)
}
</script>
