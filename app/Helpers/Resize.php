<?php
namespace App\Helpers;

use App\Models\User;

class Resize
{
    protected static $sizes = [
        'mainProduct'     => [
            'recipe'    => 'main',
            'dir'       => 'uploads/products',
            'width'     => 1140,
            'height'    => 1140,
            'method'    => 'resize',
            'watermark' => true,
            'bnw' => false,
        ],
        'featuredProduct' => [
            'recipe'    => 'featured',
            'dir'       => 'uploads/products',
            'width'     => 280,
            'height'    => 280,
            'method'    => 'fit',
            'watermark' => true,
            'bnw' => false,
        ],
        'featuredProduct2' => [
            'recipe'    => 'featured2',
            'dir'       => 'uploads/products',
            'width'     => 280,
            'height'    => 280,
            'method'    => 'fit',
            'watermark' => true,
            'bnw' => true,
        ],
        'sidebarProduct'  => [
            'recipe'    => 'sidebar',
            'dir'       => 'uploads/products',
            'width'     => 80,
            'height'    => 80,
            'method'    => 'fit',
            'watermark' => false,
            'bnw' => false,
        ],
        'coverProduct'    => [
            'recipe'    => 'cover',
            'dir'       => 'uploads/products',
            'width'     => 1920,
            'height'    => '1080',
            'method'    => 'resize',
            'watermark' => true,
            'bnw' => false,
        ],
        'listingProduct'  => [
            'recipe'    => 'listing',
            'dir'       => 'uploads/products',
            'width'     => 117,
            'height'    => 117,
            'method'    => 'fit',
            'watermark' => false,
            'bnw' => false,
        ],
        'avatar'        => [
            'recipe'    => 'avatar',
            'dir'       => 'uploads/avatars',
            'width'     => 80,
            'height'    => 80,
            'method'    => 'fit',
            'watermark' => false,
            'bnw' => false,
        ],
        'mainAvatar'    => [
            'recipe'    => 'main',
            'dir'       => 'uploads/avatars',
            'width'     => 263,
            'height'    => 263,
            'method'    => 'fit',
            'watermark' => false,
            'bnw' => false,
        ],
        'listingAvatar' => [
            'recipe'    => 'listing',
            'dir'       => 'uploads/avatars',
            'width'     => 117,
            'height'    => 117,
            'method'    => 'fit',
            'watermark' => false,
            'bnw' => false,
        ],
    ];


    public function __construct($file = null, $recipe = null)
    {

        $obj_recipe = self::$sizes[$recipe];

        if ($file=='') {
            $this->file = 'default.png';
            $this->dir = $obj_recipe['dir']; // 'uploads/assets';
        } else {
            $this->file = $file;
            $this->dir = $obj_recipe['dir'];
        }
        $this->width = $obj_recipe['width'];
        $this->height = $obj_recipe['height'];
        $this->method = $obj_recipe['method'];
        $this->watermark = $obj_recipe['watermark'];
        $this->bnw = $obj_recipe['bnw'];

        $this->recipe = $obj_recipe['recipe']; // reb para transportar el pedido de imagen hsta el archivo en cache
    }


    public static function getSizes()
    {
        return self::$sizes;
    }



    protected function process()
    {

        if ($this->file instanceof User) {
            if ($this->file->avatar == null || $this->file->avatar == 'user') {
                return $this->file = 'uploads/avatars/default.png';
            }
        }

        $resize = new ResizeHelper($this->file, $this->dir, $this->recipe, $this->width, $this->height, $this->method, $this->watermark, $this->bnw);

        return $resize->resize();
    }

    // public static function image($image, $recipe = null)
    // {
    //     $image = new Resize(sprintf('%s.%s', $image->image_name, $image->type), $recipe);
    //     return $image->process();
    // }

    // public static function avatar($user, $recipe = null)
    // {
    //     if ($user->avatar == null || $user->avatar == 'user') {
    //         $avatar = new Resize($user, $recipe);

    //         return $avatar->process();
    //     }
    //     $image = new Resize($user->avatar, $recipe);

    //     return $image->process();
    // }

    public static function img($name, $recipe = null)
    {
        $image = new Resize($name, $recipe);
        return $image->process();
    }

    public function __call($func, $args)
    {
        $reflection = new \ReflectionClass(get_class($this));
        $methodName = '_' . $func;

        if ($reflection->hasMethod($methodName)) {
            $method = $reflection->getMethod($methodName);

            if ($method->getNumberOfRequiredParameters() > count($args)) {
                throw new \InvalidArgumentException('Not enough arguments given for ' . $func);
            }
            call_user_func_array([$this, $methodName], $args);

            return $this;
        }

        throw new \BadFunctionCallException('Invalid method: ' . $func);
    }


    protected function _size($width = null, $height = null)
    {
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    protected function _dir($dir = null)
    {
        $this->dir = $dir;

        return $this;
    }

    protected function _watermark()
    {
        $this->watermark = true;

        return $this;
    }
}
