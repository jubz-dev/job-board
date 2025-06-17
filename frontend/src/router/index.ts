import { createRouter, createWebHistory } from 'vue-router'

import Home from '../views/Home.vue'
import PostJob from '../views/PostJob.vue'
import ModerationResult from '../views/ModerationResult.vue'

const routes = [
  { path: '/', component: Home },
  { path: '/post-job', component: PostJob },
  { path: '/moderate-result', component: ModerationResult }
];

export const router = createRouter({
  history: createWebHistory(),
  routes,
});