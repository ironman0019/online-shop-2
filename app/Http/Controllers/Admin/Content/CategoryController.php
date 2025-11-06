<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\PostCategory;
use App\Http\Requests\Admin\Content\CategoryRequest;
use App\Http\Services\ImageService\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = PostCategory::orderBy('created_at', 'desc')->paginate('10');
        return view('admin.content.category.index', compact('categories'));
    }   

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.content.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request, ImageService $imageService)
    {
        $inputs = $request->all();

        if($request->hasFile('image')) {
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'post-categories');
            $result = $imageService->createIndexAndSave($request->file('image'));

            if($result === false) {
                return back()->with('swal-error', 'خطا در آپلود عکس');
            }
            $inputs['image'] = $result;
        }
        
        PostCategory::create($inputs);
        
        return to_route('admin.content.category.index')->with('swal-success', 'دسته بندی با موفقیت ایجاد شد');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PostCategory $category)
    {
        return view('admin.content.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, PostCategory $category, ImageService $imageService)
    {
        $inputs = $request->all();

        if ($request->hasFile('image')) {
            // Remove old image
            $imageService->deleteIndex($category->image);

            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'post-categories');
            $result = $imageService->createIndexAndSave($request->file('image'));

            if ($result === false) {
                return back()->with('swal-error', 'خطا در آپلود عکس');
            }
            $inputs['image'] = $result;
        }
        else
        {
            if(isset($inputs['currentImage']) && !empty($category->image)) {
                $image = $category->image;
                $image['currentImage'] = $inputs['currentImage'];
                $inputs['image'] = $image;
            }
        }

        $category->update($inputs);
        return to_route('admin.content.category.index')->with('swal-success', 'دسته بندی با موفقیت آپدیت شد');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostCategory $category)
    {
        $category->delete();
        return to_route('admin.content.category.index')->with('swal-success', 'دسته بندی با موفقیت حذف شد');
    }

    public function status(PostCategory $category)
    {

        $category->status = $category->status == 0 ? 1 : 0;
        $result = $category->save();
        if ($result) {
            if ($category->status == 0) {
                return response()->json(['status' => true, 'checked' => false]);
            } else {
                return response()->json(['status' => true, 'checked' => true]);
            }
        } else {
            return response()->json(['status' => false]);
        }
    }


}
