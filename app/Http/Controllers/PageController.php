<?php
namespace App\Http\Controllers;

use App\Helpers\ResizeHelper;
use App\Repository\PageRepositoryInterface;
use Illuminate\Support\Facades\Crypt;

class PageController extends Controller
{

    public function __construct(PageRepositoryInterface $page)
    {
        $this->page = $page;
    }

    public function getSlug($slug = null)
    {

        // si no viene parametro (root) asumo que es la page home
        if($slug==null){
            $slug='home';
        }

        // var_dump($slug);
        $page = $this->page->getBySlug($slug);

        // var_dump($page);

        if (empty($slug) or $slug != $page->slug) {
            // return redirect()->route('home', 301);
            abort(404);
        }

        \Event::fire('App\Events', $page);

        $title = ucfirst($page->title);

        return view('page.view', compact('page', 'title'));
    }








    public function getByTags($tag)
    {
        $pages = $this->pages->getByTags($tag);
        $title = sprintf('%s %s', t('Tagged With'), ucfirst($tag));

        return view('gallery.index', compact('pages', 'title'));
    }

    public function search(Request $request)
    {
        $this->validate($request, ['q' => 'required']);

        $pages = $this->pages->search($request->get('q'), $request->get('category'), $request->get('timeframe'));

        $title = sprintf('%s %s', t('Searching for'), ucfirst($request->get('q')));

        return view('gallery.index', compact('title', 'pages'));
    }

}
