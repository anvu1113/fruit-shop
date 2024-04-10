<template>
  <form @submit.prevent="handleSubmit">
    <div class="w-1/2 mx-auto">
      <div>
        <label for="name" class="name">Name</label>
        <input id="name" v-model="form.name" type="text" class="input" />
        <div v-if="form.errors.name" class="input-error">{{ form.errors.name }}</div>
      </div>      
      <div class="mt-4">
        <button class="btn-primary w-full" type="submit" :disabled="isSubmitting">Save</button>       
      </div>
    </div>
  </form>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const props = defineProps({
  category: Object, 
})

const form = useForm({
  name: props.category.name ?? '',  
})

const isUpdate = computed(
  () => Object.keys(props.category).length > 0 ? true : false,
)

const isSubmitting = ref(false)

const handleSubmit = () => {
  isSubmitting.value = true
  if (isUpdate.value) { 
    form.post(route('category.update',  props.category.id), {
      onSuccess: () => {          
        isSubmitting.value = false       
      },     
    })
  } else {
    form.post(route('category.save'), {
      onSuccess: () => {          
        isSubmitting.value = false        
      },     
    })
  } 
}
</script>