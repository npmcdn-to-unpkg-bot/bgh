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



use App\Repository\ProductCategoryRepositoryInterface;
use App\Repository\ProductRepositoryInterface;

class ProductCategoryController extends Controller
{

    public function __construct(ProductCategoryRepositoryInterface $category, ProductRepositoryInterface $products)
    {
        $this->products = $products;
        $this->category = $category;
    }


    public function index()
    {
        $title = t('Categories');

        $categories = ProductCategory::orderBy('lft', 'asc')->get();

        foreach ($categories as &$category) {
            $category->link = $this->category->getLink($category->slug);
        }

        return view('admin.productcategory.index', compact('title','categories'));
    }


    public function createCategory(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'slug' => 'required',
        ]);

        $category = new ProductCategory();

        $category->name = $request->get('name');

        $slug = @str_slug($request->get('slug'));
        if (!$slug) {
            $slug = str_random(9);
        }
        $category->slug = $slug;

        $category->save();

        Artisan::call('cache:clear');
        return redirect()->back()->with('flashSuccess', 'Category Is Added');
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

    // // se usa en el modal
    // public function updateCategory(Request $request)
    // {
    //     $this->validate($request, [
    //         'id'   => ['required'],
    //         'slug' => ['required', 'alpha_dash'],
    //         'name' => ['required']
    //     ]);
    //     $id = $request->get('id');
    //     $category = ProductCategory::where('id', '=', $id)->with('images')->first();

    //     $category->slug = $request->get('slug');
    //     $category->name = $request->get('name');
    //     $category->save();
    //     Artisan::call('cache:clear');
    //     return redirect()->back()->with('flashSuccess', 'Product Category is now updated');
    // }


    public function delete($id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->delete();
        // Artisan::call('cache:clear');
        return redirect()->back()->with('flashSuccess', 'Product Category is now deleted');
    }


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
       $title = t('Edit');

        $category = ProductCategory::where('id', '=', $id)->first();


        // para popular el select2 de productos
        $ix = 0;

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
        $allproducts = Product::all();
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
            'slug' => ['required', 'alpha_dash'],
            'name' => ['required']
        ]);

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

    public function items($id)
    {

        $category = ProductCategory::where('id', '=', $id)->first();

        $title = $category->name . ': ' . t('Items');

        $items = $category->products;

        return view('admin.productcategory.items', compact('title','category','items'));
    }

    public function itemsupdate($id, Request $request)
    {

        $category = ProductCategory::findOrFail($id);

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

        echo 'Itemes updated';
    }

}
