<template>
  <form @submit.prevent="handleSubmit">
    <div class="w-3/4 mx-auto">          
      <div>
        <label for="customer">Customer</label>
        <div class="flex">
          <select v-model="form.customer_id" class="input w-full px-3 h-10 rounded-l">
            <option value="">Select customer</option>
            <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
          </select>
          <button type="button" title="Add new customer" class="bg-indigo-600 text-white rounded-r px-2 md:px-3 py-0 md:py-2 w-210 align-middle font-medium" @click="isShowModal = !isShowModal">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>               
          </button>
        </div>       
        <div v-if="form.errors.customer_id" class="input-error">{{ form.errors.customer_id }}</div>
      </div>
      <div class="mt-4">
        <button type="button" title="Add new customer" class="flex bg-indigo-600 text-white px-2 md:px-3 py-0 md:py-2 w-210 align-middle font-medium" @click="addItem">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>   
          <span>Add Item</span>            
        </button>
      </div>
      <div v-if="form.errors.items" class="input-error text-center">{{ form.errors.items }}</div>
      <div class="relative overflow-x-auto mt-4">
        <table class="text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 table-auto w-full">
          <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
            <tr>
              <th scope="col" class="px-4 py-2 text-right w-10">
                No
              </th>
              <th scope="col" class="px-4 py-2">
                Item
              </th>
              <th scope="col" class="px-4 py-2">
                Category
              </th>
              <th scope="col" class="px-4 py-2">
                Unit
              </th>
              <th scope="col" class="px-4 py-2 text-right w-36">
                Quantity
              </th>
              <th scope="col" class="px-4 py-2 text-right">
                Price
              </th>            
              <th scope="col" class="px-4 py-2 text-right ">
                Amount
              </th>            
              <th scope="col" class="px-4 py-2 text-center">
                Action
              </th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(value, index) in itemArr" :key="index">
              <tr ref="rowItem" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 bg-gray-100">
                <td scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white text-right"> 
                  {{ parseInt(index)+1 }}             
                </td>            
                <td scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  <select v-model="form.items[index]" class="input" :data-index="index" @change="selectItem">
                    <option value="">Select item</option>
                    <option v-for="item in items" :key="item.id" :value="item.id" :data-value="JSON.stringify(item)">{{ item.name }}</option>
                  </select>    
                  <div v-if="form.errors['items.'+index]" class="input-error">{{ form.errors['items.'+index] }}</div>         
                </td>  
                <td scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  {{ arrDetailItem.categories[index] ?? '' }}
                </td>
               
                <td scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                  {{ arrDetailItem.units[index] ?? '' }}  
                </td>               
                <td scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">   
                  <input ref="elemQuantity" v-model="form.quantities[index]" type="number" :data-index="index" class="input text-right" placeholder="Enter quantity" @change="changeQuantity" />             
                  <div v-if="form.errors['quantities']" class="input-error">{{ form.errors['quantities'] }}</div>      
                  <div v-if="form.errors['quantities.'+index]" class="input-error">{{ form.errors['quantities.'+index] }}</div>         
                </td>            
                <td scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white text-right">        
                  {{ arrDetailItem.prices[index] ?? 0 }}    
                </td>     
                <td scope="row" class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white text-right">        
                  {{ arrDetailItem.amounts[index] ?? 0 }}  
                </td>     
                <td scope="row " class="px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white text-center">               
                  <button type="button" @click="removeItem">
                    <svg :data-id="itemArr[parseInt(index)] != undefined && itemArr[parseInt(index)].id != undefined ? itemArr[parseInt(index)].id : 0" :data-index="index" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor" class="w-6 h-6">
                      <path strokeLinecap="round" strokeLinejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                  </button>              
                </td>            
              </tr>
            </template> 
            <template v-if="itemArr != undefined && (Object.keys(itemArr).length > 0)">
              <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 bg-gray-100">
                <td class="text-right px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white" colspan="6">Total</td>
                <td ref="elemTotalPrice" class="text-right px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ totalPrice }}</td>
                <td class="text-right px-4 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white"></td>
              </tr>
            </template>                   
          </tbody>
        </table>
      </div>      
      <div class="mt-4 text-right">
        <button class="btn-primary w-32" type="submit">Save</button>       
      </div>
    </div>
  </form>
  <div id="modal-box" tabindex="-1" :class="isShowModal ? '' : 'hidden'" class="bg-black/50 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[999] h-full items-center justify-center popup flex">
    <div class="relative p-4 w-full max-w-md h-full md:h-auto">
      <div class="relative bg-white rounded-lg shadow popup-body">
        <button 
          type="button"  
          class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center popup-close"
          @click="isShowModal = false"
        >
          <svg aria-hidden="true" class="w-5 h-5" fill="#c6c7c7" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path
              fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              cliprule="evenodd"
            />
          </svg>
          <span class="sr-only">Close popup</span>
        </button>
        <div class="p-5">
          <h3 class="text-2xl mb-0.5 font-medium" />
          <p class="mb-4 text-sm font-normal text-gray-800" />
          <div class="mb-7 text-center">
            <p class="mb-3 text-2xl font-semibold leading-5 text-slate-900">
              Create new customer
            </p>            
          </div>  
          <div class="w-full">
            <label for="name" class="sr-only">Full Name</label>
            <input 
              v-model="formCustomer.name" type="text" required="" placeholder="Full Name"
              class="mb-3 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-black focus:ring-offset-1"
            />
            <div v-if="formCustomer.errors.name" class="input-error mb-3">{{ formCustomer.errors.name }}</div>
            <label for="phone" class="sr-only">Phone Number</label>
            <input 
              v-model="formCustomer.phone_number" type="text" required="" placeholder="Phone Number" 
              class="mt-2 mb-3 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-black focus:ring-offset-1"
            />    
            <div v-if="formCustomer.errors.phone_number" class="input-error mb-3">{{ formCustomer.errors.phone_number }}</div>
            <label for="address" class="sr-only">Address</label>
            <input 
              v-model="formCustomer.address" type="text" required="" placeholder="Address" 
              class="mt-2 mb-3 block w-full rounded-lg border border-gray-300 px-3 py-2 shadow-sm outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-black focus:ring-offset-1"
            />   
            <div v-if="formCustomer.errors.address" class="input-error mb-3">{{ formCustomer.errors.address }}</div>         
            <button type="button" class="btn-primary w-full" :disabled="isSubmittingCustomer" @click="handleSubmitCustomer">
              Save
            </button>
          </div>            
        </div>      
      </div>
    </div>
  </div>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref, computed, reactive, onMounted } from 'vue'

const props = defineProps({
  invoice: Object, 
  customers: Object, 
  categories: Object, 
  items: Object, 
  invoiceDetail: Object, 
  totalPrice: Number, 
})

const itemArr = ref({})
const itemCount = ref(0)

const isShowModal = ref(false)

const isUpdate = computed(
  () => Object.keys(props.invoice).length > 0  && props.invoice.id > 0 ? true : false,
)

const isSubmitting = ref(false)

const form = useForm({
  customer_id: props.invoice.customer_id ?? '',  
  ids: props.invoiceDetail.ids ?? [],  
  items: props.invoiceDetail.items ?? [],  
  quantities: props.invoiceDetail.quantities ?? [],  
  amounts: props.invoiceDetail.amounts ?? [],  
})

const arrDetailItem = reactive({
  units: props.invoiceDetail.units ?? [],  
  categories: props.invoiceDetail.categories ?? [],  
  prices: props.invoiceDetail.prices ?? [],  
  amounts: props.invoiceDetail.amounts ?? [],  
})

const handleSubmit = () => {
  isSubmitting.value = true
  form.amounts = arrDetailItem.amounts
  if (isUpdate.value) { 
    form.post(route('invoice.update',  props.invoice.id), {
      onSuccess: () => {          
        isSubmitting.value = false       
      },     
    })
  } else {
    form.post(route('invoice.save'), {
      onSuccess: () => {          
        isSubmitting.value = false        
      },     
    })
  } 
}

const rowItem = ref([])
const elemQuantity = ref([])
const elemTotalPrice = ref([])

const selectItem = (event) => {
  let selectElement = event.target
  let indexSelected = event.target.dataset.index
  let selectedIndex  = event.target.selectedIndex
  let selectedOptionValue = selectElement.options[selectedIndex].dataset.value
  if (rowItem.value[indexSelected]) { 
    arrDetailItem['units'][indexSelected] = ''
    arrDetailItem['categories'][indexSelected] = ''
    arrDetailItem['prices'][indexSelected] = 0
    arrDetailItem['amounts'][indexSelected] = 0
    if (selectedOptionValue != undefined && selectedOptionValue != '') { 
      selectedOptionValue = JSON.parse(selectedOptionValue)
      if (props.categories[selectedOptionValue.category_id]) {
        arrDetailItem['categories'][indexSelected] = props.categories[selectedOptionValue.category_id][0].name
      }      
      arrDetailItem['units'][indexSelected]= selectedOptionValue.unit
      arrDetailItem['prices'][indexSelected] = parseFloat(selectedOptionValue.price)
      if (elemQuantity.value[indexSelected] && elemQuantity.value[indexSelected].value > 0) {
        let quantity = elemQuantity.value[indexSelected].value
        let amount = selectedOptionValue.price * quantity
        arrDetailItem['amounts'][indexSelected] = amount        
      }
      caculatorTotal()
    }     
  }  
}

const changeQuantity = (event) => {
  let indexSelected = event.target.dataset.index
  let price =  arrDetailItem['prices'][indexSelected]  
  let quantity = event.target.value  
  arrDetailItem['amounts'][indexSelected] = 0
  if (quantity > 0 && price > 0) {
    let amount = parseFloat(price) * quantity
    arrDetailItem['amounts'][indexSelected] = amount
  }
  caculatorTotal()
}

const caculatorTotal = () => {
  let total = 0
  if(rowItem.value.length > 0) {
    rowItem.value.forEach((item, index) => {
      let amount = arrDetailItem['amounts'][index] !== undefined ? parseFloat(arrDetailItem['amounts'][index]) : 0
      total += amount
    })
  }
  elemTotalPrice.value.textContent = total
}

const addItem = (firstLoad = false) => {  

  if (firstLoad != undefined && firstLoad == true && isUpdate.value == true) {
    // Update
    itemCount.value = Object.keys(props.invoiceDetail.items).length
    itemArr.value =  props.invoiceDetail.items
  } else {
    form.ids[itemCount.value] = 0
    form.items[itemCount.value] = ''
    form.quantities[itemCount.value] = ''
    itemCount.value++
    let newCount = itemCount.value
    // let index = newCount - 1 
    let objectPush = {}
    objectPush[newCount] = itemCount.value
    // push objectPush to taskArr 
    itemArr.value[Object.keys(itemArr.value).length] = objectPush
  }  
}

const removeItem = (event) => {
  const id = parseInt(event.target.getAttribute('data-id'))
  const indexId = parseInt(event.target.getAttribute('data-index'))  
  if (id > 0) {
    // confirmDelete(indexId)
  } else {    
    reloadFormDelete(indexId)
  }  
  caculatorTotal()
}

const reloadFormDelete = (indexId) => {
  delete itemArr.value[indexId]
  form.ids = form.ids.filter((item, index) => index !== indexId)
  form.items = form.items.filter((item, index) => index !== indexId)
  form.quantities = form.quantities.filter((item, index) => index !== indexId)
  arrDetailItem['categories'] = arrDetailItem['categories'].filter((item, index) => index !== indexId)
  arrDetailItem['units'] = arrDetailItem['units'].filter((item, index) => index !== indexId)
  arrDetailItem['prices'] = arrDetailItem['prices'].filter((item, index) => index !== indexId)
  arrDetailItem['amounts'] = arrDetailItem['amounts'].filter((item, index) => index !== indexId)
  const newObj = {}
  let indexNew = 0
  Object.keys(itemArr.value).forEach((key, index) => {     
    newObj[indexNew] = itemArr.value[key]
    indexNew++
  })
  itemCount.value = Object.keys(newObj).length
  itemArr.value = newObj
}


const formCustomer = useForm({
  name: '',  
  phone_number: '',  
  address: '',   
})

const isSubmittingCustomer = ref(false)

const handleSubmitCustomer = () => {
  isSubmittingCustomer.value = true
  formCustomer.post(route('customer.save'), {
    onSuccess: () => {          
      isSubmittingCustomer.value = false   
      formCustomer.reset()     
      isShowModal.value = false     
    },     
  })
}

onMounted(() => {
  if (isUpdate.value) {
    addItem(true)
  } 
})

</script>