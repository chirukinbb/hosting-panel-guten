<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::withTrashed()->get();

        return view('admin.article.index',compact('articles'));
    }

    public function create()
    {
        return view('admin.article.create');
    }

    public function store(Request $request)
    {
        $article = Article::create($request->all());
        $message = __('admin/article.submit.publish');

        if ($request->input('save_to') === 0) {
            $message = __('admin/article.submit.trashed');
            $article->delete();
        }

        return redirect()->route('admin.article.index')->with(['success'=>$message]);
    }

    public function edit($id)
    {
        $token = Auth::user()->tokens_count ?: Auth::user()->createToken(
            'api_token',
            [Auth::user()->getRoleNames()[0]]
        );
        $article = Article::find($id);

        return view('admin.article.edit', compact(
            'article',
            'token'
        ));
    }

    public function update(Request $request, $id)
    {
        Article::find($id)->update(
            $request->all()
        );

        return redirect()->route('admin.article.index')->with(['success'=>'Successfully updated']);
    }

    public function destroy($id)
    {
        Article::find($id)->forceDelete();

        return redirect()->back()->with(['success'=>'Permanently deleted']);
    }

    public function hide($id)
    {
        Article::find($id)->delete();

        return redirect()->back()->with(['success'=>__('admin/article.submit.trash')]);
    }

    public function restore($id)
    {
        Article::whereId($id)->restore();

        return redirect()->back()->with(['success'=>'Resurrected from Trash']);
    }
}
