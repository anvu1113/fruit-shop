<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\FruitItem;
use Illuminate\Http\Request;
use App\Models\FruitCategory;
use App\Models\InvoiceDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {      
        $limit = 15;
        $filters = $request->only([
            'customer_id', 
        ]);   

        $customers = Customer::select(['id', 'name'])->get()->groupBy('id');

        $invoices = Invoice::with('invoiceDetail')->filter($filters)->paginate($limit)->withQueryString();
       
        return inertia(
            'Invoice/Index',
            [              
                'filters' => $filters,
                'invoices' => $invoices,
                'customers' => $customers,
                'limit' => $limit,
            ]
        );
    }

    public function createEdit(Invoice $invoice)
    {
    
        $categories = FruitCategory::select(['id', 'name'])->get()->groupBy('id');
        $customers = Customer::select(['id', 'name'])->get();
        $items = FruitItem::select(['id', 'name', 'category_id', 'price', 'unit'])->get();
        $invoiceDetail = [
            'items' => [],
            'quantities' => [],
            'amounts' => [],
            'units' => [],
            'categories' => [],
            'prices' => [],
        ];
        $totalPrice = 0;
        if ($invoice->exists) {
            $itemById = $items->groupBy('id');
            $invoice->load(['invoiceDetail']);
            if ($invoice->invoiceDetail) {               
                foreach ($invoice->invoiceDetail as $key => $value) {
                    $item_id = $value->item_id;
                    $invoiceDetail['ids'][] = $value->id;
                    $invoiceDetail['items'][] = $item_id;
                    $invoiceDetail['quantities'][] = $value->quantity;
                    $invoiceDetail['amounts'][] = (float)$value->total_price;
                    $invoiceDetail['units'][] = isset($itemById[$item_id]) ? $itemById[$item_id][0]->unit : '';
                    if (isset($itemById[$item_id])) {
                        $category_id = $itemById[$item_id][0]->category_id;                      
                        $invoiceDetail['categories'][] = isset($categories[$category_id])  ? $categories[$category_id][0]->name : '';
                        $invoiceDetail['prices'][] = (float)$itemById[$item_id][0]->price;
                    } else {
                        $invoiceDetail['categories'][] = '';
                        $invoiceDetail['prices'][] = 0;
                    } 
                    $totalPrice += (float)$value->total_price;                   
                }            
            }                
        }

        return inertia(
            'Invoice/CreateUpdate',
            [             
                'invoice' => $invoice,                           
                'customers' => $customers,                           
                'categories' => $categories,                           
                'items' => $items,                           
                'invoiceDetail' => $invoiceDetail,                           
                'totalPrice' => $totalPrice,                           
            ]
        );
    }

    public function saveUpdate(Request $request, Invoice $invoice) 
    {
        
        $message = 'Invoice was created!';    
        $successError = 'success';    
        $params =  [
            'validate' =>  [
                'customer_id' => 'required',  
                'items' => 'required|array',                
                'items.*' => 'required|distinct',  
                'quantities' => 'required|array',  
                'quantities.*' => 'required|numeric|max:9999999999|gt:0', 
            ],             
            'validate_message_custom' => [ 
                'items.required'  => 'Please select a item to save.',                
                'items.*.required'  => 'The item field is required.',   
                'items.*.distinct'  => 'The step item must not be the same.',                       
                'quantities.*.required'  => 'The quantity field is required.', 
                'quantities.*.gt'  => 'The quantity must be greater than 0.', 
                'quantities.*.numeric'  => 'The quantity must be a number.', 
                'quantities.*.max'  => 'The quantity must not be greater than 9999999999.', 
                  
            ],
            'save_update' => [                 
                'customer_id', 'items', 'quantities', 'amounts', 'ids'
            ],         
        ];     
        $request->validate($params['validate'], $params['validate_message_custom']);    
        $paramSave = $request->only($params['save_update']);        
        $isUpdate = false;        
        try { 
            DB::beginTransaction(); 
            if ($invoice->exists) { 
                $invoice->update($paramSave);              
                $message = 'Invoice was updated!'; 
                $isUpdate = true; 
            } else {  
                $invoice = Invoice::create($paramSave); 
            } 

            if ($invoice->id > 0) {
                if (isset($paramSave['items']) && !empty($paramSave['items'])) {
                    $items = $paramSave['items'];  
                    if ($isUpdate) {  
                        InvoiceDetail::where('invoice_id', '=',  $invoice->id)->whereNotIn('id', $paramSave['ids'])->delete(); 
                    }                 
                    try { 
                        foreach ($items as $key => $item) {
                            $idDetail = isset($paramSave['ids'][$key]) ? $paramSave['ids'][$key] : 0;
                            $paramsItem = [
                                'invoice_id' => $invoice->id,    
                                'item_id' => $item,    
                                'quantity' => isset($paramSave['quantities'][$key]) ? $paramSave['quantities'][$key] : 0,    
                                'total_price' => isset($paramSave['amounts'][$key]) ? (float)$paramSave['amounts'][$key] : 0,   
                            ];
                            if ($idDetail >  0) {
                                $invoice->invoiceDetail()->where('id', '=', $idDetail)->update($paramsItem);
                            } else {
                                $invoice->invoiceDetail()->create($paramsItem);   
                            }                                                     
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $message = 'An error occurred when save item detail. Please try again.';  
                        $successError = 'error';  
                    }
                }
            }
            DB::commit();          
        } catch (\Exception $e) {
            DB::rollBack();
            $successError = 'error';
            $message = 'An error occurred when save invoice. Please try again.';    
        }
        return redirect()->route('invoice.index')->with($successError,  $message);
    }

    public function saveCustomer(Request $request) 
    {
        
        $message = 'Customer was created!';
        $successError = 'success';    
        $params =  [
            'validate' =>  [
                'name' => 'required|max:100',  
                'phone_number' => 'required|unique:customers,phone_number',  
                'address' => 'required|max:100',
            ],
            'save_update' => [                 
                'name', 'phone_number', 'address'
            ],          
        ];

        $request->validate($params['validate']);    
        $paramSave = $request->only($params['save_update']); 
      
        try { 
            DB::beginTransaction(); 
            Customer::create($paramSave); 
            DB::commit();          
        } catch (\Exception $e) {
            DB::rollBack();
            $successError = 'error';
            $message = 'An error occurred when save customer. Please try again.';    
        }

        return redirect()->back()->with($successError,  $message);
    }


       /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Invoice $invoice)
    {
        $successError = 'success';
        $message = 'Invoice was deleted!';   
        try { 
            $invoice->deleteOrFail();
            $invoice->invoiceDetail()->delete();
        } catch (\Exception $e) {           
            $successError = 'error';
            $message = 'An error occurred when delete invoice. Please try again.';    
        }
        return redirect()->back()->with($successError,  $message);
    }


    public function reviewPdf(Request $request, Invoice $invoice)   
    {  
        $customer_id = $invoice->customer_id > 0 ? $invoice->customer_id : 0;
        $detailCustomer = Customer::find($customer_id);
        $categories = FruitCategory::select(['id', 'name'])->get()->groupBy('id'); 
        $items = FruitItem::select(['id', 'name', 'category_id', 'price', 'unit'])->get();
        $invoice->load(['invoiceDetail']);
        $invoiceDetails = [];     
        $total_invoice = 0;    
        if ($invoice->invoiceDetail) { 
            $itemById = $items->groupBy('id');

            foreach ($invoice->invoiceDetail as $key => $value) {
                $item_id = $value->item_id;
                $category_id = isset($itemById[$item_id]) ? $itemById[$item_id][0]->category_id : 0;
                $invoiceDetails[$key]['category'] = isset($categories[$category_id]) ? $categories[$category_id][0]->name : '';
                $invoiceDetails[$key]['name'] = isset($itemById[$item_id]) ? $itemById[$item_id][0]->name : '';
                $invoiceDetails[$key]['unit'] = isset($itemById[$item_id]) ? $itemById[$item_id][0]->unit : '';
                $invoiceDetails[$key]['price'] = isset($itemById[$item_id]) ? (float)$itemById[$item_id][0]->price : 0;
                $invoiceDetails[$key]['quantity'] = (float)$value->quantity;
                $invoiceDetails[$key]['amount'] = (float)$value->total_price;
                $total_invoice += (float)$value->total_price;
            }
        }
        

        $data = [
            'address' => '3rd floor, 62 Lam Van Street, <br> Tan Kieng Ward, District 7, Ho Chi Minh City, Vietnam',
            'website' => 'https://v-fruit.com.vn/',
            'email' => 'v-fruit@gmail.com.vn',
            'phone' => '0123456789',
            'title' => 'INVOICE',
            'date_text' => 'Date',
            'date' => '01-01-2024',
            'customer_name' => $detailCustomer->name,
            'customer_phone' => $detailCustomer->phone_number,
            'customer_address' => $detailCustomer->address,
            'invoice_details' => $invoiceDetails,
            'total_invoice' => $total_invoice,
        ]; 
        $pdf = Pdf::loadView('pdf.invoice', $data);
        return $pdf->stream();
    }
}
