<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Group
 *
 * @property int $id
 * @property string|null $idkey
 * @property int|null $shop_id
 * @property string|null $module
 * @property string|null $locale
 * @property int|null $parent_id
 * @property string|null $title
 * @property string|null $slug
 * @property int|null $is_slug_override
 * @property int|null $duplicate
 * @property string|null $description
 * @property string|null $content
 * @property string|null $image
 * @property string|null $image_extension
 * @property string|null $image_banner
 * @property string|null $image_icon
 * @property string|null $url
 * @property int|null $url_type
 * @property int|null $author_id
 * @property int|null $target
 * @property int|null $price
 * @property string|null $params
 * @property int|null $totalitems
 * @property int|null $totalviews
 * @property int|null $order
 * @property int|null $position
 * @property int|null $display_type
 * @property int|null $sticky
 * @property int|null $is_display
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property int|null $seo_robots
 * @property int|null $status
 * @property string|null $started_at
 * @property string|null $ended_at
 * @property string|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Group|null $group
 * @property-read \Illuminate\Database\Eloquent\Collection|Group[] $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Item[] $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Item[] $itemsChild
 * @property-read int|null $items_child_count
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereDisplayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereDuplicate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereIdkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereImageBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereImageExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereImageIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereIsDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereIsSlugOverride($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereSeoRobots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereSeoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereSticky($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereTotalitems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereTotalviews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUrlType($value)
 * @mixin \Eloquent
 */
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
