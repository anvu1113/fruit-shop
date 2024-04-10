<template>
  <form @submit.prevent="handleSubmit">
    <div class="w-1/2 mx-auto">
      <div>
        <label for="name">Name</label>
        <input id="name" v-model="form.name" type="text" class="input" />
        <div v-if="form.errors.name" class="input-error">{{ form.errors.name }}</div>
      </div>      
      <div>
        <label for="category">Category</label>
        <select v-model="form.category_id" class="input">
          <option value="">Select category</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
        </select>
        <div v-if="form.errors.category_id" class="input-error">{{ form.errors.category_id }}</div>
      </div>
      <div>
        <label for="price">Price</label>
        <input id="price" v-model="form.price" type="number" class="input" />
        <div v-if="form.errors.price" class="input-error">{{ form.errors.price }}</div>
      </div> 
      <div>
        <label for="unit">Unit</label>
        <select v-model="form.unit" class="input">
          <option value="">Select unit</option>
          <option value="kg">Kg</option>
          <option value="pcs">Pcs</option>
          <option value="pack">Pack</option>
        </select>
        <div v-if="form.errors.unit" class="input-error">{{ form.errors.unit }}</div>
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
  item: Object, 
  categories: Object, 
})

const form = useForm({
  name: props.item.name ?? '',  
  category_id: props.item.category_id ?? '',  
  price: props.item.price ? parseFloat(props.item.price) : '',  
  unit: props.item.unit ?? '',  
})

const isUpdate = computed(
  () => Object.keys(props.item).length > 0 ? true : false,
)

const isSubmitting = ref(false)

const handleSubmit = () => {
  isSubmitting.value = true
  if (isUpdate.value) { 
    form.post(route('item.update',  props.item.id), {
      onSuccess: () => {          
        isSubmitting.value = false       
      },     
    })
  } else {
    form.post(route('item.save'), {
      onSuccess: () => {          
        isSubmitting.value = false        
      },     
    })
  } 
}
</script>