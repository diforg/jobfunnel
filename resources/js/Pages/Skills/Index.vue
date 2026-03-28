<template>
    <AppLayout title="Habilidades">
        <template #actions>
            <Link href="/skills/create">
                <PrimaryButton type="button">+ Nova Habilidade</PrimaryButton>
            </Link>
        </template>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nível</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <tr v-for="skill in skills" :key="skill.id" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ skill.name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ skill.category }}</td>
                        <td class="px-6 py-4">
                            <span :class="proficiencyClass(skill.proficiency)" class="px-2.5 py-0.5 rounded-full text-xs font-medium">
                                {{ proficiencyLabel(skill.proficiency) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <Link :href="`/skills/${skill.id}/edit`" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                    Editar
                                </Link>
                                <button @click="deleteSkill(skill)" class="text-xs text-red-500 hover:text-red-700 font-medium">
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!skills.length">
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm">
                            Nenhuma habilidade cadastrada.
                            <Link href="/skills/create" class="text-indigo-600 hover:underline ml-1">Adicionar</Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { Link, useForm } from '@inertiajs/vue3'

defineProps({ skills: Array })

const proficiencyMap = {
    beginner:     { label: 'Iniciante',     color: 'bg-gray-100 text-gray-700' },
    intermediate: { label: 'Intermediário', color: 'bg-blue-100 text-blue-700' },
    expert:       { label: 'Especialista',  color: 'bg-green-100 text-green-700' },
}

function proficiencyLabel(p) { return proficiencyMap[p]?.label ?? p }
function proficiencyClass(p) { return proficiencyMap[p]?.color ?? 'bg-gray-100 text-gray-600' }

const deleteForm = useForm({})
function deleteSkill(skill) {
    if (!confirm(`Excluir a habilidade "${skill.name}"?`)) return
    deleteForm.delete(`/skills/${skill.id}`)
}
</script>
