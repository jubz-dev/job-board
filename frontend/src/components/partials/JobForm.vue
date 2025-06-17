<template>
  <form
    @submit.prevent="submitJob"
    class="max-w-xl mx-auto mt-12 w-full rounded-lg bg-white p-6 shadow-md sm:p-8 border-t border-gray-200"
  >
    <h2 class="text-2xl font-semibold text-gray-900 mb-1">Post a Job</h2>
    <p class="mb-6 text-gray-600 text-sm">Fill out the form below to submit a job post for review.</p>

    <div class="mb-5">
      <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
      <input
        id="title"
        v-model="title"
        type="text"
        placeholder="e.g. Frontend Developer"
        required
        class="w-full rounded-md border border-gray-300 px-4 py-2 text-gray-800 focus:border-indigo-500 focus:ring-indigo-200 focus:ring-2 outline-none transition"
      />
    </div>

    <div class="mb-5">
      <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Job Description</label>
      <textarea
        id="description"
        v-model="description"
        rows="5"
        placeholder="Include responsibilities, qualifications, etc."
        required
        class="w-full resize-y rounded-md border border-gray-300 px-4 py-2 text-gray-800 focus:border-indigo-500 focus:ring-indigo-200 focus:ring-2 outline-none transition"
      ></textarea>
    </div>

    <div class="mb-6">
      <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Your Email</label>
      <input
        id="email"
        v-model="email"
        type="email"
        placeholder="contact@company.com"
        required
        class="w-full rounded-md border border-gray-300 px-4 py-2 text-gray-800 focus:border-indigo-500 focus:ring-indigo-200 focus:ring-2 outline-none transition"
      />
      <p class="text-xs text-gray-500 mt-1">We’ll use this to notify you once the job is reviewed.</p>
    </div>

    <button
      type="submit"
      :disabled="loading"
      class="w-full rounded-md bg-indigo-600 py-2 px-4 text-white font-medium hover:bg-indigo-700 transition disabled:opacity-50 cursor-pointer"
    >
      {{ loading ? 'Submitting...' : 'Submit Job' }}
    </button>

    <p class="mt-4 text-sm text-green-600" v-if="message">{{ message }}</p>
  </form>
</template>

<script setup lang="ts">
import { useJobForm } from '../../composables/useJobForm'

const {
  title,
  description,
  email,
  message,
  loading,
  submitJob
} = useJobForm()
</script>