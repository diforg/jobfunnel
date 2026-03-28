<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-sm p-8">
      <h1 class="text-2xl font-bold text-gray-900 mb-2">Entrar</h1>
      <p class="text-sm text-gray-500 mb-6">
        Não tem conta?
        <a :href="route('register')" class="text-indigo-600 hover:underline">Cadastre-se</a>
      </p>

      <div v-if="$page.props.flash?.warning" class="mb-4 text-sm text-yellow-800 bg-yellow-50 rounded p-3">
        {{ $page.props.flash.warning }}
      </div>

      <form @submit.prevent="submit" novalidate>
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
        <div class="mb-6">
          <InputLabel for="password" value="Senha" />
          <TextInput
            id="password"
            v-model="form.password"
            type="password"
            class="mt-1 block w-full"
            autocomplete="current-password"
          />
          <InputError :message="form.errors.password" class="mt-1" />
        </div>

        <div class="flex items-center gap-3 mb-6">
          <input id="remember" v-model="form.remember" type="checkbox" class="rounded border-gray-300 text-indigo-600" />
          <InputLabel for="remember" value="Lembrar-me" class="!mb-0 cursor-pointer" />
        </div>

        <PrimaryButton type="submit" class="w-full justify-center" :disabled="form.processing">
          {{ form.processing ? 'Entrando…' : 'Entrar' }}
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
  email: '',
  password: '',
  remember: false,
})

function submit() {
  form.post('/login', { onFinish: () => form.reset('password') })
}
</script>
