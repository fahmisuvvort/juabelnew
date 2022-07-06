<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class FrontController extends Controller
{
    public $shop;

    public function __construct()
    {
        $this->shop = Cache::rememberForever('shop', function() {
            return Store::first();
        });
    }

    public function homepage()
    {
        $title = $this->shop->name;
        if($this->shop->description) {
            $title = $title . ' | ' . $this->shop->description;
        }
        return View::vue([
            'title' => $title,
            'description' => $this->shop->description,
            'featured_image' => $this->shop->logo_path? url($this->shop->logo_path) : null,
            'data' => null
        ]);
    }
    
    public function products()
    {
        return View::vue([
            'title' => 'Produk Katalog | ' . $this->shop->name,
            'description' => $this->shop->description,
            'featured_image' => $this->shop->logo_path? url($this->shop->logo_path) : null,
            'data' => null
        ]);
    }
    public function productDetail($slug)
    {
        $product = Product::where('slug', $slug)->first();
        
        return View::vue([
            'title' => $product->title . ' | ' . $this->shop->name,
            'description' => $product->description ? $this->createTeaser($product->description) : $this->shop->description,
            'featured_image' => $product->assets[0]->src,
            'data' => null
        ]);

    }

    public function productCategory(Category $category)
    {
        return View::vue([
            'title' => $category->title . ' | ' . $this->shop->name,
            'description' => $category->description?? $this->shop->description,
            'featured_image' => url('/upload/images/' . $category->filename),
            'data' => null
        ]);

    }
    public function postIndex()
    {
        return View::vue([
            'title' => 'Artikel | ' . $this->shop->name,
            'description' => $this->shop->description,
            'featured_image' => $this->shop->logo_path? url($this->shop->logo_path) : null,
            'data' => null
        ]);
    }
    public function postDetail($slug)
    {
        $post = Post::where('slug', $slug)->first();
        
        return View::vue([
            'title' => $post->title . ' | ' . $this->shop->name,
            'description' => $this->createTeaser($post->body),
            'featured_image' => url('/upload/images/' . $post->image),
            'data' => null
        ]);
    }
    public function any()
    {
        return View::vue([
            'title' => $this->shop->name,
            'description' => $this->shop->description,
            'featured_image' => $this->shop->logo_path? url($this->shop->logo_path) : null,
            'data' => null
        ]);
    }
    public function clearCache()
    {   
        Cache::flush();
        return redirect('/');
    }
    protected function createTeaser($html)
    {
        $str = strip_tags($html);

        return substr($str, 0, 130) . '...'; 
    }
}
