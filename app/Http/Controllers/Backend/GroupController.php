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

    public function __construct(Request $request)
    {
        $this->module = $request->segment(2);
    }

    public function index()
    {
        $module = $this->module;
        return view('backend.groups.index',compact('module'));
    }

    public function create()
    {
        return view('backend.groups.form_data', [
            'groups'=>$this->getGroupsByModule(),
            'module' => $this->module,
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
        $group = Group::findOrFail($group);
        return view('backend.groups.form_data', [
            'groups' => $this->getGroupsByModule(),
            'module' => $this->module,
            'group' => $group
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
            'items'=>$items,
            'message'=>'item có id '.$item_id.' đã được thêm vào group'
        ]);
    }

    public function deleteItemInGroup(Request $request)
    {
        $item = Item::findOrFail($request->item_id);
        $item->groups()->detach($request->group_id);
        $group = Group::findOrFail($request->group_id);
        $items = $group->items;
        return response()->json([
            'items'=>$items,
            'message'=>'item có id '.$request->item_id.' đã bị xoá khỏi group'
        ]);
    }

    public function getGroupsByModule()
    {
        return Group::where('module', substr($this->module,0,-5).'category')->get(['id', 'title']);
    }
    public function ajaxGetGroup($module)
    {
        $groups = Group::query()->with('group')->where('module',$module)->get([
            'id', 'title', 'image','parent_id', 'order', 'position', 'status', 'created_at'
        ]);
        return Datatables::of($groups)->make(true);
    }

    public function getItemInGroup(Request $request)
    {
       $group_id = $request->group_id;
       $group = Group::findOrFail($group_id);
       $items = $group->items;
       return response()->json($items);
    }

    public function destroyMulti(Request $request)
    {
        $ids = $request->ids;
        Group::whereIn('id', explode(",", $ids))->delete();
        return response()->json([
            'message'=>"Đã xoá những item đã chọn !!"
        ]);
    }

}
