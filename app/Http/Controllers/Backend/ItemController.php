<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Group_Item;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use stdClass;
use Yajra\DataTables\DataTables;

class ItemController extends Controller
{
    protected $module;
    protected $page_breadcrumbs;

    public function __construct(Request $request)
    {
        $this->module = $request->segment(2);
        if( $this->module!="" && !$request->ajax() && $this->module!="duplicate-item"){
            $this->page_breadcrumbs[] = [
                'page' => route($this->module.'.index'),
                'title' => __('Quản lý bài viết')
            ];
        }
    }

    public function index()
    {
        $module = $this->module;
        $categories = Group::query()
            ->where('module',$module.'-category')
            ->where('status',1)
            ->get();
        return view('backend.items.index', [
            'module'=>$module,
            'categories'=>$categories,
            'page_breadcrumbs'=>$this->page_breadcrumbs,
        ]);
    }

    public function create()
    {
        $this->page_breadcrumbs[] = [
            'page' => '#',
            'title' => __("Thêm mới")
        ];
        $categories = Group::where('module', $this->module . '-category')->get(['id', 'title', 'parent_id']);
        return view('backend.items.form_data', [
            'module' => $this->module,
            'categories' => $categories,
            'page_breadcrumbs'=>$this->page_breadcrumbs,
            'page_title' => 'Tạo mới ' . $this->module
        ]);
    }


    public function store(Request $request)
    {
        $data_item = $request->all();
        $title_slug = $this->checkMatchSlugItem($data_item['slug'], $data_item['title']);
        $data_item['slug'] = $title_slug['slug'];
        $data_item['title'] = $title_slug['title'];
        $item = Item::create($data_item);
        if ($request->group_id) {
            foreach ($request->group_id as $group_id) {
                $item->groups()->attach($group_id);
            }
        }
        if (isset($data_item['attribute'])) {
            $this->setAttributeItem($data_item['attribute'],$item->id);
        }
        Session::put('message', 'Tạo mới thành công');
        return back();
    }

    public function checkMatchSlugItem($slug_item, $title_item)
    {
        $count = 1;
        $slug = $slug_item;
        $title = $title_item;
        while (true) {
            $check = Item::where('slug', $slug)->first();
            if ($check) {
                $slug = $slug_item . "-$count";
                $title = $title_item . "($count)";
                $count++;
            } else {
                break;
            }
        }
        return ['slug' => $slug, 'title' => $title];
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {

        $this->page_breadcrumbs[] = [
            'page' => '#',
            'title' => __("Chỉnh sửa")
        ];
        $item = Item::with('groups:id')->findOrFail($id);
        $categories = Group::where('module', $this->module . '-category')->get(['id', 'title', 'parent_id']);
        return view('backend.items.form_data', [
            'item' => $item,
            'module' => $this->module,
            'categories' => $categories,
            'page_breadcrumbs'=>$this->page_breadcrumbs,
            'page_title' => 'Chỉnh sửa ' . $this->module
        ]);
    }

    public function update(Request $request, $id)
    {
        $data_item = $request->all();
        $item = Item::with('groups')->findOrFail($id);
//        if (isset($data_item['attribute'])) {
//            foreach ($item->item_attributes as $attribute) {
//                foreach ($attribute->subitems as $subitem){
//                  $subitem->delete();
//                }
//                $attribute->delete();
//            }
//            $this->setAttributeItem($data_item['attribute'],$item->id);
//        }
        foreach ($item->groups as $group) {
            if ($group->module == $this->module . '-group') {
                continue;
            } else {
                $item->groups()->detach($group->id);
            }
        }
        if (isset($data_item['group_id'])) {
            foreach ($data_item['group_id'] as $group_id) {
                $item->groups()->attach($group_id);
            }
        }
        $check_duplicate_slug = Item::where('slug', $data_item['slug'])->first();
        if (isset($check_duplicate_slug) && $check_duplicate_slug['slug'] == $item['slug']) {
            $item->update($data_item);
        } else {
            $check_duplicate = $this->checkMatchSlugItem($data_item['slug'], $data_item['title']);
            $data_item['slug'] = $check_duplicate['slug'];
            $data_item['title'] = $check_duplicate['title'];
            $item->update($data_item);
        }
        Session::put('message', 'Cập nhật thay đổi thành công');
        return back();
    }

    public function destroy($id)
    {
        Item::destroy($id);
        Session::put('message', 'Đã xoá item có id: ' . $id);
        return back();
    }

    public function ajaxGetItem($module)
    {
        $items = Item::with(['groups' => function ($query) use ($module) {
                $query->where(['module' => $module . '-category']);
            }]
        )->where('module', $module)
            ->get([
                'id', 'title', 'image', 'order', 'position', 'status', 'created_at'
            ]);
        return Datatables::of($items)->make(true);
    }

    public function searchItemGroup(Request $request)
    {
        $result = Item::where('title', 'LIKE', '%' . $request->search_query . '%')
            ->where('module', substr($request->module, 0, -6))
            ->limit(2)
            ->get([
                'id',
                'image',
                'title',
                'status'
            ]);
        return response()->json($result);
    }

    public function destroyMulti(Request $request)
    {
        $ids = $request->ids;
        Item::whereIn('id', explode(",", $ids))->delete();
        return response()->json([
            'message' => "Đã xoá những item đã chọn !!"
        ]);
    }

    public function setAttributeItem($attributes,$item_id)
    {
        foreach ($attributes as $attribute => $subitems) {
            $item_attribute = Item::create([
                'title' => $attribute,
                'module' => $this->module . '-attribute',
                'parent_id' => $item_id,
            ]);
            foreach ($subitems as $key => $subitem) {
                SubItem::create([
                    'title' => $key,
                    'price' => $subitem['price'],
                    'quantity' => $subitem['qty'],
                    'item_id' => $item_attribute->id,
                ]);
            }
        }
    }

    public function duplicateItem($id)
    {
        $item = Item::findOrFail($id);
        $title_slug = $this->checkMatchSlugItem($item['slug'], $item['title']);
        $item['slug'] = $title_slug['slug'];
        $item['title'] = $title_slug['title'];
        $item->replicate()->save();
        Session::put('message', 'Đã nhân bản item số ' . $id);
        return back();
    }

    public function filterItem(Request $request)
    {

        $items = Item::query();
        if ($request->data['category_id']) {
            $items->whereHas('groups',function (Builder $q) use ($request){
                $q->where('groups.id',$request->data['category_id'])->where('module',$request->data['module'] .'-category');
            });
            $items->with(['groups'=>function($q) use ($request) {
                $q->where('groups.id',$request->data['category_id'])->where('module',$request->data['module'] .'-category');
            }]);
        }else {
            $items->with(['groups'=>function($q) use ($request) {
                $q->where('module',$request->data['module'] .'-category');
            }]);
        }
        if ($request->data['title']) {
            $items->where('title', 'LIKE', '%' . $request->data['title'] . '%');
        }
        if ($request->data['id']) {
            $items->where('id', $request->data['id']);
        }
        if ($request->data['position']) {
            $items->where('position', $request->data['position']);
        }
        if (is_numeric($request->data['status'])) {
            $items->where('status', $request->data['status']);
        }
        if ($request->data['date_from'] && $request->data['date_to']) {
            $items->whereBetween('created_at',[date('Y-m-d H:i:s', strtotime($request->data['date_from'])),date('Y-m-d H:i:s', strtotime($request->data['date_to']))]);
        }

        if ($request->data['module']) {
            $items->where('module', $request->data['module']);
        }

        return Datatables::of($items->get(['id','title','image','order','position','status','created_at']))->make(true);
    }

    public function changeOrderInGroup(Request $request)
    {
        try {
            $items = $request->list;
            $group_id = $request->group_id;
            if (find_key($items,'children')){
                return response()->json([
                    'status'=>0,
                    'message'=>'Vị trí sắp xếp không hợp hệ'
                ]);
            }
            $group = Group::query()->findOrFail($group_id);
            $group->items()->detach();
            foreach ($items as $key => $item){
                $group->items()->attach($item);
                $inter = Group_Item::query()->where('group_id',$group_id)->where('item_id',$item['id'])->first();
                $inter->update(['order'=> $key]);
            }
            return response()->json([
                'status'=>1,
                'message'=>'Cập nhật thứ tự thành công'
            ]);
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }
}
