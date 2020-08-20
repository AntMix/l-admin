<?php

namespace App\Http\Controllers\Admin;


class File extends Base
{
    public $imageMaxSize = 1024 * 2;

    public function upload($file, $path, $validate)
    {
        $path = '/uploads/' .  $path . '/' . date('Y') . '/' . date('m');
        $validator = validator(['file' => $file], $validate);
        $error = $validator->errors();
        $info = $error->first('file');
        $extension = $file->extension();
        if (!$info) {
            $fileName = md5(microtime()) . '.' . $extension;
            $url = '/storage/' . $file->storeAs($path, $fileName, 'public');
            return $this->success(['url' => $url]);
        } else {
            return $this->error($info);
        }
    }

    public function image()
    {
        $file = $this->request->file('file');
        if ($file) {
            return $this->upload($file, 'image', ['file' => 'file|image|max:' . $this->imageMaxSize]);
        }
        $this->error('文件为空');
    }
}
