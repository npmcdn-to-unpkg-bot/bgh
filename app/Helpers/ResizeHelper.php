<?php
namespace App\Helpers;

// use App\Models\Image;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\NamespacedItemResolver;
use Intervention\Image\Facades\Image as ImageResize;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
class ResizeHelper extends NamespacedItemResolver
{
    protected $cacheDir = 'cache';

    private $source;
    private $width;
    private $height;
    private $type;

    public function __construct($name, $dirName = null, $width = null, $height = null, $type = 'fit', $watermark = false)
    {
        $this->name = $name;
        $this->source = sprintf('%s/%s', $dirName, $name);
        $this->dirName = sprintf('%s', $dirName);
        $this->width = $width;
        $this->height = $height;
        $this->type = $type;
        $this->watermark = $watermark;
        $this->key = $this->createKey($this->source, "$type" . $this->width . $this->height);
    }

    protected function createKey($source, $fingerprint = null, $prefix = 'cached', $suffix = 'file')
    {
        return sprintf(
            '%s.%s%s%s',
            substr(hash('sha1', $source), 0, 8),
            $prefix,
            $this->pad($source, $source . '/' . $fingerprint),
            $this->pad($source, $suffix, 3)
        );
    }

    protected function pad($src, $pad, $len = 16)
    {
        return substr(hash('sha1', sprintf('%s%s', $src, $pad)), 0, $len);
    }

    public function resize()
    {
        // REB
        if ($url = $this->checkIfCacheExits()) {
            return $url;
        }
        $this->createCache();

        // $img = new \stdClass();
        // $img->url = $this->url();
        // list($w, $h) = getimagesize(public_path() . $this->getCachedFileAbsolutePath());
        // $img->width = $w;
        // $img->height = $h;

        // return $img;
        return $this->url();
    }

    protected function checkIfCacheExits()
    {
        if (config('filesystems.default') == 'local') {
            $this->createCache();

            return $this->url();
        }
        if (Cache::store('image')->has($this->key)) {
            return Cache::store('image')->get($this->key);
        }

        return Cache::store('image')->rememberForever($this->key, function () {
            $this->createCache();

            return $this->url();
        });
    }

    protected function createCache()
    {
        if (Storage::exists($this->getCachedFileAbsolutePath()) == false) {
            if (Storage::exists($this->absoluteSoucePath())) {
                $this->createCacheDir();
                $content = Storage::read($this->absoluteSoucePath());
            } else {
                $content = Storage::read($this->basePath() . '/uploads/assets/user.jpeg');
            }
            list($image, $filename) = $this->doResize($content);
            Storage::put($filename, (string)$image, 'public');
        }
    }

    protected function getCachedFileAbsolutePath()
    {
        $parsed = $this->parseKey($this->key);
        array_shift($parsed);
        list($dir, $file) = $parsed;
        $mime = substr($this->source, strrpos($this->source, '.') + 1);

        return sprintf('%s/%s/%s/%s.%s', $this->basePath(), 'cache', $dir, $file, $mime);
    }

    private function basePath()
    {
        if (config('filesystems.default') == 'dropbox') {
            return 'Public/dropaccount';
            // return 'Public/artvenue';
        }
    }

    private function absoluteSoucePath()
    {
        return sprintf('%s/%s', $this->basePath(), $this->source);
    }

    protected function createCacheDir()
    {
        $path = $this->getCacheDir($this->key);

        if (Storage::exists($path)) {
            return $path;
        }
        Storage::makeDirectory($path);

        return $path;
    }

    protected function getCacheDir()
    {
        $parsed = $this->parseKey($this->key);
        array_shift($parsed);
        list($dir) = $parsed;

        return sprintf('%s/%s/%s', $this->basePath(), 'cache', $dir);
    }

    protected function doResize($content)
    {
        if ($this->type == 'resize') {
            $image = ImageResize::make($content)->resize($this->width, $this->height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } else {
            $image = ImageResize::make($content)->fit($this->width, $this->height);
        }
        if ($this->watermark == true && env('WATERMARK') == true) {
            $image->insert('uploads/assets/watermark.png', env('WATERMARK_POSITION', 'bottom-right'), 10, 10);
        }
        $image->interlace();
        $image = (string)$image->encode($this->resolveMime($image), 100);
        $filename = $this->getCachedFileAbsolutePath();

        return [$image, $filename];
    }

    private function resolveMime($image)
    {
        if ($image instanceof UploadedFile) {
            $mime = str_replace('image/', '', $image->getMimeType());
        } else {
            $mime = str_replace('image/', '', $image->mime);
        }
        if ($mime == 'jpg') {
            return 'jpeg';
        }

        return $mime;
    }

    protected function url()
    {
        if (config('filesystems.default') == 'local') {
            return asset($this->getCachedFileAbsolutePath());
        }

        if (config('filesystems.default') == 's3') {
            if (config('filesystems.disks.s3.distribution_url')) {
                return sprintf('//%s%s', config('filesystems.disks.s3.distribution_url'),
                    $this->getCachedFileAbsolutePath());
            }

            return sprintf('//%s.s3.amazonaws.com%s', config('filesystems.disks.s3.bucket'),
                $this->getCachedFileAbsolutePath());
        }

        if (config('filesystems.default') == 'dropbox') {
            return sprintf('//dl.dropboxusercontent.com/u/%s/%s',
                config('filesystems.disks.dropbox.userId'),
                str_replace('Public', '', $this->getCachedFileAbsolutePath()));
        }

        if (config('filesystems.default') == 'copy') {
            $link = Storage::getDriver()->getAdapter()->getClient()->createLink($this->getCachedFileAbsolutePath());

            return sprintf('%s/%s', $link->url, $link->name);
        }
    }

    public function saveOriginal()
    {
        $filename = $this->newFileName();
        $mime = $this->resolveMime($this->name);
        Storage::put(sprintf('%s/%s/%s.%s', $this->basePath(), $this->dirName, $filename, $mime),
            file_get_contents($this->name));

        return [$filename, $mime];
    }

    private function newFileName()
    {

        // var_dump('newFileName');

        $str = str_random(9);
        if (Image::select('image_name')->whereImageName($str)->first()) {
            $str = $this->dirName();
        }

        return $str;
    }

    /**
     * Delete original image and it's thumbnails as well as it's cache keys.
     *
     * @return bool
     */
    public function delete()
    {
        if (Storage::exists($this->basePath() . '/' . $this->source)) {
            Storage::delete($this->basePath() . '/' . $this->source);
        }
        if (Storage::exists($this->getCacheDir())) {
            Storage::deleteDirectory($this->getCacheDir());
        }
        $this->clearCacheKeys();

        return true;
    }

    /**
     * Clear all the cache keys of image from `storage/app/images` folder
     */
    public function clearCacheKeys()
    {
        $sizes = Resize::getSizes();
        foreach ($sizes as $size) {
            $key = $this->createKey($this->source, $size['method'] . $size['width'] . $size['height']);
            Cache::store('image')->forget($key);
        }
    }

    /**
     * Clear all the cached thumbnails of image
     *
     * @return bool
     */
    public function clearCache()
    {
        if (Storage::exists($this->getCacheDir())) {
            Storage::deleteDirectory($this->getCacheDir());
        }
        $this->clearCacheKeys();

        return true;
    }

    /**
     * Generate image for download
     *
     * @return string
     */
    public function download()
    {
        $file = Storage::read($this->absoluteSoucePath());
        $image = ImageResize::make($file);
        $mime = substr($this->source, strrpos($this->source, '.') + 1);
        $mime = ($mime == 'jpeg' ? 'jpg' : $mime);
        if (env('WATERMARK') == true) {
            $image->insert('uploads/assets/watermark.png', env('WATERMARK_POSITION', 'bottom-right'), 10, 10);
        }
        $filename = sprintf('%s/%s.%s', 'cache', str_random(), $mime);
        Storage::drive('local')->put($filename, (string)$image->encode($mime, 100), 'public');

        return $filename;
    }
}
