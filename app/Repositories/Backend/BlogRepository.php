<?php

namespace App\Repositories\Backend;

use App\Enums\CategoryType;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Exception;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class BlogRepository extends BaseRepository
{
    protected $category;

    protected $tag;

    public function model()
    {
        $this->category = new Category();
        $this->tag = new Tag();

        return Blog::class;
    }

    public function index()
    {
        return view('backend.blog.index');
    }

    public function create($attribute = [])
    {
        return view('backend.blog.create', [
            'categories' => $this->getCategories(),
            'tags' => $this->getTags(),
        ]);
    }

    private function getCategories()
    {
       
        return  $this->category->getCategoryDropdownOptions(CategoryType::BLOG);
    }

    private function getTags()
    {
        return $this->tag->where('type', 'blog')
            ->where('status', true)
            ->pluck('name', 'id');
    }

    public function show($id) {}

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $blog = $this->model->create(
                [
                    'title' => $request->title,
                    'description' => $request->description,
                    'content' => $request->content,
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'is_featured' => $request->is_featured,
                    'status' => $request->status,
                ]
            );

            if ($request->image) {
                $uploadedImages = $request->image;
                foreach ($uploadedImages as $uploadedImage) {
                    $blog->addMedia($uploadedImage)->toMediaCollection('image');
                    $blog->media;
                }
            }

            if ($request->web_image) {
                $uploadedImages = $request->web_image;
                foreach ($uploadedImages as $uploadedImage) {
                    $blog->addMedia($uploadedImage)->toMediaCollection('web_image');
                    $blog->media;
                }
            }

            if ($request->hasFile('meta_image') && $request->file('meta_image')->isValid()) {
                $blog->addMedia($request->file('meta_image'))->toMediaCollection('meta_image');
            }

            if (isset($request->categories)) {
                $blog->categories()->attach($request->categories);
                $blog->categories;
            }

            if (isset($request->tags)) {
                $blog->tags()->attach($request->tags);
                $blog->tags;
            }

            DB::commit();

            return redirect()->route('backend.blog.index')->with('message', 'Blog Created Successfully.');
        } catch (Exception $e) {

            DB::rollback();

            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $blog = $this->model->find($id);
        return view('backend.blog.edit', [
            'blog' => $blog,
            'categories' => $this->getCategories(),
            'tags' => $this->getTags(),
            'default_categories' => $this->getDefaultCategories($blog),
            'default_tags' => $this->getDefaultTags($blog),
        ]);
    }

    public function getDefaultCategories($blog)
    {
        $categories = [];
        foreach ($blog->categories as $category) {
            $categories[] = $category->id;
        }
        $categories = array_map('strval', $categories);

        return $categories;
    }

    public function getDefaultTags($blog)
    {
        $tags = [];
        foreach ($blog->tags as $tag) {
            $tags[] = $tag->id;
        }
        $tags = array_map('strval', $tags);

        return $tags;
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $blog = $this->model->findOrFail($id);
            $blog->update($request->all());

            if (isset($request->categories)) {
                $blog->categories()->sync($request->categories);
                $blog->categories;
            }

            if (isset($request->tags)) {
                $blog->tags()->sync($request->tags);
                $blog->tags;
            }

            if ($request['image']) {
                $uploadedImages = $request->file('image');
                $blog->clearMediaCollection('image');
                foreach ($uploadedImages as $uploadedImage) {
                    $blog->addMedia($uploadedImage)->toMediaCollection('image');
                    $blog->media;
                }
            }

            if ($request['web_image']) {
                $uploadedImages = $request->file('web_image');
                $blog->clearMediaCollection('web_image');
                foreach ($uploadedImages as $uploadedImage) {
                    $blog->addMedia($uploadedImage)->toMediaCollection('web_image');
                    $blog->media;
                }
            }
            if ($request['meta_image']) {
                $blog->clearMediaCollection('meta_image');
                $blog->addMedia($request['meta_image'])->toMediaCollection('meta_image');
            }

            DB::commit();

            return redirect()->route('backend.blog.index')->with('success', 'Blog Updated Successfully.');
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $category = $this->model->findOrFail($id);
            $category->destroy($id);

            DB::commit();

            return redirect()->back()->with(['message' => 'Blog deleted successfully']);
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $blog = $this->model->findOrFail($id);
            $blog->update(['status' => $status]);

            return json_encode(['resp' => $blog]);
        } catch (Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    public function updateIsFeatured($id, $status)
    {
        try {
            $blog = $this->model->findOrFail($id);
            $blog->update(['is_featured' => $status]);

            return json_encode(['resp' => $blog]);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteAll($ids)
    {
        DB::beginTransaction();
        try {

            $this->model->whereNot('system_reserve', true)->whereIn('id', $ids)->delete();

            return back()->with('message', 'Roles Deleted Successfully');
        } catch (Exception $e) {

            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }
}
