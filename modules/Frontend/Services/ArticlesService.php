<?php

namespace Modules\Frontend\Services;

use Modules\Core\Ncl\Http\BaseService;
use Modules\Frontend\Repositories\ArticlesRepository;
use Modules\System\Cms\Services\FileService;

class ArticlesService extends BaseService
{
    private $commentService;
    private $fileService;

    public function __construct(CommentService $commentService, FileService $fileService)
    {
        $this->commentService = $commentService;
        $this->fileService = $fileService;
        parent::__construct();
    }
    public function repository()
    {
        return ArticlesRepository::class;
    }
    public function index(): array
    {
        $articles = $this->repository->where('status_articles', 'DA_DUYET')->where('status', 1)->orderBy('create_date', 'desc')->first();
        $data['relateds'] = $this->repository->where('status_articles', 'DA_DUYET')->where('id', '<>', $articles->id)->where('status', 1)->orderBy('create_date', 'desc')->take(5)->get();
        $data['datas']  = $articles;
        return $data;
    }
    public function list(): array
    {
        $data['datas'] = $this->repository->where('status_articles', 'DA_DUYET')->where('status', 1)->orderBy('create_date', 'desc')->get();
        return $data;
    }
    public function _detail($input, $slug): array
    {
        $articles = $this->repository->where('slug', $slug)->where('status', 1)->orderBy('create_date', 'desc')->first();
        $data['arrComments'] = $this->commentService->where('articles_id', $articles->id)->get();
        $data['datas'] = $articles;
        if(!empty($articles)){
            $arrFile = $this->fileService->where('articles_id', $articles->id)->get();
            foreach($arrFile as $file){
                // 2023_10_24_1612242894!~!Landscape-Color.jpg
                $arrFileName = explode('!~!', $file->file_name);
                $file->file_name = $arrFileName[1] ?? $arrFileName[0];
            }
            $data['files'] = $arrFile;
        }
        // dd($data);
        return $data;
    }
}