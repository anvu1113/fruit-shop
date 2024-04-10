<?php

namespace App\Http\Controllers;

use App\Models\FruitItem;
use Illuminate\Http\Request;
use App\Models\FruitCategory;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index(Request $request)
    {      
        $limit = 15;
        $filters = $request->only([
            'name', 
        ]);
       
        $items = FruitItem::with('category')->filter($filters)->paginate($limit)->withQueryString();


        return inertia(
            'Item/Index',
            [              
                'filters' => $filters,
                'items' => $items,
                'limit' => $limit,
            ]
        );
    }


    public function createEdit(FruitItem $item)
    {
    

        $categories = FruitCategory::select(['id', 'name'])->get();

        // if ($item->exists) {
        //     $item->load(['category']);
        // }

        return inertia(
            'Item/CreateUpdate',
            [             
                'item' => $item,                           
                'categories' => $categories,                           
            ]
        );
    }

    public function saveUpdate(Request $request, FruitItem $item) 
    {
        
        $message = 'Item was created!';
        $successError = 'success';    
        $params =  [
            'validate' =>  [
                'name' => 'required|max:100',  
                'category_id' => 'required',  
                'price' => 'required|numeric|max:9999999999',  
                'unit' => 'required',  
            ],
            'save_update' => [                 
                'name', 'category_id', 'price', 'unit'
            ],          
        ];

        $request->validate($params['validate']);    
        $paramSave = $request->only($params['save_update']); 
      
        try { 
            DB::beginTransaction(); 
            if ($item->exists) { 
                $item->update($paramSave);              
                $message = 'Item was updated!';               
            } else {  
                $item = FruitItem::create($paramSave); 
            } 
            DB::commit();          
        } catch (\Exception $e) {
            DB::rollBack();
            $successError = 'error';
            $message = 'An error occurred when save item. Please try again.';    
        }

        return redirect()->route('item.index')->with($successError,  $message);
    }


       /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FruitItem $item)
    {
        $successError = 'success';
        $message = 'Item was deleted!';   
        try { 
            $item->deleteOrFail();
        } catch (\Exception $e) {           
            $successError = 'error';
            $message = 'An error occurred when save item. Please try again.';    
        }
        return redirect()->back()->with($successError,  $message);
    }
}
