<?php

namespace App\Http\Controllers\Admin;

use App\Events\ArticlePublishEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ArticleRequest;
use App\Repositories\Admin\ArticleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function __construct(protected ArticleRepository $repository)
    {}

    public function index()
    {
        $articles = $this->repository->getList();

        return view('admin.article.index',compact('articles'));
    }

    public function create()
    {
        $token = $this->apiToken();

        return view('admin.article.create',compact('token'));
    }

    public function store(ArticleRequest $request)
    {
        if ($request->input('save_to') === '0') {
            $message = __('admin/article.submit.trashed');
            $this->repository->trashed($request->all());
        } else {
            $article = $this->repository->published($request->all());
            $message = __('admin/article.submit.publish');
            event(new ArticlePublishEvent($article));
        }

        return redirect()->route('admin.article.index')->with(['success'=>$message]);
    }

    public function edit($id)
    {
        $token = $this->apiToken();
        $article = $this->repository->show($id);

        return view('admin.article.edit', compact(
            'article',
            'token'
        ));
    }

    public function update(Request $request, $id)
    {
        $this->repository->update(array_merge(['id'=>$id],$request->all()));

        if ($request->input('save_to') === '0') {
            $this->repository->hide($id);
        } else {
            $this->repository->restore($id);
        }

        return redirect()->route('admin.article.index')->with(['success'=>'Successfully updated']);
    }

    public function destroy($id)
    {
        $this->repository->destroy($id);

        return redirect()->back()->with(['success'=>'Permanently deleted']);
    }

    public function hide($id)
    {
        $this->repository->hide($id);

        return redirect()->back()->with(['success'=>__('admin/article.submit.trash')]);
    }

    public function restore($id)
    {
        $this->repository->restore($id);

        return redirect()->back()->with(['success'=>'Resurrected from Trash']);
    }

    protected function apiToken()
    {
        return Auth::user()->createApiToken();
    }
}
