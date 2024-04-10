<template>
  <form @submit.prevent="filter">  
    <div class="mb-8 mt-4 flex justify-end gap-2">       
      <div class="flex">
        <select v-model="filterForm.customer_id" class="input w-full px-3 h-10 md:w-80 rounded-l">
          <option value="">Select customer</option>
          <option v-for="customer in customers" :key="customer[0].id" :value="customer[0].id">{{ customer[0].name }}</option>
        </select>        
        <button type="submit" class="bg-indigo-600 text-white rounded-r px-2 md:px-3 py-0 md:py-2 w-24 flex align-middle font-medium">
          <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
          </svg>
          <span>Search</span>          
        </button>
      </div>     
      <Link :href="route('invoice.create')" class="btn-primary flex" as="button">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg> 
        Create invoice
      </Link>    
    </div>
  </form>
  <div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <tr>
          <th scope="col" class="px-6 py-3 text-right w-48">
            No
          </th>          
          <th scope="col" class="px-6 py-3">
            Customer Name
          </th>         
          <th scope="col" class="px-6 py-3 text-right">
            Total Amount
          </th>        
          <th scope="col" class="px-6 py-3">
            Created at
          </th>
          <th scope="col" class="px-6 py-3">
            Updated at
          </th>
          <th scope="col" class="px-6 py-3">
            Action
          </th>
        </tr>
      </thead>
      <tbody>
        <template v-if="invoices.data.length">
          <tr v-for="(invoice, key) in invoices.data" :key="invoice.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-right">
              {{ numberByPage+key+1 }}
            </td>   
            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
              {{ invoice.customer_id && customers[invoice.customer_id] ? customers[invoice.customer_id][0].name : 'N/A' }}
            </td>            
            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-right">
              {{ invoice.total_price_formatted }}
            </td> 
            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
              {{ invoice.created_at }}
            </td>            
            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
              {{ invoice.updated_at }}
            </td>            
            <td scope="row " class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white flex">
              <Link :href="route('invoice.edit', invoice.id)"> 
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                </svg>
              </Link>    
              <a class="cursor-pointer" type="button" @click="deleteInvoice(invoice.id)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" class="w-6 h-6">
                  <path strokeLinecap="round" strokeLinejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
              </a>              
              <a class="cursor-pointer" type="button" @click="printInvoice(invoice.id)">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
              </a>              
            </td>            
          </tr>
        </template>
        <template v-else>
          <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center" colspan="7">
              No data
            </td>            
          </tr>      
        </template>         
      </tbody>
    </table>
    <div v-if="invoices.data.length" class="w-full flex justify-center mt-4 mb-4">
      <Pagination :links="invoices.links" />
    </div>
  </div> 
</template>
<script setup>
import {Link, useForm} from '@inertiajs/vue3'
import { ref } from 'vue'
import Pagination from '@/Components/UI/Pagination.vue'

const props = defineProps({
  filters: Object,
  invoices: Object,
  customers: Object,
  limit: Number,
})

const pageCurrent = ref(props.invoices.current_page)

const numberByPage = ref((pageCurrent.value*props.limit) - props.limit)

const filterForm = useForm({
  customer_id: props.filters.customer_id ?? '',
})

const filter = () => {
  filterForm.get(
    route('invoice.index'),
    {
      preserveState: true,
      preserveScroll: true,
    },
  )
}

const deleteInvoice = (id) => {
  if (confirm('Are you sure you want to delete this invoice?') && id > 0) {
    useForm({}).delete(route('invoice.destroy', id))
  }
}

const printInvoice = (id) => {
  window.open('/invoice/review-pdf/' + id, '_blank')
}

</script>