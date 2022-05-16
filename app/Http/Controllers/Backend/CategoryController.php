<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    protected $module;
    protected $page_breadcrumbs;

    public function __construct(Request $request)
    {
        $this->module = $request->segment(2);
        if( $this->module!="" && !$request->ajax()){
            $this->page_breadcrumbs[] = [
                'page' => route($this->module.'.index'),
                'title' => __('Quản lý danh mục')
            ];
        }
    }

    public function index()
    {
        $nestable = view('backend.components.nestable',['module'=>$this->module,'groups' => $this->getGroupsByModule()]);
        return view('backend.categories.index', [
            'page_title'=>'Category '.$this->module,
            'module' => $this->module,
            'nestable'=>$nestable,
            'page_breadcrumbs'=>$this->page_breadcrumbs,
            ]);
    }

    public function create()
    {
        $this->page_breadcrumbs[] = [
            'page' => '#',
            'title' => __("Thêm mới")
        ];
        $module = $this->module;
        return view('backend.categories.form-data', [
            'groups' => $this->getGroupsByModule(),
            'page_title' => 'Tạo mới danh mục ' . $module,
            'module' => $module,
            'page_breadcrumbs'=>$this->page_breadcrumbs,
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
        $this->page_breadcrumbs[] = [
            'page' => '#',
            'title' => __("Chỉnh sửa")
        ];
        $module = $this->module;
        $category = Group::findOrFail($id);
        return view('backend.categories.form-data', [
            'groups' => $this->getGroupsByModule(),
            'category'=>$category,
            'page_title' => 'Tạo mới danh mục ' . $module,
            'module' => $module,
            'page_breadcrumbs'=>$this->page_breadcrumbs,
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
            'ended_at'=>$data_category['ended_at'],
            'order'=>$data_category['order'],
        ]);
        Session::put('message','Danh mục được cập nhật thành công !');
        return back();
    }

    public function destroy($id)
    {
        try {
            Group::destroy($id);
            $is_destroy = Group::query()->find($id);
            if (!$is_destroy){
                \session()->put('message','Đã xoá danh mục: '. $id);
            }else {
                \session()->put('message','Lỗi chưa xác định');
            }
            return back();
        }catch (\Exception $e){
            return $e;
        }
    }

    public function getGroupsByModule()
    {
        return Group::with(['groups'=>function($q){
            $q->where('module',$this->module);
        }])->where('module',$this->module)->orderBy('order','ASC')->get(['id', 'title','parent_id']);
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
