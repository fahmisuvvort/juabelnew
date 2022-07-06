<?php

namespace App\Repositories;

use stdClass;
use Exception;
use App\Models\Asset;
use App\Models\Promo;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\ProductResource;

class ProductRepository
{
    protected $limit = 6;
    
    public function show($slug)
    {
        Cache::flush();
        $product = Cache::remember($slug, now()->addMinute(), function() use ($slug) {

            return new ProductResource(Product::with(['assets', 'category', 'seller', 'varians.subvarian', 'productPromo' => function($query) {
                $query->whereHas('promoActive');
            }])
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->where('slug', $slug) 
                ->orWhere('id', $slug)
                ->first());
        });


        return $product;
    }

    public function getAll()
    {
            return Product::with(['assets', 'category.parent.parent', 'productPromo' => function($query) {
                $query->whereHas('promoActive');
            }])
            ->withAvg('reviews', 'rating')
            ->simplePaginate($this->limit);
        
    }

    public function getProductsFavorites($pids)
    {
        return Product::with(['assets', 'category.parent.parent', 'productPromo' => function($query) {
            $query->whereHas('promoActive');
        }])
            ->whereIn('id', $pids)
            ->withAvg('reviews', 'rating')
            ->get();

    }
  
    public function search($key)
    {

        return Product::with(['assets', 'category.parent.parent', 'productPromo' => function($query) {
            $query->whereHas('promoActive');
        }])
            ->where('title', 'like', '%'.$key.'%')
            ->withAvg('reviews', 'rating')
            ->get();

    }

    public function getProductsByCategory($id)
    {
        $category = Category::find($id);
        $ids =  Category::where('category_id', $id)->pluck('id');   
        $subs = Category::whereIn('category_id', $ids)->pluck('id')->toArray();

        $ids[] = (int) $id;

        return Product::with(['assets', 'category.parent.parent', 'productPromo' => function($query) {
            $query->whereHas('promoActive');
        }])
            ->whereIn('category_id', $ids)
            ->OrWhereIn('category_id', $subs)
            ->withAvg('reviews', 'rating')
            ->simplePaginate($this->limit);

        }

    public function getProductPromo()
    {
        return Promo::active()->with(['products' => function($query) {
            $query->with('assets');
            $query->with('productPromo', function($q) {
                $q->whereHas('promoActive');
            });
            $query->withAvg('reviews', 'rating');
        }])->get()->map(function($item) {

            $promo = new stdClass();
            $promo->id = $item->id;
            $promo->label = $item->label;
            $promo->start_date = $item->start_date;
            $promo->end_date = $item->end_date;

            $promo->products = $item->products->map(function($product) {

                return [
                    'id'      => $product->id,
                    'title'   => $product->title,
                    'slug'    => $product->slug,
                    'status'  =>  $product->status,
                    'rating'  =>  $product->reviews_avg_rating ? (float) number_format($product->reviews_avg_rating, 1) : 0,
                    'pricing' =>  $this->setPricing($product),
                    'assets'  =>  $product->assets,
                ];
            });

            return $promo;

        });
        
    }

    public function getInitialProducts($categories)
    {
        $datas = [];

        foreach($categories as $category) {
            $ids =  Category::where('category_id', $category->id)->pluck('id');   
            $subs = Category::whereIn('category_id', $ids)->pluck('id')->toArray();

            $categoryItem = new stdClass();
            $categoryItem->title = $category->title;
            $categoryItem->category_id = $category->id;
            $categoryItem->category_slug = $category->slug;
            $categoryItem->id = Str::random(16);
            $categoryItem->description = $category->description ?? '';
            $categoryItem->banner = $category->banner ? url('upload/images/' . $category->banner) : '';

            $categoryItem->items = Product::with(['assets', 'category.parent.parent', 'productPromo' => function($query) {
                $query->whereHas('promoActive');
            }])
                ->whereIn('category_id', $ids)
                ->OrWhereIn('category_id', $subs)
                ->with('varians.subvarian')
                ->withAvg('reviews', 'rating')
                ->get()
                ->map(function($product) use($category){

                    return [
                        'id'      => $product->id,
                        'title'   => $product->title,
                        'sku'   => $product->sku,
                        'slug'    => $product->slug,
                        'status'  =>  $product->status,
                        'rating'  =>  $product->reviews_avg_rating ? (float) number_format($product->reviews_avg_rating, 1) : 0,
                        'pricing' =>  $this->setPricing($product),
                        'category' => $category,
                        'assets'  =>  $product->assets,
                        'description' =>  $product->description,
                        // 'promo' => $product->promo
                    ];
                });

            $datas[] = $categoryItem;

        }

        // dd($datas);

        return $datas;
        
        // $data = Category::whereHas('products')
        //     ->with(['parent.parent', 'products' => function($query) {
        //         $query->with('assets');
        //         $query->with('productPromo', function($q) {
        //             $q->whereHas('promoActive');
        //         });
        //         $query->with('varians.subvarian');
        //         $query->withAvg('reviews', 'rating');
        //     }])
        //     ->orderBy('weight')
        //     ->get()
        //     ->map(function($cat) {

        //         $parent = $cat;

        //         if($cat->parent) {
        //             $parent = $cat->parent;

        //             if($cat->parent->parent) {
        //                 $parent = $cat->parent->parent;
        //             }
        //         }

        //         $categoryItem = new stdClass();
        //         $categoryItem->title = $parent->title;
        //         $categoryItem->category_id = $parent->id;
        //         $categoryItem->category_slug = $parent->slug;
        //         $categoryItem->id = Str::random(16);
        //         $categoryItem->description = $parent->description ?? '';
        //         $categoryItem->banner = $parent->banner ? url('upload/images/' . $parent->banner) : '';

        //         $categoryItem->items = $cat->products->map(function($product) use($parent) {

        //             $newCat = new stdClass();
        //             $newCat->id = $parent->id;
        //             $newCat->title = $parent->title;
        //             $newCat->slug = $parent->slug;

        //             $product->category = $newCat;

        //             return [
        //                 'id'      => $product->id,
        //                 'title'   => $product->title,
        //                 'sku'   => $product->sku,
        //                 'slug'    => $product->slug,
        //                 'status'  =>  $product->status,
        //                 'rating'  =>  $product->reviews_avg_rating ? (float) number_format($product->reviews_avg_rating, 1) : 0,
        //                 'pricing' =>  $this->setPricing($product),
        //                 'category' => $newCat,
        //                 'assets'  =>  $product->assets,
        //                 'description' =>  $product->description,
        //                 // 'promo' => $product->promo
        //             ];
        //         });

        //         return $categoryItem;
        //     });

        // return $data;

    }
  
    public function store($request)
    {
        $path = public_path('/upload/images');

        if(! File::exists($path)) {
            File::makeDirectory($path, 0755, true, true);
        }

        DB::beginTransaction();

        $seller = $request->user();
        
        try {
            $slug = Str::slug($request->title);
            $product = new Product();

            $product->title = $request->title;
            $product->slug = $slug;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->weight = $request->weight;
            $product->sku = 'PF-'. $request->sku;
            
            $product->category_id =  $request->category_id;

            $product->description = $request->description;

            if( $seller->shop) {

                $product->seller_id = $seller->shop->id;
            }

            $product->save();

            if($request->images && count($request->images) > 0) {
                foreach($request->images as $file) {
                    
                    $filename = Str::random(41).'.' . $file->extension();

                    $file->move($path, $filename);

                    $product->assets()->create([
                        'filename' => $filename
                    ]);
                }
            }

            $product->fresh();

            if($request->varians) {
                $datas = json_decode($request->varians, true);

                foreach($datas as $data) {

                    if($request->boolean('has_subvarian') === true) {

                        $varian =  $product->varians()->create([
                                'has_subvarian' => $data['has_subvarian'],
                                'label' => $data['label'],
                                'value' => $data['value'],
                            ]);
        
                            foreach($data['subvarian'] as $item) {
        
                                $varian->subvarian()->create($item);
                            }
        
                    } else {
                    
                        $product->varians()->create($data);
                    }

                } 

                
            }

            DB::commit();

            $this->clearCache();

            return $product->load('assets','varians.subvarian');


        } catch (Exception $e) {

            DB::rollBack();

            throw new Exception($e);
        }

            
    }

    public function update($request)
    {
        $product = Product::find($request->id);

        $path = public_path('/upload/images');

        if(! File::exists($path)) {
            File::makeDirectory($path, 0755, true, true);
        }

        DB::beginTransaction();

        $product->title = $request->title;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->weight = $request->weight;
        $product->description = $request->description;
        $product->category_id = $request->category_id;

        try {

            if($request->images) {
                foreach($request->images as $file) {

                    $filename = Str::random(42).'.' . $file->extension();
                    
                    if($file->move($path, $filename)){

                        $product->assets()->create([
                            'filename' => $filename
                        ]);
                    }

                }
            }
            if($request->del_images) {
                foreach($request->del_images as $filename) {
                    File::delete('upload/images/'. $filename);
                    Asset::where('filename', $filename)->delete();
                }
            }

            $product->save();

            $product->varians()->delete();

            if($request->varians) {
                $datas = json_decode($request->varians, true);

                foreach($datas as $data) {

                    if($request->boolean('has_subvarian') === true) {

                        $varian =  $product->varians()->create([
                                'has_subvarian' => $data['has_subvarian'],
                                'label' => $data['label'],
                                'value' => $data['value'],
                            ]);
        
                            foreach($data['subvarian'] as $item) {
        
                                $varian->subvarian()->create($item);
                            }
        
                    } else {
                    
                        $product->varians()->create($data);
                    }

                } 

                
            }

            $product->fresh();

            DB::commit();

            $this->clearCache();

            return $product;

        } catch (Exception $e) {

            DB::rollBack();
            
            throw new Exception($e);
        }

        
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        DB::beginTransaction();

        try {
            if($product->assets) {

                foreach($product->assets as $asset){
                    File::delete('upload/images/'. $asset->filename);
                }
                $product->assets()->delete();
            }
            $product->delete();

            DB::commit();

            $this->clearCache();

            return true;


        } catch (Exception $e) {

            DB::rollBack();

            throw new Exception($e);
        }
    }

    protected function setPricing($product)
    {
        $defaultPrice = $product->price;

            $pricing = [
                'default_price' => $defaultPrice,
                'current_price' => $defaultPrice,
                'discount_percent' => 0,
                'discount_amount' => 0,
                'is_discount' => false,
            ];


            $disc = null;
    
            if($product->productPromo) {
                $disc = $product->productPromo;
            } 

            if($disc) {

                $pricing['is_discount'] = true;

                $discountVal = 0;
                

                if($disc->discount_type == 'PERCENT') {
    
                    $discountVal = ($defaultPrice*$disc->discount_amount) / 100;

                    $pricing['current_price'] = $defaultPrice - (int) $discountVal;

                    $pricing['discount_percent'] = (int) $disc->discount_amount;
                    
                } else{
    
                    $discountVal = $disc->discount_amount;

                    $pricing['current_price'] = $defaultPrice - (int) $discountVal;

                    $pricing['discount_percent'] = number_format(((int)$disc->discount_amount / $defaultPrice)*100, 0);
    
                }

                $pricing['discount_amount'] = $discountVal;
            }
        
            return $pricing;
    }
    
    protected function clearCache()
    {
        Cache::forget('products');
        Cache::forget('initial_products');
        Cache::forget('product_promo');
    }

}