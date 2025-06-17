import { ref } from 'vue'
import { api } from '../helpers/config'

export function useJobForm() {
  const title = ref('')
  const description = ref('')
  const email = ref('')
  const message = ref('')
  const loading = ref(false)
  const success = ref(false)

  const submitJob = async () => {
    message.value = ''
    loading.value = true
    success.value = false

    try {
      await api.post('/jobPosts', {
        title: title.value,
        description: description.value,
        email: email.value,
      })

      title.value = ''
      description.value = ''
      email.value = ''

      success.value = true
      message.value = 'Job submitted successfully!'
    } catch (error: any) {
      success.value = false
      message.value = error?.response?.data?.message || 'Submission failed. Please try again.'
    } finally {
      loading.value = false
    }
  }

  return {
    title,
    description,
    email,
    message,
    loading,
    submitJob
  }
}