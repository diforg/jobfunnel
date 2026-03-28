<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-sm p-8">
      <h1 class="text-2xl font-bold text-gray-900 mb-2">Criar conta</h1>
      <p class="text-sm text-gray-500 mb-6">
        Já tem conta?
        <a :href="route('login')" class="text-indigo-600 hover:underline">Entrar</a>
      </p>

      <form @submit.prevent="submit" novalidate>
        <!-- Name -->
        <div class="mb-4">
          <InputLabel for="name" value="Nome completo" />
          <TextInput
            id="name"
            v-model="form.name"
            type="text"
            class="mt-1 block w-full"
            autocomplete="name"
          />
          <InputError :message="form.errors.name" class="mt-1" />
        </div>

        <!-- Email -->
        <div class="mb-4">
          <InputLabel for="email" value="E-mail" />
          <TextInput
            id="email"
            v-model="form.email"
            type="email"
            class="mt-1 block w-full"
            autocomplete="email"
          />
          <InputError :message="form.errors.email" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="mb-4">
          <InputLabel for="password" value="Senha (mín. 8 caracteres)" />
          <TextInput
            id="password"
            v-model="form.password"
            type="password"
            class="mt-1 block w-full"
            autocomplete="new-password"
          />
          <InputError :message="form.errors.password" class="mt-1" />
        </div>

        <!-- Password confirmation -->
        <div class="mb-6">
          <InputLabel for="password_confirmation" value="Confirmar senha" />
          <TextInput
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            class="mt-1 block w-full"
            autocomplete="new-password"
          />
          <InputError :message="form.errors.password_confirmation" class="mt-1" />
        </div>

        <PrimaryButton type="submit" class="w-full justify-center" :disabled="form.processing">
          {{ form.processing ? 'Aguarde…' : 'Criar conta' }}
        </PrimaryButton>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

function submit() {
  form.post('/register', {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>
