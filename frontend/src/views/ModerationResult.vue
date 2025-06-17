<template>
  <div class="p-8 text-center">
    <h1 class="text-2xl font-bold mb-4">Moderator Action</h1>
    <p class="text-lg text-gray-700">{{ statusMessage }}</p>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const statusMessage = ref('Processing...')

const statusMap: Record<string, string> = {
  approve: 'Job approved successfully.',
  spam: 'Job marked as spam.',
}

onMounted(() => {
  const action = route.query.action?.toString() ?? ''
  statusMessage.value = statusMap[action] ?? 'Invalid action or expired link.'
})
</script>