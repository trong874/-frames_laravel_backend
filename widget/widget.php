<?php

use App\Models\Group;
use App\Models\Item;
use App\Models\Setting;
use Illuminate\Support\Facades\View;


//query lấy những bài viết gần đây
View::composer('frontend.components.recent-blogs', function ($view) {
    $articles  = Item::query()->where('module','article')->where('status',1)->orderBy('id','DESC')->limit(3)->get();
    return $view->with('articles', $articles);
});

//query lấy những bài viết mới nhất
View::composer('frontend.components.our-latest-news', function ($view) {
    $articles  = Item::query()->where('module','article')
        ->where('status',1)->orderBy('id','DESC')->limit(6)->get();
    return $view->with('articles', $articles);
});

//query lấy video

View::composer('frontend.section.crypto-video',function ($view){
    $crypto_videos = Item::query()
        ->where('module','crypto-video')
        ->where('status',1)->limit(6)
        ->orderBy('order','ASC')
        ->get();
    return $view->with('crypto_videos',$crypto_videos);
});

// query lấy phần introduction
View::composer('frontend.section.intro',function ($view){
    $intro = Item::query()->where('module','advertisement')
        ->where('slug','gioi-thieu')
        ->where('status',1)
        ->first();
    return $view->with('intro',$intro);
});

// query lấy phần started
View::composer('frontend.section.started',function ($view){
    $started = Item::query()->where('module','advertisement')
        ->where('slug','bat-dau')
        ->where('status',1)
        ->first();

    return $view->with('started',$started);
});

// query lấy phần service
View::composer('frontend.section.company',function ($view){
    $services = Item::query()->where('module','advertisement')
        ->where('position','service')
        ->where('status',1)
        ->get();

    return $view->with('services',$services);
});
//get setting
View::composer('frontend.layout.footer', function ($view) {
    $settings = Setting::all();
    return $view->with('settings', $settings);
});
// query phần start
View::composer('frontend.section.started',function ($view){
    $starts = Item::query()->where('module','advertisement')
        ->where('position','start')
        ->where('status',1)
        ->get();
    return $view->with('starts',$starts);
});

View::composer('frontend.section.testimonial',function ($view){
    $testimonials = Item::query()->where('module','advertisement')
        ->where('position','strategy')
        ->where('status',1)
        ->get();

    return $view->with('testimonials',$testimonials);
});

// lấy phần intro
View::composer('frontend.section.intro',function ($view){
    $intros = Item::query()->where('module','advertisement')
        ->where('position','slider')
        ->where('status',1)
        ->get();
    return $view->with('intros',$intros);
});

//query lấy những bài viết xu hướng
View::composer('frontend.components.our-trend', function ($view) {
    $article_trends  = Item::query()->with('groups:id,title,slug')
        ->where('module','trend')
        ->where('status',1)->orderBy('id','DESC')->limit(6)->get();

    return $view->with('article_trends', $article_trends);
});

//query lấy các chủ đề của xu hướng.

View::composer('frontend.layout.header',function ($view){
    $topics = Group::query()->where('module','trend-group')->where('status',1)->get();
    return $view->with('topics',$topics);
});

// nhóm bài viết của danh mục kiếm tiền cùng R9T;

View::composer('frontend.layout.header',function ($view){
    $category = Group::with('groups')->where('module','article-category')->first();
    $groups = $category['groups'];
    return $view->with('groups_make_money',$groups);
});

// bài viết phân tích chuyên sâu trang chủ

View::composer('frontend.section.analysis-depth',function ($view){
    $group = Group::query()->where('module','article-group')
        ->where('slug','phan-tich-chuyen-sau')
        ->first();
    $items = '';
    if ($group){
        $items = $group->items()->where('status',1)->limit(3)->orderBy('created_at','ASC')->get();
    }
    return $view->with('group_analysis_depth',$group)->with('articles_analysis_depth',$items);
});

// section xu hướng

View::composer('frontend.section.trend',function ($view){
    $trends = Group::query()->where('module','trend-group')->where('status',1)->get();
    return $view->with('trends',$trends);
});

//section kiến thức người mới ở trang chủ
View::composer('frontend.section.for-newbies',function ($view){
    $category = Group::with('groups.items:id,title,slug,content,image,url,created_at')->where('module','crypto-video-category')->first();
    return $view->with('category',$category);
});

// lấy ra phần các khóa học
View::composer('frontend.section.course',function ($view){
    $courses = Item::query()->where('module','course')
        ->where('position','course')
        ->where('status',1)
        ->get();
    return $view->with('courses',$courses);
});
// lấy ra phần các giảng viên
View::composer('frontend.section.team',function ($view){
    $teams = Item::query()->where('module','team')
        ->where('position','team')
        ->where('status',1)
        ->get();

    return $view->with('teams',$teams);
});
// lấy ra phần lí do chọn R9T
View::composer('frontend.section.different',function ($view){
    $differents = Item::query()->where('module','team')
        ->where('position','different')
        ->where('status',1)
        ->get();
    return $view->with('differents',$differents);
});
// lấy ra phần đăng kí khóa học
View::composer('frontend.section.register-course',function ($view){
    $register_videos = Item::query()->where('module','course')
        ->where('position','video')
        ->where('status',1)
        ->first();
    return $view->with('register_course',$register_videos);
});
//về r9t section index

View::composer('frontend.section.about',function ($view){
    $about = Item::query()->where('module','advertisement')->where('slug','ve-r9t-crypto')->first();
    return $view->with('about',$about);
});

