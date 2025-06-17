<template>
  <header
    :class="[
      'sticky top-0 inset-x-0 z-50 transition-all duration-300 backdrop-blur-md',
      isScrolled ? 'bg-black/80 text-white shadow-sm' : 'bg-transparent text-black'
    ]"
  >
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <div class="flex justify-between items-center">
        <!-- Logo -->
        <RouterLink to="/" class="text-xl font-bold tracking-wide flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-wallet-fill" viewBox="0 0 16 16">
            <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v2h6a.5.5 0 0 1 .5.5c0 .253.08.644.306.958.207.288.557.542 1.194.542s.987-.254 1.194-.542C9.42 6.644 9.5 6.253 9.5 6a.5.5 0 0 1 .5-.5h6v-2A1.5 1.5 0 0 0 14.5 2z"/>
            <path d="M16 6.5h-5.551a2.7 2.7 0 0 1-.443 1.042C9.613 8.088 8.963 8.5 8 8.5s-1.613-.412-2.006-.958A2.7 2.7 0 0 1 5.551 6.5H0v6A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5z"
            :class="isScrolled ? 'text-white' : 'text-black'"/>
          </svg> JOB BOARD
        </RouterLink>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-6 text-sm font-medium">
          <template v-for="(link, index) in navLinks" :key="link.label || link.to || index">
            <RouterLink
              v-if="link.to"
              :to="link.to"
              class="hover:text-indigo-400 transition-colors"
              :class="{ 'text-indigo-400 font-semibold': $route.path === link.to }"
            >
              {{ link.label }}
            </RouterLink>
            <span v-else class="text-gray-300">|</span>
          </template>
        </div>

        <!-- Mobile Toggle -->
        <button
          @click="toggleMenu"
          class="md:hidden text-3xl focus:outline-none text-inherit cursor-pointer"
          aria-label="Toggle navigation"
          :aria-expanded="mobileMenuVisible"
        >
          <i :class="mobileMenuVisible ? 'bx bx-x' : 'bx bx-menu'"></i>
        </button>
      </div>
    </nav>

    <!-- Mobile Menu -->
    <transition name="fade">
      <div v-if="mobileMenuVisible" class="md:hidden bg-black/90 text-white px-6 py-4 space-y-3 text-base">
        <RouterLink
          v-for="(link, index) in navLinks.filter(l => l.to)"
          :key="link.to || index"
          :to="link.to!"
          class="block hover:text-indigo-300"
          @click="toggleMenu"
        >
          {{ link.label }}
        </RouterLink>
      </div>
    </transition>
  </header>
</template>

<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue'

interface NavLink {
  to?: string
  label?: string
  separator?: boolean
}

const isScrolled = ref(false)
const mobileMenuVisible = ref(false)

const toggleMenu = () => {
  mobileMenuVisible.value = !mobileMenuVisible.value
}

const handleScroll = () => {
  isScrolled.value = window.scrollY > 100
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onBeforeUnmount(() => {
  window.removeEventListener('scroll', handleScroll)
})

const navLinks: NavLink[] = [
  { to: '/', label: 'Job Seeker' },
  { separator: true },
  { to: '/post-job', label: 'Post a Job' },
]
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease-in-out;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>