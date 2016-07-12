<?php

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */

namespace App\Http\Controllers\Admin\ProductCategory;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Helpers\Resize;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $title = 'Product Categories';

        return view('admin.productcategory.index', compact('title'));
    }


    public function createCategory(Request $request)
    {
        $this->validate($request, [
            'addnew' => 'required',
        ]);
        $category = new ProductCategory();
        $category->name = ucfirst($request->get('addnew'));
        $slug = @str_slug($request->get('addnew'));
        if (!$slug) {
            $slug = str_random(9);
        }
        $category->slug = $slug;
        $category->save();
        Artisan::call('cache:clear');
        return redirect()->back()->with('flashSuccess', 'New Product Category Is Added');
    }

    public function reorderCategory(Request $request)
    {
        $tree = $request->get('tree');
        foreach ($tree as $k => $v) {
            if ($v['depth'] == -1) {
                continue;
            }
            if ($v['parent_id'] == 'root') {
                $v['parent_id'] = 0;
            }

            $category = ProductCategory::whereId($v['item_id'])->first();
            $category->parent_id = $v['parent_id'];
            $category->depth = $v['depth'];
            $category->lft = $v['left'] - 1;
            $category->rgt = $v['right'] - 1;
            $category->save();
        }
        Artisan::call('cache:clear');
    }

    // se usa en el modal
    public function updateCategory(Request $request)
    {
        $this->validate($request, [
            'id'   => ['required'],
            'slug' => ['required', 'alpha_dash'],
            'name' => ['required']
        ]);
        $id = $request->get('id');
        $category = ProductCategory::where('id', '=', $id)->with('images')->first();

        $delete = $request->get('delete');
        if ($delete) {
            // if ($request->get('shiftCategory')) {
            //     foreach ($category->images as $image) {
            //         $image->category_id = $request->get('shiftCategory');
            //         $image->save();
            //     }
            // }
            $category->delete();

            return redirect()->back()->with('flashSuccess', 'Product Category is now deleted');
        }

        $category->slug = $request->get('slug');
        $category->name = $request->get('name');
        $category->save();
        Artisan::call('cache:clear');
        return redirect()->back()->with('flashSuccess', 'Product Category is now updated');
    }


    // REB


    public function productlist()
    {

        $products = Product::where('id', '>', 0)->get();

        $res =  [];
        $ix=0;
        foreach ($products as $p) {
            $res[$ix]['id'] = $p->id;
            $res[$ix]['text'] = $p->title;
            $res[$ix]['slug'] = $p->slug;
            $res[$ix]['image'] = Resize::img($p->main_image, 'sidebarProduct');
            $ix++;
        }

        return json_encode($res);
    }

    public function edit($id)
    {
       $title = 'Edit Product Categories';

        $category = ProductCategory::where('id', '=', $id)->first();

        $ix = 0;

        // para popular el select2 de productos

        // primero incluyo los que ya tiene incluidos el many to many con su respetivo orden
        $products = [];
        foreach ($category->products as $p) {
            $products[$ix]['id'] = $p->id;
            $products[$ix]['text'] = $p->title;
            $products[$ix]['value'] = true;
            $ix++;
        }

        $selectedproducts = $products;

        // luego incluyo el resto de los productos filtrando los que ya inclui como seleccionados
        $allproducts = Product::where('id', '>', 0)->get();
        foreach ($allproducts as $p) {

            if(!$category->products->contains($p->id)){
                $products[$ix]['id'] = $p->id;
                $products[$ix]['text'] = $p->title;
                $products[$ix]['value'] = false;
            }

            $ix++;
        }



        return view('admin.productcategory.edit', compact('title','category','products','selectedproducts'));

    }

    public function update($id, Request $request)
    {
        $this->validate($request, [
            // 'id'   => ['required'],
            'slug' => ['required', 'alpha_dash'],
            'name' => ['required']
        ]);

        // $id = $request->get('id');
        $category = ProductCategory::where('id', '=', $id)->with('images')->first();
        $delete = $request->get('delete');
        if ($delete) {
            // if ($request->get('shiftCategory')) {
            //     foreach ($category->images as $image) {
            //         $image->category_id = $request->get('shiftCategory');
            //         $image->save();
            //     }
            // }
            $category->delete();

            return redirect()->back()->with('flashSuccess', 'Product Category is now deleted');
        }

        // $products = $request->get('products');

        $order = (array) $request->get('products');
        $pivotData = array_fill(0, count($order), ['order' => 0]);
        $syncData  = array_combine($order, $pivotData);
        $ix = 0;
        foreach ($syncData as &$sd) {
            $ix++;
            $sd['order'] = $ix;
        }
        $category->products()->sync($syncData); // print_r($syncData);

        $category->slug = $request->get('slug');
        $category->name = $request->get('name');
        $category->save();
        Artisan::call('cache:clear');

        return redirect()->back()->with('flashSuccess', 'Product Category is now updated');
    }

    public function order($id)
    {

        $category = ProductCategory::where('id', '=', $id)->first();

        $title = 'Products of ' . $category->name;

        $items = $category->products;

        return view('admin.productcategory.order', compact('title','category','items'));
    }

    public function reorder($id, Request $request)
    {

        $category = ProductCategory::where('id', '=', $id)->with('images')->first();

        // levanto el array de ids por orden de esa categoria y los pongo como parametro extra en el many to many
        $order = (array) $request->get('order');
        $pivotData = array_fill(0, count($order), ['order' => 0]);
        $syncData  = array_combine($order, $pivotData);
        $ix = 0;
        foreach ($syncData as &$sd) {
            $ix++;
            $sd['order'] = $ix;
        }
        $category->products()->sync($syncData); // print_r($syncData);

        $category->save();
        Artisan::call('cache:clear');

        echo 'Itemes ordered';
    }

}
