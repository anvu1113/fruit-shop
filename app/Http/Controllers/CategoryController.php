<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FruitCategory;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(Request $request)
    {      
        $limit = 15;
        $filters = $request->only([
            'name', 
        ]);
       
        $categories = FruitCategory::filter($filters)->paginate($limit)->withQueryString();

        return inertia(
            'Category/Index',
            [              
                'filters' => $filters,
                'categories' => $categories,
                'limit' => $limit,
            ]
        );
    }


    public function createEdit(FruitCategory $category)
    {
    

        return inertia(
            'Category/CreateUpdate',
            [             
                'category' => $category,                           
            ]
        );
    }

    public function saveUpdate(Request $request, FruitCategory $category) 
    {
        
        $message = 'Category was created!';
        $successError = 'success';    
        $params =  [
            'validate' =>  [
                'name' => 'required|max:100',  
            ],
            'save_update' => [                 
                'name'
            ],          
        ];

        $request->validate($params['validate']);    
        $paramSave = $request->only($params['save_update']); 
      
        try { 
            DB::beginTransaction(); 
            if ($category->exists) { 
                $category->update($paramSave);              
                $message = 'Category was updated!';               
            } else {  
                $category = FruitCategory::create($paramSave); 
            } 
            DB::commit();          
        } catch (\Exception $e) {
            DB::rollBack();
            $successError = 'error';
            $message = 'An error occurred when save category. Please try again.';    
        }

        return redirect()->route('category.index')->with($successError,  $message);
    }


       /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FruitCategory $category)
    {
        $successError = 'success';
        $message = 'Category was deleted!';   
        try { 
            $category->deleteOrFail();
        } catch (\Exception $e) {           
            $successError = 'error';
            $message = 'An error occurred when save category. Please try again.';    
        }
        return redirect()->back()->with($successError,  $message);
    }
}
