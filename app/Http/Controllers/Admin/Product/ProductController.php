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

    public function getBulkUpload()
    {
        $title = sprintf('Bulkupload');

        return view('admin.product.bulkupload', compact('title'));
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


    // REB

    public function getNew()
    {
        $title = 'Creating new product';

        return view('admin.product.new', compact('title'));
    }

    public function postNew(Request $request)
    {
        $item = new Product();
        $item->title = $request->get('title');
        $item->description = $request->get('description');
        $slug = @str_slug($request->get('title'));
        if (!$slug) {
            $slug = str_random(7);
        }
        $item->user_id = auth()->user()->id;
        $item->slug = $slug;
        $item->save();

        return redirect()->route('admin.products')->with('flashSuccess', 'Product is now crated');
    }






    public function getEdit($id)
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

    public function postEdit(ProductRequest $request)
    {
        $product = Product::whereId($request->route('id'))->firstOrFail();

        if ($request->get('delete')) {
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
            $product->info()->delete();
            $product->delete();

            return redirect()->route('admin.products')->with('flashSuccess', 'Product is now deleted permanently');
        }

        // if (Category::whereId($request->get('category'))->count() != 1) {
        //     return redirect()->back()->with('flashError', t('Invalid category'));
        // }


        if ($request->get('tags')) {
            $categories = $request->get('categories');
            $product->categories()->sync($categories);
            // $product->categories()->sync([3, 6, 12]);
            // $product->categories()->attach(15);
            // $product->categories()->detach([1, 2, 3]);
            // $user->roles()->attach(1, ['expires' => $expires]);
        }


        if ($request->get('tags')) {
            $tags = implode(',', $request->get('tags'));
        } else {
            $tags = null;
        }
        $slug = @str_slug($request->get('slug'));
        if (!$slug) {
            $slug = str_random(8);
        }
        $product->title = $request->get('title');
        $product->slug = $slug;
        $product->description = $request->get('description');
        // $product->category_id = $request->get('category');
        $product->tags = $tags;

        if ($request->get('featured_at') && $product->featured_at == null) {
            $product->featured_at = Carbon::now();
        } elseif ($request->get('featured_at') == null && $product->featured_at) {
            $product->featured_at = null;
        }

        $product->save();




        // $this->validate($request, [
        //     'cover_image2' => 'required'
        // ]);


        if ($request->hasFile('cover_image'))
        {

            if ($request->file('cover_image')->isValid())
            {

                $save = new ResizeHelper($request->file('cover_image'), 'uploads/products');
                list($fName, $fType) = $save->saveOriginal();

                $product->info->cover_image = $fName . "." . $fType;

                $product->info->save();

                // $request->file('cover_image')->move($destinationPath, $fileName);

            }

        }





        return redirect()->back()->with('flashSuccess', 'Updated');
    }





    public function getCreate()
    {

        $title = t('Create');

        $categories = ProductCategory::items();

        return view('admin.product.create', compact('title', 'categories'));
    }


    public function postCreate(Request $request)
    {
        $product = new Product();


        // if (Category::whereId($request->get('category'))->count() != 1) {
        //     return redirect()->back()->with('flashError', t('Invalid category'));
        // }


        $categories = $request->get('categories');
        $product->categories()->sync($categories);


        if ($request->get('tags')) {
            $tags = implode(',', $request->get('tags'));
        } else {
            $tags = null;
        }
        $slug = @str_slug($request->get('slug'));
        if (!$slug) {
            $slug = str_random(8);
        }
        $product->title = $request->get('title');
        $product->slug = $slug;
        $product->description = $request->get('description');
        // $product->category_id = $request->get('category');
        $product->tags = $tags;

        if ($request->get('featured_at') && $product->featured_at == null) {
            $product->featured_at = Carbon::now();
        } elseif ($request->get('featured_at') == null && $product->featured_at) {
            $product->featured_at = null;
        }

        $product->save();

        return redirect()->back()->with('flashSuccess', 'Created');
    }


    public function clearCache(Request $request)
    {
        $product = Product::whereId($request->get('id'))->firstOrFail();
        $cache = new ResizeHelper($product->main_image, $product->type);
        $cache->clearCache();

        return 'Cache is cleared, reload the page';
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
