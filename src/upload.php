<?php

namespace fab;

class upload
{
    public static function uploader($fileNameWithoutExt = null, $destFolderRelativeToUploadDir = "files", $filekey = 'file', $overwrite = true)
    {
        $result = [];
        $_full_mkdir_p_resp = self::_full_mkdir_p($destFolderRelativeToUploadDir);
        if ($_full_mkdir_p_resp['code'] == 'error') {
            $result["code"] = 'error';
            $result["message"] = $_full_mkdir_p_resp['message'];
            return $result;
        } else {
            $dest_upload_dir = $_full_mkdir_p_resp['dest_upload_dir'];
            $base_upload_dir = $_full_mkdir_p_resp['base_upload_dir'];
            $base_upload_url = $_full_mkdir_p_resp['base_upload_url'];
        }

        $uploadfile = join(DIRECTORY_SEPARATOR, array($dest_upload_dir, basename($_FILES[$filekey]['name'])));

        if (move_uploaded_file($_FILES[$filekey]['tmp_name'], $uploadfile)) {
            if ($fileNameWithoutExt === null) {
                $basename = basename($_FILES[$filekey]['name']);
            } else {
                $basename = self::rename_file(basename($_FILES[$filekey]['name']), $fileNameWithoutExt, $dest_upload_dir, $dest_upload_dir, $overwrite);
            }
            $result['code'] = 'ok';
            $result['data'] = [
                'upload_url' => join(DIRECTORY_SEPARATOR, array($base_upload_url, $destFolderRelativeToUploadDir)),
                'upload_dir' => $dest_upload_dir,
                'basename' => $basename,
            ];
            return $result;
        } else {
            $result["code"] = 'error';
            $result['message'] = 'error move_uploaded_file';
            return $result;
        }
    }

    private static function _full_mkdir_p($destFolderRelativeToUploadDir)
    {
        if (!function_exists('wp_upload_dir')) {
            return [
                'code' => 'error',
                'message' => 'wp_upload_dir non esiste'
            ];
        }
        $upload_dir = \wp_upload_dir();
        $base_upload_dir = $upload_dir['basedir'];
        $base_upload_url = $upload_dir['baseurl'];

        // DIR assoluta di destinazione
        $dest_upload_dir = join(DIRECTORY_SEPARATOR, array($base_upload_dir, $destFolderRelativeToUploadDir));
        if (!function_exists('wp_mkdir_p')) {
            return [
                'code' => 'error',
                'message' => 'wp_mkdir_p non esiste'
            ];
        }
        if (\wp_mkdir_p($dest_upload_dir) === true) {
            // OK
        } else {
            return [
                'code' => 'error',
                'message' => $dest_upload_dir . " non esiste!",
            ];
        }
        return [
            'code' => 'ok',
            'dest_upload_dir' => $dest_upload_dir,
            'base_upload_dir' => $base_upload_dir,
            'base_upload_url' => $base_upload_url,
        ];
    }
    public static function fineuploader($fileNameWithoutExt = null, $destFolderRelativeToUploadDir = "files", $overwrite = true, $allowedExtensions = array())
    {
        // Include the upload handler class
        require_once(dirname(__FILE__) . '/fineuploader/UploadHandler.php');

        if (class_exists('\fab\fineuploader\UploadHandler')) {
            $uploader = new \fab\fineuploader\UploadHandler();

            // Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
            $uploader->allowedExtensions = $allowedExtensions; // all files types allowed by default

            // Specify max file size in bytes.
            $uploader->sizeLimit = null;

            // Specify the input name set in the javascript.
            $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default

            $_full_mkdir_p_resp = self::_full_mkdir_p($destFolderRelativeToUploadDir);
            if ($_full_mkdir_p_resp['code'] == 'error') {
                $result["error"] = $_full_mkdir_p_resp['message'];
                return $result;
            } else {
                $dest_upload_dir = $_full_mkdir_p_resp['dest_upload_dir'];
                $base_upload_dir = $_full_mkdir_p_resp['base_upload_dir'];
                $base_upload_url = $_full_mkdir_p_resp['base_upload_url'];
            }

            $uploader->chunksFolder = join(DIRECTORY_SEPARATOR, array($base_upload_dir, "chunks"));

            $_full_mkdir_p_resp = self::_full_mkdir_p($uploader->chunksFolder);
            if ($_full_mkdir_p_resp['code'] == 'error') {
                $result["error"] = $_full_mkdir_p_resp['message'];
                return $result;
            }

            $method = $_SERVER["REQUEST_METHOD"];
            if ($method == "POST") {
                header("Content-Type: text/plain");

                // Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
                // For example: /myserver/handlers/endpoint.php?done
                if (isset($_GET["done"])) {
                    $result = $uploader->combineChunks($base_upload_dir);

                    $result["uploadName"] = $uploader->getUploadName();
                }
                // Handles upload requests
                else {
                    // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
                    $result = $uploader->handleUpload($base_upload_dir);

                    // To return a name used for uploaded file you can use the following line.
                    $result["uploadName"] = $uploader->getUploadName();
                }

                // FAB sposta e rinomina file
                if (is_file(join(DIRECTORY_SEPARATOR, array($base_upload_dir, $result["uuid"], $result["uploadName"])))) {

                    if ($dest_upload_dir === null && $fileNameWithoutExt === null) {
                    } else {
                        $currentPath = join(DIRECTORY_SEPARATOR, array($base_upload_dir, $result["uuid"]));
                        if ($dest_upload_dir === null) {
                            // catella di dest uguale a quella di origine
                            $dest_upload_dir = $currentPath;
                        }


                        $fileNameWithExt = self::rename_file($result["uploadName"], $fileNameWithoutExt, $currentPath, $dest_upload_dir, $overwrite);
                        if ($fileNameWithExt) {
                            if ($dest_upload_dir != $currentPath) {
                                // elimina la cartella originale
                                rmdir(join(DIRECTORY_SEPARATOR, array($base_upload_dir, $result["uuid"])));
                            }
                            $result["uploadName"] = $fileNameWithExt;
                            $result["dir_url"] = join(DIRECTORY_SEPARATOR, array($base_upload_url, $destFolderRelativeToUploadDir));
                            $result["dir_path"] = join(DIRECTORY_SEPARATOR, array($base_upload_dir, $destFolderRelativeToUploadDir));
                        } else {
                            $result["error"] = $dest_upload_dir . " non esiste!";
                        }
                    }
                }

                return $result;
            }
            // for delete file requests
            else if ($method == "DELETE") {
                $result = $uploader->handleDelete("files");
                return $result;
            } else {
                return false;
                header("HTTP/1.0 405 Method Not Allowed");
            }
        } else {
            if (class_exists('WP_Error') and function_exists('__')) {
                return new \WP_Error('broke', __('Classe non trovata in ' . dirname(__FILE__) . '/fineuploader/UploadHandler.php', 'UploadHandler'));
            } else {
                return 'Classe non trovata in ' . dirname(__FILE__) . '/fineuploader/UploadHandler.php';
            }
        }
    }

    public static function rename_file($oldFileName, $newFileNameWithoutExt, $dirOriginPath, $dirDestPath, $overwrite = true)
    {
        if ($newFileNameWithoutExt === null) {
            // nome originale del file
            $fileNameWithExt = $oldFileName;
        } else {
            // nome con estensione originale del file
            $ext = pathinfo($oldFileName, PATHINFO_EXTENSION);
            $fileNameWithExt = $newFileNameWithoutExt . '.' . $ext;
        }
        if (!function_exists('sanitize_file_name')) return 'sanitize_file_name non esiste';
        $fileNameWithExt = \sanitize_file_name($fileNameWithExt);
        if (is_dir($dirDestPath)) {
            $old = join(DIRECTORY_SEPARATOR, array($dirOriginPath, $oldFileName));
            $new = join(DIRECTORY_SEPARATOR, array($dirDestPath, $fileNameWithExt));
            if ($overwrite == false) {
                $fileNameWithExt = self::get_unique_filename($fileNameWithExt, $dirDestPath);
                $new = join(DIRECTORY_SEPARATOR, array($dirDestPath, $fileNameWithExt));
            }
            rename($old, $new);
            return $fileNameWithExt;
        }
        return false;
    }


    public static function get_unique_filename($fileNameWithExt, $dirPath)
    {
        $new = join(DIRECTORY_SEPARATOR, array($dirPath, $fileNameWithExt));
        $fileNameWithoutExt = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        $ext = pathinfo($fileNameWithExt, PATHINFO_EXTENSION);
        $n = 1;
        // cerco un nome di file univoco esempio: filename3.png
        while (is_file($new)) {
            $fileNameWithExt = $fileNameWithoutExt . $n . '.' . $ext;
            $new = join(DIRECTORY_SEPARATOR, array($dirPath, $fileNameWithExt));
            $n++;
        }
        return $fileNameWithExt;
    }

    public static function n2_fineuploader($fileNameWithoutExt = null, $destFolderPath = null, $overwrite = true, $allowedExtensions = array())
    {
        // Include the upload handler class
        require_once(dirname(__FILE__) . '/fineuploader/UploadHandler.php');

        if (class_exists('\fab\fineuploader\UploadHandler')) {
            $uploader = new \fab\fineuploader\UploadHandler();

            // Specify the list of valid extensions, ex. array("jpeg", "xml", "bmp")
            $uploader->allowedExtensions = $allowedExtensions; // all files types allowed by default

            // Specify max file size in bytes.
            $uploader->sizeLimit = null;

            // Specify the input name set in the javascript.
            $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default

            // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
            $baseFolderPath = "files";
            $uploader->chunksFolder = join(DIRECTORY_SEPARATOR, array($baseFolderPath, "chunks"));

            $method = $_SERVER["REQUEST_METHOD"];
            if ($method == "POST") {
                header("Content-Type: text/plain");

                // Assumes you have a chunking.success.endpoint set to point here with a query parameter of "done".
                // For example: /myserver/handlers/endpoint.php?done
                if (isset($_GET["done"])) {
                    $result = $uploader->combineChunks($baseFolderPath);

                    $result["uploadName"] = $uploader->getUploadName();
                }
                // Handles upload requests
                else {
                    // Call handleUpload() with the name of the folder, relative to PHP's getcwd()
                    $result = $uploader->handleUpload($baseFolderPath);

                    // To return a name used for uploaded file you can use the following line.
                    $result["uploadName"] = $uploader->getUploadName();
                }

                // FAB sposta e rinomina file
                if (is_file(join(DIRECTORY_SEPARATOR, array($baseFolderPath, $result["uuid"], $result["uploadName"])))) {

                    if ($destFolderPath === null && $fileNameWithoutExt === null) {
                    } else {
                        $currentPath = join(DIRECTORY_SEPARATOR, array($baseFolderPath, $result["uuid"]));
                        if ($destFolderPath === null) {
                            // catella di dest uguale a quella di origine
                            $destFolderPath = $currentPath;
                        }
                        if ($fileNameWithoutExt === null) {
                            // nome originale del file
                            $fileNameWithExt = $result["uploadName"];
                        } else {
                            // nome con estensione originale del file
                            $ext = pathinfo($result["uploadName"], PATHINFO_EXTENSION);
                            $fileNameWithExt = $fileNameWithoutExt . '.' . $ext;
                        }
                        // crea il percorso di dest se nn esiste
                        if (!is_dir($destFolderPath)) {
                            $splitPath = preg_split('/\//', $destFolderPath);
                            $path = '';
                            foreach ($splitPath as $folder) {
                                if ($folder === '') continue;
                                $path .= $folder . '/';
                                if (!is_dir($path)) {
                                    @mkdir($path, 0777);
                                }
                            }
                        }
                        if (is_dir($destFolderPath)) {
                            $old = join(DIRECTORY_SEPARATOR, array($currentPath, $result["uploadName"]));
                            $new = join(DIRECTORY_SEPARATOR, array($destFolderPath, $fileNameWithExt));
                            if ($overwrite == false) {
                                $fileNameWithoutExt = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                                $ext = pathinfo($fileNameWithExt, PATHINFO_EXTENSION);
                                $n = 1;
                                // cerco un nome di file univoco esempio: filename3.png
                                while (is_file($new)) {
                                    $fileNameWithExt = $fileNameWithoutExt . $n . '.' . $ext;
                                    $new = join(DIRECTORY_SEPARATOR, array($destFolderPath, $fileNameWithExt));
                                    $n++;
                                }
                            }
                            // sposta il file nella cartella di dest rinominandolo
                            rename($old, $new);
                            if ($destFolderPath != $currentPath) {
                                // elimina la cartella originale
                                rmdir(join(DIRECTORY_SEPARATOR, array($baseFolderPath, $result["uuid"])));
                            }
                            $result["uploadName"] = $fileNameWithExt;
                            $result["folder"] = $destFolderPath;
                        } else {
                            $result["error"] = $destFolderPath . " non esiste!";
                        }
                    }
                }

                return $result;
            }
            // for delete file requests
            else if ($method == "DELETE") {
                $result = $uploader->handleDelete("files");
                return $result;
            } else {
                return false;
                header("HTTP/1.0 405 Method Not Allowed");
            }
        } else {
            return 'Classe non trovata in ' . dirname(__FILE__) . '/fineuploader/UploadHandler.php';
        }
    }
}
