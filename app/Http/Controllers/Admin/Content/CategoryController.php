<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\PostCategory;
use App\Http\Requests\Admin\Content\CategoryRequest;
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
    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();
        
        // Generate slug from name if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('storage/categories'), $imageName);
            $validated['image'] = 'storage/categories/' . $imageName;
        }
        
        PostCategory::create($validated);
        
        return redirect()->route('admin.content.category.index')->with('swal-success', 'دسته بندی با موفقیت ایجاد شد');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
