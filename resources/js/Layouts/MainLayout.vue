<template>
  <header class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 w-full">
    <div class="container mx-auto"> 
      <nav class="p-4 flex items-center justify-between">
        <!-- <div class="text-lg font-medium">
          <Link :href="route('listing.index')">Listings</Link>
        </div> -->
        <div v-if="user" class="relative">
          <button type="button" class="inline-flex items-center gap-x-1 text-sm font-semibold leading-6 text-gray-900" @click="isShowMenu = !isShowMenu">
            <span>Listing</span>
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
          </button>
          <div class="absolute left-1/2 z-10 mt-5 flex w-screen max-w-max -translate-x-1/4 px-4" :style="!isShowMenu ? 'display: none' : ''">
            <div class="w-screen max-w-md flex-auto overflow-hidden rounded-3xl bg-white text-sm leading-6 shadow-lg ring-1 ring-gray-900/5">
              <div class="p-4">               
                <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50">
                  <div class="mt-1 flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-gray-600 group-hover:text-indigo-600">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                  </div>
                  <div>
                    <Link :href="route('category.index')" class="font-semibold text-gray-900">
                      Fruit categories
                      <span class="absolute inset-0" />
                    </Link>
                    <p class="mt-1 text-gray-600">View list fruit categories</p>
                  </div>
                </div>
                <div class="group relative flex gap-x-6 rounded-lg p-4 hover:bg-gray-50">
                  <div class="mt-1 flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-gray-600 group-hover:text-indigo-600">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                  </div>
                  <div>
                    <Link :href="route('item.index')" class="font-semibold text-gray-900">
                      Fruit items
                      <span class="absolute inset-0" />
                    </Link>    
                    <p class="mt-1 text-gray-600">View list fruit items</p>          
                  </div>
                </div>               
              </div>               
            </div>
          </div>
        </div>   
        <div v-else />
             
        <div class="text-xl text-indigo-600 dark:text-indigo-300 font-bold text-center">
          <Link :href="route('index')">V-Fruit</Link>
        </div>        
        <div v-if="user" class="flex items-center gap-4">        
          <span class="text-sm text-gray-500">{{ user.name }}</span>   
          <div>
            <Link :href="route('logout')" method="delete" as="button">Logout</Link>
          </div>
        </div>
        <div v-else class="flex items-center gap-2">
          <Link :href="route('user-account.create')">Register</Link>
          <Link :href="route('login')">Sign-In</Link>
        </div>
      </nav>
    </div>
  </header>
  
  <main class="container mx-auto p-4 w-full">
    <div v-if="flashSuccess" class="mb-4 border rounded-md shadow-sm border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900 p-2">
      {{ flashSuccess }}
    </div>
    <div v-if="flashError" class="mb-4 border rounded-md shadow-sm border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900 p-2">
      {{ flashError }}
    </div>
    <slot>Default</slot>
  </main>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'

const page = usePage()
const flashSuccess = computed(
  () => page.props.flash.success,
)
const flashError = computed(
  () => page.props.flash.error,
)
const user = computed(
  () => page.props.user,
)

const isShowMenu = ref(false)

router.on('success', (event) => {
  isShowMenu.value = false
})
</script>