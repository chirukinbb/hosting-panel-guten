<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(protected ArticleRepository $repository)
    {}

    public function index($slug)
    {
        $article = $this->repository->getBySlug($slug);

        return view('article',compact('article'));
    }
}
