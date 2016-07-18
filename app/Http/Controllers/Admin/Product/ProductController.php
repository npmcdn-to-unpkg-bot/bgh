<?php

namespace App\Http\Controllers\Admin\Product;

use App\Helpers\Resize;
use App\Helpers\ResizeHelper;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\JsonResponse;

use App\Repository\ProductRepositoryInterface;
use Carbon\Carbon;

use App\Http\Requests\Admin\ProductRequest;


class ProductController extends Controller
{

    public function getIndex(Request $request)
    {

        $title = sprintf('List of %s products', ucfirst($request->get('type')));
        $type = $request->get('type');

        return view('admin.product.index', compact('title', 'type'));
    }

    public function getData(Request $request)
    {
        $products = Product::select([
            'products.*',
            DB::raw('users.fullname as fullname'),
        ])->leftJoin('users', 'users.id', '=', 'products.user_id')
            ->groupBy('products.id');

        switch ($request->get('type')) {
            case 'approved':
                $products->approved();
                break;
            case 'featured':
                $products->whereNotNull('products.featured_at');
                break;
            case 'approvalRequired':
                $products->whereNull('products.approved_at');
                break;
            default:
                $products->approved();
        }

        $datatables = app('datatables')->of($products);

        if ($request->get('type') == 'approvalRequired') {
            $datatables->addColumn('actions', function ($product) {
                return '<a href="#" class="product-approve btn btn-success" data-approve="' . $product->id . '"><i class="fa fa-check"></i> Approve </a>
                 <a href="' . route('admin.products.edit', [$product->id]) . '" class="btn btn-info" target="_blank"><i class="fa fa-edit"></i> Edit </a>
                <a href="#" class="product-disapprove btn btn-danger" data-disapprove="' . $product->id . '"><i class="fa fa-times"></i> Delete</a>';
            });
        } else {
            $datatables->addColumn('actions', function ($product) {
                return '<a href="' . route('admin.products.edit', [$product->id]) . '" class="btn btn-info" target="_blank"><i class="fa fa-edit"></i> Edit </a>
                <a href="' . route('product', [$product->id, $product->slug]) . '" class="btn btn-success" target="_blank"><i class="fa fa-search"></i> View</a>';
            });
        }

        return $datatables->addColumn('thumbnail', function ($product) {
            return '<img src="' . Resize::img($product->main_image, 'listingProduct') . '" style="width:80px"/>';
        })
            ->editColumn('created_at', '{!! $created_at->diffForHumans() !!}')
            ->editColumn('featured_at', function ($product) {
                if ($product->featured_at !== null) {
                    $product->featured_at->diffForHumans();
                }

                return 'Not Featured';
            })
            ->editColumn('updated_at', '{!! $updated_at->diffForHumans() !!}')
            ->editColumn('title', '{!! str_limit($title, 60) !!}')
            ->make(true);
    }





    // #################################
    // REB metodos que responden al routes en modo REST con verbs (PUT, PATCH, DELETE) para no usar el post en distitnas rutas y ser mas organico
    // #################################

    public function edit($id)
    {
        $product = Product::whereId($id)->with('user', 'info')->firstOrFail();

        $title = t('Edit');

        $categories = ProductCategory::items();
        foreach ($categories as $c) {
            if($product->hasCategory($c->id)){
                $c->checked = 'checked';
            }
        }

        return view('admin.product.edit', compact('product', 'title', 'categories'));
    }


    public function patch(ProductRequest $request)
    {
        $product = Product::whereId($request->route('id'))->firstOrFail();

        if ($request->get('categories')) {
            $categories = $request->get('categories');
            $product->categories()->sync($categories);
        }

        if ($request->get('tags')) {
            $tags = implode(',', $request->get('tags'));
        } else {
            $tags = null;
        }
        $product->tags = $tags;

        $slug = @str_slug($request->get('slug'));
        if (!$slug) {
            $slug = str_random(8);
        }
        $product->slug = $slug;

        $product->title = $request->get('title');
        $product->description = $request->get('description');
        $product->is_microsite = $request->get('is_microsite');

        if ($request->get('featured_at') && $product->featured_at == null) {
            $product->featured_at = Carbon::now();
        } elseif ($request->get('featured_at') == null && $product->featured_at) {
            $product->featured_at = null;
        }

        $product->save();


        if ($request->hasFile('cover_image')){
            if ($request->file('cover_image')->isValid()){
                $save = new ResizeHelper($request->file('cover_image'), 'uploads/products');
                list($fName, $fType) = $save->saveOriginal();
                $product->info->cover_image = $fName . "." . $fType;
                // $request->file('cover_image')->move($destinationPath, $fileName);
            }
        }

        $product->info->save();


        if ($request->ajax() || $request->wantsJson()) {
            // return response()->json(['dato' => 'valor', 'otrodato' => 'otrovalor']);
            return new JsonResponse('ajax todo ok', 200);
        }
        else{
            return redirect()->back()->with('flashSuccess', 'post todo ok');
        }

    }


    public function create()
    {

        $title = 'Creating new product';

        // $categories = ProductCategory::items();
        // return view('admin.product.create', compact('title', 'categories'));
        return view('admin.product.create', compact('title'));
    }

    public function put(Request $request)
    {
        $item = new Product();
        $item->title = $request->get('title');

        $slug = @str_slug($request->get('title'));
        if (!$slug) {
            $slug = str_random(7);
        }
        $item->slug = $slug;

        $item->user_id = auth()->user()->id;

        $item->save();

        $info_data = [
            'cover_image' => '',
        ];

        $info = new ProductInfo($info_data);
        $item->info()->create($info_data);


        return redirect()->route('admin.products.edit', ['id' => $item->id])->with('flashSuccess', 'Product is now crated');
    }


    public function delete($id)
    {

        // if (Request::ajax()) {
        // if (Request::isMethod('delete')){

        $product = Product::findOrFail($id);

        $delete = new ResizeHelper( $product->main_image, $product->type);
        $delete->delete();
        // $product->favorites()->delete();
        // $comments = $product->comments()->get();
        // foreach ($comments as $comment) {
        //     $comment->votes()->delete();
        //     foreach ($comment->reply()->get() as $reply) {
        //         $reply->votes()->delete();
        //         $reply->delete();
        //     }
        //     $comment->delete();
        // }
        $product->categories()->detach();
        $product->info()->delete();
        $product->delete();

        return redirect()->route('admin.products')->with('flashSuccess', 'deleted');

    }


    public function approve(Request $request)
    {
        $product = Product::whereId($request->get('id'))->first();
        if (!$product) {
            return 'Error';
        }
        if ($request->get('approve') == 1) {
            $product->approved_at = Carbon::now();
            $product->save();

            return 'Approved';
        }
        if ($request->get('approve') == 0) {
            $delete = new ResizeHelper($product->main_image, $product->type);
            $delete->delete();
            $product->delete();

            return 'Deleted';
        }
    }


    public function clearCache($id)
    {

        $product = Product::whereId($id);

        if(!isset($product->main_image)){
           $product->main_image = 'default.png';
        }

        $cache = new ResizeHelper($product->main_image);
        $cache->clearCache();
        return 'Cache is cleared, reload the page';


    }



    public function getBulkUpload()
    {
        $title = sprintf('Bulkupload');

        return view('admin.product.bulkupload', compact('title'));
    }

    public function postBulkUpload(Request $request)
    {
        $file = $request->file('files')[0];
        $info = $request->get('photo');

        $save = new ResizeHelper($file, 'uploads/products');
        list($productName, $mimetype) = $save->saveOriginal();

        $tags = null;
        if ($request->get('tags')) {
            $tags = implode(',', $request->get('tags'));
        }

        $description = null;

        $title = str_replace(['.jpg', '.jpeg', '.png', '.gif'], '', $file->getClientOriginalName());

        $slug = @str_slug($title);
        if (!$slug) {
            $slug = str_random(9);
        }

        sleep(1);
        $approved_at = Carbon::now();
        $product = new Product();
        $product->user_id = $request->user()->id;
        $product->main_image = $productName . "." . $mimetype;
        // $product->name = $productName;
        $product->title = $title;
        $product->slug = $slug;
        // $product->category_id = $request->get('category_id');
        // $product->type = $mimetype;
        $product->tags = $tags;
        $product->description = $description;
        $product->is_microsite = $request->get('is_microsite');
        $product->approved_at = $approved_at;
        $product->save();


        $info_data = [
            'cover_image' => '',
        ];

        $info = new ProductInfo($info_data);
        $product->info()->create($info_data);


        return [
            'files' => [
                0 => ['success'      => 'Uploaded',
                      'successSlug'  => route('product', ['id' => $product->id, 'slug' => $product->slug]),
                      'successTitle' => ucfirst($product->title),
                      'thumbnail'    => Resize::img($product->main_image, 'listingProduct')
                ]
            ]
        ];
    }





}
