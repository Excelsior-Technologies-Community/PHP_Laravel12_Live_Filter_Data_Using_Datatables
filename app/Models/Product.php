<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Fields that are allowed for mass assignment (create/update)
    protected $fillable = ['name', 'description', 'price', 'category_id'];

    /**
     * Relationship Method
     * Each Product belongs to one Category
     * This links products.category_id with categories.id
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
