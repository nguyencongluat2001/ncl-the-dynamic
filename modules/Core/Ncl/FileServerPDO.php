<?php

namespace Modules\Core\Ncl;

use PDO;

/**
 * File Server
 * 
 * @author khuongtq
 * @created 20/12/2022
 */
class FileServerPDO
{

    /**
     * PDO object
     */
    private $pdo;

    /**
     * Ghi log
     */
    private $logger;

    /**
     * Context (used in file_get_contents())
     */
    private $context;

    function __construct()
    {
        $this->logger = new LoggerHelpers;
        $this->logger->setFileName('FileServerPDO');
        try {
            $config = config('database.connections.sqlsrvFS');
            $this->pdo = new PDO("sqlsrv:Server=" . $config['host'] . ";Database=" . $config['database'], $config['username'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->context = stream_context_create([
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ],
            ]);
        } catch (\Exception $e) {
            $this->logger->setChannel('__construct')
                ->log('Exception', [
                    $config,
                    $e,
                ]);
        }
    }

    /**
     * Thực thi lệnh SQL thô trên database File Server
     * 
     * @param string $sql Chuỗi sql
     * @return mixed
     */
    public function rawSQL(string $sql)
    {
        $query = $this->pdo->prepare($sql);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Tải file lên FileServer
     * 
     * @param string $filename Tên file lưu vào server
     * @param string $filepath Đường dẫn file hiện tại để lấy binary content
     * @param string $recordId ID hồ sơ
     */
    public function uploadFile(string $filename, $content, string $recordId, string $ownerCode, string $codeRecordtypefile = '', string $nameRecordtypefile = '', int $isOnegate = 1)
    {
        try {
            $sql = "EXEC dbo.File_Upload :fileName, :stream, :isOnegate, :recordId, :ownerCode, :y, :m, :d, :codeRecordtypeFile, :nameRecordtypeFile";
            $query = $this->pdo->prepare($sql);
            $query->bindParam(':fileName', $filename);
            $query->bindParam(':stream', $content, PDO::PARAM_LOB, 0, PDO::SQLSRV_ENCODING_BINARY);
            $query->bindValue(':isOnegate', $isOnegate);
            $query->bindParam(':recordId', $recordId);
            $query->bindParam(':ownerCode', $ownerCode);
            $query->bindValue(':y', date('Y'));
            $query->bindValue(':m', date('m'));
            $query->bindValue(':d', date('d'));
            $query->bindParam(':codeRecordtypeFile', $codeRecordtypefile);
            $query->bindParam(':nameRecordtypeFile', $nameRecordtypefile);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_OBJ);

            return (array) $result[0];
        } catch (\Exception $e) {
            $this->logger->setChannel('uploadFile')
                ->log('Exception', [
                    'filename'   => $filename,
                    'record_id'  => $recordId,
                    $e
                ]);
            return false;
        }
    }

    /**
     * Xóa file trên FileServer
     */
    public function deleteFile(string $streamId)
    {
        try {
            $this->pdo->exec("EXEC dbo.File_Delete 1, '$streamId'");
            return true;
        } catch (\Exception $e) {
            $this->logger->setChannel('deleteFile')
                ->log('Exception', [
                    'stream_id' => $streamId, $e
                ]);

            return false;
        }
    }

    /**
     * Trả về html view file
     * 
     * @param array|object $file Thông tin file lấy từ SQL File Server (stream_id, name, file_type)
     * @return view
     */
    public function getViewFile($file)
    {
        try {
            if (empty($file)) return '';
            if (is_object($file)) $file = get_object_vars($file);
            $url = url("/view-file/" . $file['stream_id']);
            $type = strtolower($file['file_type']);
            $e = explode('!~!', $file['name']);
            $filename = end($e);
            $data['title'] = $filename;
            switch ($type) {
                case 'pdf':
                    $data['viewfile'] = '<embed width="100%" height="100%" src="' . $url . '" type="application/pdf">';

                    return view('file.open_pdf', $data);
                    break;
                case 'png':
                case 'jpg':
                case 'jpeg':
                case 'gif':
                    return redirect($url);
                    break;
                case 'webp':
                    $content = file_get_contents($url, false, $this->context);
                    $size    = getimagesizefromstring($content);
                    $data['viewfile'] = '<img src="' . $url . '" alt="' . $filename . '" style="width: ' . $size[0] . '; height: ' . $size[1] . '; max-width: 100%; max-height: 100%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">';

                    return view('file.open_img', $data);
                    break;
                case 'mov':
                case 'mp4':
                case 'ogg':
                case 'webm':
                    $data['viewfile'] = '<video width="100%" height="99%" controls autoplay>
                                            <source src="' . $url . '" type="video/mp4">
                                            <source src="movie.ogg" type="video/ogg">
                                            Your browser does not support the video tag.
                                        </video>';

                    return view('file.open_img', $data);
                    break;

                default:
                    // $data['viewfile'] = '<div style="height: 100%; text-align: center;">
                    //                         <h1 style="padding-top: 30px">Định dạng file chưa hỗ trợ xem trên trình duyệt</h1>
                    //                         <span style="font-size: 20px;" data-name="' . $file['name'] . '"> Tệp: <span style="font-style: italic;">' . $filename . '</span></span>
                    //                         <br/>
                    //                         <h2>
                    //                         <a href="' . $url . '" style="cursor: pointer; text-decoration: none">
                    //                             <img src="' . asset('public/images/download_96x96.png') . '" style="width: 24px; vertical-align: bottom;">
                    //                             Tải xuống
                    //                         </a>
                    //                         </h2>
                    //                     </div>';
                    $data['viewfile'] = '<iframe width="1" height="1" frameborder="0" src="' . $url . '"></iframe>
                                        <script>
                                            setTimeout(function () {
                                                window.close();
                                            }, 1500);
                                        </script>';
                    return view('file.open_pdf', $data);
                    break;
            }
        } catch (\Exception $e) {
            $this->logger->setChannel('getViewFile')
                ->log('Exception', [$e]);

            return '';
        }
    }

    /**
     * Xem file
     */
    public function viewFile(string $streamId)
    {
        try {
            $sql = "SELECT TOP 1 [name], [file_stream], [file_type], [cached_file_size]
                FROM dbo.FileTableTb
                WHERE convert(varchar(50), stream_id) = ?";
            $query = $this->pdo->prepare($sql);
            $query->execute([$streamId]);
            // $query->bindColumn('file_stream', $lob, PDO::PARAM_LOB);
            $result = $query->fetchAll(PDO::FETCH_OBJ);
            if (empty($result) || is_null($result)) {
                return response('<div style="height: 100%; text-align: center;">
                    <h1 style="padding-top: 30px">Tệp không tồn tại</h1>
                </div>', 404);
            }
            $mime = Library::mimeContentType($result[0]->file_type);
            $n = explode('!~!', $result[0]->name);
            $name = end($n);
            // header('Content-Type: ' . $mime);
            // fpassthru($lob);
            // exit;
            // return response(stream_get_contents($lob), 200, [
            return response($result[0]->file_stream, 200, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'inline; filename="' . $name . '"',
            ]);
        } catch (\Exception $e) {
            $this->logger->setChannel('viewFile')
                ->log('Exception', [
                    'stream_id' => $streamId, $e
                ]);

            return response('<div style="height: 100%; text-align: center;">
                <h1 style="padding-top: 30px">Error in retrieving data.</h1>
            </div>');
        }
    }
}
