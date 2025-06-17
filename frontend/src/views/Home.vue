<template>
  <main class="p-6 max-w-4xl mx-auto">
    <h1 class="text-3xl font-semibold text-gray-900 mb-6">Job Listings</h1>

    <div
      v-for="job in jobs"
      :key="job.id"
      class="mb-6 rounded-lg border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition-shadow"
    >
      <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ job.title }}</h2>
      <p class="text-gray-700 leading-relaxed mb-3 whitespace-pre-line">{{ job.description }}</p>
      <div class="text-sm text-gray-500 mb-3">Source: {{ job.source }}</div>

      <a
        v-if="job.source === 'external'"
        :href="job.source_url"
        class="inline-block rounded-md bg-[#2557a7] border-2 border-[#2557a7] px-4 py-2 text-white font-medium hover:bg-[#1f4a8e] transition"
        target="_blank"
        rel="noopener noreferrer"
      >
        View Job
      </a>
    </div>
  </main>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { api } from '../helpers/config';
import { initEcho } from '../helpers/echo';

interface Job {
  id: string;
  title: string;
  description: string;
  source: 'internal' | 'external';
  source_url?: string;
  created_at: string;
  status?: string;
}

const jobs = ref<Job[]>([]);

/**
 * Load job posts from API.
 */
const fetchJobs = async (): Promise<void> => {
  try {
    const { data } = await api.get<Job[]>('/jobPosts');
    jobs.value = data;
  } catch (error) {
    console.error('❌ Failed to fetch jobs:', error);
  }
};

/**
 * Setup real-time Echo listener.
 */
const setupRealtimeUpdates = async (): Promise<void> => {
  try {
    const echo = await initEcho();
    echo.channel('jobs-status-updates').listen('JobStatusUpdated', (event: { jobPost: Job }) => {
      const updated = event.jobPost;
      const index = jobs.value.findIndex(j => j.id === updated.id);

      if (index !== -1) {
        updated.status === 'approve'
          ? jobs.value.splice(index, 1, updated)
          : jobs.value.splice(index, 1);
      } else if (updated.status === 'approve') {
        jobs.value.unshift(updated);
      }
    });
  } catch (error) {
    console.error('Failed to initialize Echo:', error);
  }
};

onMounted(() => {
  fetchJobs();
  setupRealtimeUpdates();
});
</script>