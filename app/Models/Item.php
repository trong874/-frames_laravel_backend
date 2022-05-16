<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Item
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
 * @property int|null $price_input
 * @property int|null $price_old
 * @property int|null $price
 * @property string|null $percent_sale
 * @property int|null $order
 * @property string|null $params
 * @property int|null $totalitems
 * @property int|null $totalviews
 * @property string|null $position
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
 * @property string|null $deleted_at
 * @property-read \App\Models\User|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Group[] $groups
 * @property-read int|null $groups_count
 * @method static \Illuminate\Database\Eloquent\Builder|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item query()
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereDisplayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereDuplicate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereIdkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereImageBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereImageExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereImageIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereIsDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereIsSlugOverride($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereModule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item wherePercentSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item wherePriceInput($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item wherePriceOld($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereSeoRobots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereSeoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereSticky($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereTarget($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereTotalitems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereTotalviews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Item whereUrlType($value)
 * @mixin \Eloquent
 */
class Item extends Model
{
    use HasFactory;

    protected $table = 'items';
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

    function groups(){
        return $this->belongsToMany(Group::class,'groups_items','item_id','group_id');
    }

    function author(){
        return $this->belongsTo(User::class,'author_id','id');
    }

}
