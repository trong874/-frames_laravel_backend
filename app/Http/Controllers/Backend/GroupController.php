<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Group_Item;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class GroupController extends Controller
{
    protected $module;
    protected $page_breadcrumbs;

    public function __construct(Request $request)
    {
        $this->module = $request->segment(2);
        if( $this->module!="" && !$request->ajax()){
            $this->page_breadcrumbs[] = [
                'page' => route($this->module.'.index'),
                'title' => __('Quản lý các nhóm')
            ];
        }
    }

    public function index()
    {
        return view('backend.groups.index',[
            'module'=>$this->module,
            'page_breadcrumbs'=>$this->page_breadcrumbs,
        ]);
    }

    public function create()
    {
        $this->page_breadcrumbs[] = [
            'page' => '#',
            'title' => __("Thêm mới"),
        ];
        return view('backend.groups.form_data', [
            'groups' => $this->getGroupsByModule(),
            'module' => $this->module,
            'page_breadcrumbs'=>$this->page_breadcrumbs,
        ]);
    }

    public function store(Request $request)
    {
        Group::create([
            'status' => $request->status,
            'title' => $request->title,
            'position' => $request->position,
            'module' => $request->module,
            'slug' => $request->slug,
            'parent_id' => $request->parent_id,
            'image' => $request->image,
            'author_id' => $request->author_id
        ]);
        Session::put('message', 'Tạo mới thành công');
        return back();
    }

    public function edit($group)
    {
        $this->page_breadcrumbs[] = [
            'page' => '#',
            'title' => __("Chỉnh sửa"),
        ];
        $group = Group::findOrFail($group);
        return view('backend.groups.form_data', [
            'groups' => $this->getGroupsByModule(),
            'module' => $this->module,
            'group' => $group,
            'page_breadcrumbs'=>$this->page_breadcrumbs,
        ]);
    }

    public function update(Request $request, $group)
    {
        $group = Group::findOrFail($group);
        $group->update($request->all());
        Session::put('message', 'Chỉnh sửa thành công');
        return back();
    }

    public function destroy($group)
    {
        Group::destroy($group);
        Session::put('message', 'Xoá thành công group số ' . $group);
        return back();
    }

    public function addItemIntoGroup(Request $request)
    {
        $item_id = $request->item_id;
        $group_id = $request->group_id;
        $check = Group_Item::whereItem_id($item_id)->whereGroup_id($group_id)->first();
        if ($check) {
            return response()->json([
                'error_message' => 'Item đã tồn tại trong nhóm này.'
            ]);
        }
        $item = Item::findOrFail($item_id);

        $item->groups()->attach($group_id);

        $group = Group::findOrFail($group_id);
        $items = $group->items;
        return response()->json([
            'items' => $items,
            'message' => 'item có id ' . $item_id . ' đã được thêm vào group'
        ]);
    }

    public function deleteItemInGroup(Request $request)
    {
        $item = Item::findOrFail($request->item_id);
        $item->groups()->detach($request->group_id);
        $group = Group::findOrFail($request->group_id);
        $items = $group->items;
        return response()->json([
            'items' => $items,
            'message' => 'item có id ' . $request->item_id . ' đã bị xoá khỏi group'
        ]);
    }

    public function getGroupsByModule()
    {
        return Group::where('module', substr($this->module, 0, -5) . 'category')->get(['id', 'title']);
    }

    public function ajaxGetGroup($module)
    {
        $groups = Group::query()->with('group')->where('module', $module)->get([
            'id', 'title', 'image', 'parent_id', 'order', 'position', 'status', 'created_at'
        ]);
        return Datatables::of($groups)->make(true);
    }

    public function getItemInGroup(Request $request)
    {
        $group_id = $request->group_id;
        $group = Group::with(['itemsChild' => function ($q) {
            $q->select('items.id', 'items.title');
        }])->select('id')->findOrFail($group_id)->toArray();
        return response()->json($group['items_child']);
    }

    public function destroyMulti(Request $request)
    {
        $ids = $request->ids;
        Group::whereIn('id', explode(",", $ids))->delete();
        return response()->json([
            'message' => "Đã xoá những item đã chọn !!"
        ]);
    }

}
