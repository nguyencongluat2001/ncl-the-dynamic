<?php

namespace Modules\Frontend\Services\Dashboard;

use Modules\Base\Service;
use Modules\Frontend\Repositories\Dashboard\BlogRepository;
use Modules\Frontend\Services\Dashboard\BlogDetailService;
use Modules\Frontend\Services\Dashboard\BlogImagesService;
use Modules\Frontend\Services\Dashboard\CategoryService;
use Modules\Frontend\Services\Dashboard\UserService;
use Illuminate\Support\Facades\Hash;
use Modules\Base\Library;
use DB;
use Str;

class BlogService extends Service
{
    private $baseDis;
    public function __construct(
        UserService $userService,
        CategoryService $categoryService,
        BlogImagesService $blogImagesService,
        BlogDetailService $blogDetailService,
        BlogRepository $blogRepository
        )
    {
        parent::__construct();
        $this->userService       = $userService;
        $this->categoryService   = $categoryService;
        $this->blogImagesService = $blogImagesService;
        $this->blogDetailService = $blogDetailService;
        $this->blogRepository = $blogRepository;
        $this->baseDis = public_path("file-image-client/blogs") . "/";
    }

    public function repository()
    {
        return BlogRepository::class;
    }
     /**
     * cập nhật bài viết
     */
    public function store($input,$file){
      
        DB::beginTransaction();
        try{
            //lấy mã bài viết
            $random = Library::_get_randon_number();
            $code_blog = date("Y") . '_' . date("m") . '_' . date("d") . "_" . date("H") . date("i") . date("u") . $random;
            $image_old = null;
            if($input['id'] != ''){
                $blog = $this->blogRepository->where('id',$input['id'])->first();
                $image = $this->blogImagesService->where('code_blog',$blog['code_blog'])->first();
                $image_old = !empty($image->name_image)?$image->name_image:'';
                $code_blog = $blog['code_blog'];
            }
            if(isset($file) && $file != []){
                $arrFile = $this->uploadFile($input,$file,$image_old);
            }
            // array data users
            $arrBlog = [
                'user_id' => $_SESSION['id'],
                'code_blog' => $code_blog,
                'code_category' => $input['code_category'],
                'type_blog' => isset($input['type_blog']) ? 1 : 0,
                'status' => isset($input['status']) ? 1 : 0,
                'created_at' => date("Y/m/d H:i:s"),
                'updated_at' => date("Y/m/d H:i:s")
            ];
            $arrBlogDetails = [
                'title'=>$input['title'],
                'decision'=>$input['decision'],
                'rate'=> 5
            ];
            if($input['id'] != ''){
                //edit Blog
                $createBlog = $this->where('id',$input['id'])->update($arrBlog);
                //Create Blog details
                $createBlogDetails = $this->blogDetailService->where('code_blog',$code_blog)->update($arrBlogDetails);
                // create image Blog
                if(!empty($arrFile) && $arrFile != []){
                    $i = 1;
                    $image = $this->blogImagesService->where('code_blog',$code_blog)->delete();
                    foreach($arrFile as $imageValue){
                        $imageNew = trim($imageValue, "!~!");
                        $name = explode("!~!", $imageNew);
                        $arrImages = [
                            'id'=> (string)Str::uuid(),
                            'code_blog'=> $code_blog,
                            'name'=> $name[1],
                            'name_image'=> $imageValue,
                            'order_image'=> $i,
                            'created_at' => date("Y/m/d H:i:s"),
                            'updated_at' => date("Y/m/d H:i:s")
                        ];
                        $createImage = $this->blogImagesService->create($arrImages);
                        $i++;
                    }
                }
            }else{
                //Create Blog
                $arrBlog['id']= (string)Str::uuid();
                $createBlog = $this->create($arrBlog);
                //Create Blog details
                $arrBlogDetails['id']= (string)Str::uuid();
                $arrBlogDetails['code_blog'] = $code_blog;
                $createBlogDetails = $this->blogDetailService->create($arrBlogDetails);
                // create image Blog
                if(!empty($arrFile) && $arrFile != []){
                    $i = 1;
                    foreach($arrFile as $imageValue){
                        $imageNew = trim($imageValue, "!~!");
                        $name = explode("!~!", $imageNew);
                        $arrImages = [
                            'id'=> (string)Str::uuid(),
                            'code_blog'=> $code_blog,
                            'name'=> $name[1],
                            'name_image'=> $imageValue,
                            'order_image'=> $i,
                            'created_at' => date("Y/m/d H:i:s"),
                            'updated_at' => date("Y/m/d H:i:s")
                        ];
                        $createImage = $this->blogImagesService->create($arrImages);
                        $i++;
                    }
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            dd($e);
             DB::rollBack();
            return array('success' => false, 'message' => (string) $e->getMessage());
        }
    }
    // /**
    //  * Tải ảnh vào thư mục
    //  */
    public function uploadFile($input,$file,$image_old)
    {
            $path = $this->baseDis;
            $old_path = $path.$image_old;
            if (file_exists($old_path)) {
                @unlink($old_path);
            }
            foreach($file as $attValue){
                $fileName = $attValue['name'];
                $random = Library::_get_randon_number();
                $fileName = Library::_replaceBadChar($fileName);
                $fileName = Library::_convertVNtoEN($fileName);
                $sFullFileName = date("Y") . '_' . date("m") . '_' . date("d") . "_" . date("H") . date("i") . date("u") . $random . "!~!" . $fileName;
                move_uploaded_file($attValue['tmp_name'], $path . $sFullFileName);
                $arrImage[] =  $sFullFileName;
            }
            return $arrImage;
    }
    public function editBlog($arrInput){
        $getBlogInfor = $this->repository->where('id',$arrInput['chk_item_id'])->first();
        $arrBlog = '';
        if(isset($getBlogInfor)){
            $blogDetail = $this->blogDetailService->where('code_blog',$getBlogInfor['code_blog'])->first();
            $blogImage = $this->blogImagesService->where('code_blog',$getBlogInfor['code_blog'])->get();
            $arrBlog = [
                'id' => $getBlogInfor->id,
                'code_blog' => $getBlogInfor->code_blog,
                'code_category' => isset($getBlogInfor->code_category)?$getBlogInfor->code_category:null,
                'type_blog' => isset($getBlogInfor->type_blog)?$getBlogInfor->type_blog:null,
                'status' => $getBlogInfor->status,
                'title' => isset($blogDetail->title)?$blogDetail->title:null,
                'decision' => isset($blogDetail->decision)?$blogDetail->decision:null,
                'rate' => isset($blogDetail->rate)?$blogDetail->rate:5,
                'image' => !empty($blogImage)?$blogImage:null,
            ];
        }
        return $arrBlog;
    }
    /**
     * Màn hình thông tin bài viết
     *
     * @param Request $request
     *
     * @return view
     */
    public function infor($input)
    {
        $dataInfor = $this->where('id',$input['id'])->first();
        $category = $this->categoryService->where('code_category',$dataInfor->code_category)->first();
        $blogDetail = $this->blogDetailService->where('code_blog',$dataInfor['code_blog'])->first();
        $blogImage = $this->blogImagesService->where('code_blog',$dataInfor['code_blog'])->get()->toArray();
        $users = $this->userService->where('id',$dataInfor['user_id'])->first();
        $data = [
            'users_name' => !empty($users->name)?$users->name:null,
            'code_blog' => $dataInfor->code_blog,
            'name_category' => isset($category->name_category)?$category->name_category:null,
            'status' => !empty($dataInfor->status == '1')?'Hoạt động':'Không hoạt động',
            'title' => isset($blogDetail->title)?$blogDetail->title:null,
            'decision' => isset($blogDetail->decision)?$blogDetail->decision:null,
            'rate' => isset($blogDetail->rate)?$blogDetail->rate:5,
            'image' => !empty($blogImage)?$blogImage:null,
            'created_at' => !empty($blogDetail->created_at)?$blogDetail->created_at:null
        ];
        return $data;
    }
     /**
     * xóa bài viết
     *
     * @param Request $request
     *
     * @return view
     */
    public function delete($input)
    {
        $listids = trim($input['listitem'], ",");
        $ids = explode(",", $listids);
        foreach ($ids as $id) {
            if ($id) {
                $getBlogInfor = $this->repository->where('id',$id)->first();
                $this->repository->where('id',$id)->delete();
                $this->blogDetailService->where('code_blog',$getBlogInfor->code_blog)->delete();
                $this->blogImagesService->where('code_blog',$getBlogInfor->code_blog)->delete();
            }
        }
    }
    /**
     * Upload file nội dung bài viết
     */
    public function uploadFileCK($input)
    {
        $folder = $this->baseDis;
        $result = [];
        $random = Library::_get_randon_number();
        if($_FILES != []){
            $filename = $_FILES['upload']['name'];
            $filename = Library::_replaceBadChar($filename);
            $filename = Library::_convertVNtoEN($filename);
            $filename = date('Y_m_d_Hiu') . $random .'!~!' . $filename;
            $fullname = $folder . $filename;
            copy($_FILES['upload']['tmp_name'], $fullname);
            $result = [
                'name' => $_FILES['upload']['name'],
                'url' => url('file-image-client/blogs') . '/' . $filename,
                'base_path' => $fullname,
                'size' => $_FILES['upload']['size'],
            ];
        }
        $response = '';
        if($_FILES != [] && isset($input['upload'])){
            $CKEditorFuncNum = $input['CKEditorFuncNum'];
            $url = $result['url'] ?? '';
            $msg = 'Tải ảnh lên thành công.';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
        }
        echo $response;
    }
}
