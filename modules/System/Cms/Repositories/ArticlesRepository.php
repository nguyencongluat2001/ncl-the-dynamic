<?php

namespace Modules\System\Cms\Repositories;

use Modules\Core\Efy\Http\BaseRepository;
use Modules\System\Cms\Models\ArticlesModel;

class ArticlesRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }
    public function model()
    {
        return ArticlesModel::class;
    }
    /**
     * Cập nhật tin bài
     * @param $data
     * @return array
     */
    public function _update($data)
    {
        if($data['id'] != ''){
            $sql = $this->model->find($data['id']);
            $sql->updated_at = date('Y-m-d H:i:s');
        }else{
            $sql = new $this->model;
            $sql->id = (string)\Str::uuid();
            $sql->created_at = date('Y-m-d H:i:s');
        }
        if($data['order'] != ''){
            $this->updateOrder($data);
        }
        if(!empty($data['feature_img'])){
            $feature_img = pathinfo($data['feature_img']);
        }
        $sql->categories_id = $data['categories_id'];
        $sql->users_id = $data['users_id'];
        $sql->users_name = $data['users_name'];
        $sql->source = $data['source'] ?? null;
        $sql->create_date = $data['create_date'] ?? null;
        $sql->title = $data['title'] ?? null;
        $sql->author = $data['author'] ?? null;
        $sql->subject = $data['subject'] ?? null;
        $sql->slug = $data['slug'] ?? null;
        $sql->file_name = $data['fileUpload']['file_name'] ?? ($feature_img['basename'] ?? ($sql->file_name ?? null));
        $sql->feature_img = $data['fileUpload']['url'] ?? ($data['feature_img'] ?? ($sql->feature_img ?? null));
        $sql->note_feature_img = $data['note_feature_img'] ?? null;
        $sql->content = $data['content'] ?? null;
        $sql->status_articles = $data['status_articles'] ?? null;
        $sql->is_comment = isset($data['is_comment']) && $data['is_comment'] === 'on' ? 1 : 0;
        $sql->is_hide_relate_articles = isset($data['is_hide_relate_articles']) && $data['is_hide_relate_articles'] === 'on' ? 1 : 0;
        $sql->title_SEO = $data['title_SEO'] ?? null;
        $sql->description_SEO = $data['description_SEO'] ?? null;
        $sql->articles_type = $data['articles_type'] ?? null;
        $sql->owner_code = $data['owner_code'] ?? null;
        $sql->status = isset($data['status']) && $data['status'] === 'on' ? 1 : 0;
        $sql->order = $data['order'] ?? null;
        // dd($sql);
        $sql->save();
        return $sql;
    }
    /**
     * Cập nhật lại thứ tự
     * 
     * @param int|string $order
     * @return void
     */
    public function updateOrder($input): void
    {
        $articles = $this->model
            ->where('categories_id', $input['categories_id'])
            ->where("order", ">=", $input['order'])
            ->orderBy('order', 'asc')
            ->get(['id', 'order'])->toArray();
        $i = $input['order'];
        foreach ($articles as $article) {
            $i++;
            $this->model->find($article['id'])->update(['order' => $i]);
        }
    }
}