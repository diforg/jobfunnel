<template>
    <div class="min-h-screen bg-gray-50 flex">
        <!-- Sidebar -->
        <aside class="w-60 bg-indigo-900 text-white flex flex-col min-h-screen shrink-0">
            <div class="px-5 py-5 border-b border-indigo-800">
                <h1 class="text-lg font-bold tracking-tight">JobFunnel</h1>
                <p class="text-indigo-400 text-xs mt-0.5">Gestão de Oportunidades</p>
            </div>
            <nav class="flex-1 px-3 py-4 space-y-0.5">
                <Link href="/" :class="navClass('/')">Dashboard</Link>
                <Link href="/jobs" :class="navClass('/jobs')">Vagas</Link>
                <Link href="/skills" :class="navClass('/skills')">Habilidades</Link>
            </nav>
        </aside>

        <!-- Main -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between shrink-0">
                <h2 class="text-base font-semibold text-gray-900">{{ title }}</h2>
                <div class="flex items-center gap-3">
                    <div v-if="user" class="hidden sm:flex flex-col items-end leading-tight">
                        <span class="text-sm font-medium text-gray-900">{{ user.name }}</span>
                        <span class="text-xs text-gray-500">{{ user.email }}</span>
                    </div>
                    <slot name="actions" />
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="inline-flex items-center px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                    >
                        Sair
                    </Link>
                </div>
            </header>

            <div v-if="$page.props.flash?.success"
                class="mx-8 mt-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
                {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash?.error"
                class="mx-8 mt-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
                {{ $page.props.flash.error }}
            </div>

            <main class="flex-1 overflow-y-auto p-8">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

defineProps({
    title: { type: String, default: '' },
})

const page = usePage()
const url = computed(() => page.url)
const user = computed(() => page.props.auth?.user ?? null)

function navClass(href) {
    const active = href === '/' ? url.value === '/' : url.value.startsWith(href)
    return [
        'block px-3 py-2 rounded-lg text-sm font-medium transition-colors',
        active ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800 hover:text-white',
    ]
}
</script>
