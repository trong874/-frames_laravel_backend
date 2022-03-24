<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    protected $module;

    public function __construct(Request $request)
    {
        $this->module = $request->segment(2);
    }

    public function index()
    {
        $groups = Group::with('groups')
            ->where('module', $this->module)
            ->orderBy('order', 'ASC')
            ->get(['id', 'title', 'parent_id']);
        $nestable = view('backend.components.nestable',['module'=>$this->module,'groups' => $groups]);
        return view('backend.categories.index', [
            'page_title'=>'Category '.$this->module,
            'module' => $this->module,
            'nestable'=>$nestable,]);
    }

    public function create()
    {
        $module = $this->module;
        return view('backend.categories.form-data', [
            'groups' => $this->getGroupsByModule(),
            'page_title' => 'Tạo mới danh mục ' . $module,
            'module' => $module
        ]);
    }

    public function store(Request $request)
    {
        $data_category = $request->all();
        Group::create($data_category);
        Session::put('message','Tạo mới thành công !');
        return back();
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $module = $this->module;
        $category = Group::findOrFail($id);
        return view('backend.categories.form-data', [
            'groups' => $this->getGroupsByModule(),
            'category'=>$category,
            'page_title' => 'Tạo mới danh mục ' . $module,
            'module' => $module
        ]);
    }

    public function update(Request $request, $id)
    {
        $data_category = $request->all();
        $category = Group::findOrFail($id);
        $category->update([
            'title'=>$data_category['title'],
            'slug'=>$data_category['slug'],
            'module'=>$data_category['module'],
            'position'=>$data_category['position'],
            'parent_id'=>$data_category['group_id'],
            'description'=>$data_category['description'],
            'content'=>$data_category['content'],
            'author_id'=>$data_category['author_id'],
            'url'=>$data_category['url'],
            'image'=>$data_category['image'],
            'image_extension'=>$data_category['image_extension'],
            'image_banner'=>$data_category['image_banner'],
            'image_icon'=>$data_category['image_icon'],
            'status'=>$data_category['status'],
            'created_at'=>$data_category['created_at'],
            'ended_at'=>$data_category['ended_at'],
            'order'=>$data_category['order'],
        ]);
        Session::put('message','Danh mục được cập nhật thành công !');
        return back();
    }

    public function destroy($id)
    {

    }

    public function getGroupsByModule()
    {
        $module = $this->module;
        return Group::where('module',$module)->get(['id', 'title']);
    }

    public function destroyMulti(Request $request)
    {
        $ids = $request->ids;
        Group::whereIn('id', explode(",", $ids))->delete();
        return response()->json([
            'status'=>'success',
            'message'=>'Đã xoá !'
        ]);
    }

    function changeOrder(Request $list)
    {
        $this->recursive($list->all()['list']);
        return response()->json('Cập nhật danh sách thành công', 200);
    }

    public function recursive($list, $parent_id = null, &$order = 0)
    {
        foreach ($list as $item) {
            $order++;
            $group = Group::find($item['id']);
            $group->order = $order;
            $group->parent_id = $parent_id;
            $group->save();
            if (array_key_exists('children', $item)) {
                $this->recursive($item["children"], $item["id"], $order);
            }
        }
    }
}
