import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  scrollBehavior() {
    return { x: 0, y: 0 }
  },
  routes: [
    {
      path: '/',
      name: 'dashboard',
      component: () => import('@/views/Category.vue'),
      meta: {
        pageTitle: 'คลังหมวดคำถาม',
        breadcrumb: [
          {
            text: 'คลังหมวดคำถาม',
            active: true,
          },
        ],
      },
    },
    {
      path: '/category-archives',
      name: 'category-archives',
      component: () => import('@/views/Category.vue'),
      meta: {
        pageTitle: 'คลังหมวดคำถาม',
        breadcrumb: [
          {
            text: 'คลังหมวดคำถาม',
            active: true,
          },
        ],
      },
    },
    {
      path: '/keyword-library',
      name: 'keyword-library',
      component: () => import('@/views/Keyword.vue'),
      meta: {
        pageTitle: 'คลังคำสำคัญ',
        breadcrumb: [
          {
            text: 'คลังคำสำคัญ',
            active: true,
          },
        ],
      },
    },
    {
      path: '/answers-library',
      name: 'answers-library',
      component: () => import('@/views/Answers.vue'),
      meta: {
        pageTitle: 'คลังคำตอบ',
        breadcrumb: [
          {
            text: 'คลังคำตอบ',
            active: true,
          },
        ],
      },
    },
    {
      path: '/question-archive',
      name: 'question-archive',
      component: () => import('@/views/Question.vue'),
      meta: {
        pageTitle: 'คลังคำถาม',
        breadcrumb: [
          {
            text: 'คลังคำถาม',
            active: true,
          },
        ],
      },
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/Login.vue'),
      meta: {
        layout: 'full',
      },
    },
    {
      path: '/error-404',
      name: 'error-404',
      component: () => import('@/views/error/Error404.vue'),
      meta: {
        layout: 'full',
      },
    },
    {
      path: '*',
      redirect: 'error-404',
    },
  ],
})

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(() => {
  // Remove initial loading
  const appLoading = document.getElementById('loading-bg')
  if (appLoading) {
    appLoading.style.display = 'none'
  }
})

export default router
