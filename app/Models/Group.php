<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';
    protected $fillable = [
        'id',
        'idkey',
        'shop_id',
        'module',
        'locale',
        'parent_id',
        'title',
        'slug',
        'is_slug_override',
        'duplicate',
        'description',
        'content',
        'image',
        'image_extension',
        'image_banner',
        'image_icon',
        'url',
        'url_type',
        'author_id',
        'target',
        'price_input',
        'price_old',
        'price',
        'percent_sale',
        'order',
        'params',
        'totalitems',
        'totalviews',
        'position',
        'display_type',
        'sticky',
        'is_display',
        'seo_title',
        'seo_description',
        'seo_robots',
        'status',
        'started_at',
        'ended_at',
        'published_at'
    ];

    function items(){
        return $this->belongsToMany(Item::class,'groups_items','group_id','item_id')->orderBy('items.order','DESC');
    }
    function itemsChild(){
        return $this->belongsToMany(Item::class,'groups_items','group_id','item_id')->orderBy('groups_items.order','ASC');
    }
    function groups(){
        return $this->hasMany(Group::class,'parent_id','id')->orderBy('order','ASC');
    }

    function group(){
        return $this->belongsTo(Group::class,'parent_id','id');
    }
}
