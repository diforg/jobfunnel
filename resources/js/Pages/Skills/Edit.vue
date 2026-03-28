<template>
    <AppLayout :title="`Editar: ${skill.name}`">
        <div class="max-w-md">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel>Nome *</InputLabel>
                        <TextInput v-model="form.name" type="text" required />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div>
                        <InputLabel>Categoria</InputLabel>
                        <TextInput v-model="form.category" type="text" />
                        <InputError :message="form.errors.category" />
                    </div>
                    <div>
                        <InputLabel>Nível de Proficiência *</InputLabel>
                        <select v-model="form.proficiency" class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="beginner">Iniciante</option>
                            <option value="intermediate">Intermediário</option>
                            <option value="expert">Especialista</option>
                        </select>
                        <InputError :message="form.errors.proficiency" />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                        <Link href="/skills"><SecondaryButton type="button">Cancelar</SecondaryButton></Link>
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

const props = defineProps({ skill: Object })

const form = useForm({
    name:        props.skill.name,
    category:    props.skill.category ?? '',
    proficiency: props.skill.proficiency,
})

function submit() {
    form.put(`/skills/${props.skill.id}`)
}
</script>
