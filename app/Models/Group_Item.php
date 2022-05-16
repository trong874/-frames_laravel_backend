<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Group_Item
 *
 * @property int $id
 * @property int|null $shop_id
 * @property int|null $group_id
 * @property int|null $item_id
 * @property int|null $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $ended_at
 * @method static \Illuminate\Database\Eloquent\Builder|Group_Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group_Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group_Item query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group_Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group_Item whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group_Item whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group_Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group_Item whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group_Item whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group_Item whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group_Item whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Group_Item extends Model
{
    use HasFactory;
    protected $table = 'groups_items';
    protected $fillable = ['order'];
}
