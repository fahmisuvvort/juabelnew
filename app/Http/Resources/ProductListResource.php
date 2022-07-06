<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        $category = $this->category;
        
        if($this->category->parent) {
            $category = $this->category->parent;
        }
        if($this->category->parent->parent) {
            $category = $this->category->parent->parent;
        }
        return [
            'id'      => $this->id,
            'title'   => $this->title,
            'slug'    => $this->slug,
            'sku'    => $this->sku,
            'description' =>  $this->description,
            'status'  =>  $this->status,
            'rating'  =>  $this->reviews_avg_rating ? (float) number_format($this->reviews_avg_rating, 1) : 0,
            'pricing' =>  $this->setPricing($this),
            'category' => $category,
            'assets'  =>  $this->assets,
            'category_id' => $this->category_id,
            'category_type' => $this->category_type,
          ];
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

                $pricing['current_price'] = $defaultPrice - $discountVal;
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
}
