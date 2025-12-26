<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Fields that can be mass assigned using Category::create()
    protected $fillable = ['name'];

    /**
     * Relationship Method
     * One Category can have multiple Products
     * This links categories.id with products.category_id
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
