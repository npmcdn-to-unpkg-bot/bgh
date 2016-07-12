<?php
namespace App\Helpers;

use App\Models\User;

class Resize
{
    protected static $sizes = [
        'mainProduct'     => [
            'dir'       => 'uploads/products',
            'width'     => 1140,
            'height'    => 1140,
            'method'    => 'resize',
            'watermark' => true,
        ],
        'featuredProduct' => [
            'dir'       => 'uploads/products',
            'width'     => 280,
            'height'    => 280,
            'method'    => 'fit',
            'watermark' => false,
        ],
        'sidebarProduct'  => [
            'dir'       => 'uploads/products',
            'width'     => 80,
            'height'    => 80,
            'method'    => 'fit',
            'watermark' => false,
        ],
        'coverProduct'    => [
            'dir'       => 'uploads/products',
            'width'     => 1920,
            'height'    => '1080',
            'method'    => 'resize',
            'watermark' => true,
        ],
        'listingProduct'  => [
            'dir'       => 'uploads/products',
            'width'     => 117,
            'height'    => 117,
            'method'    => 'fit',
            'watermark' => false,
        ],
        'avatar'        => [
            'dir'       => 'uploads/avatars',
            'width'     => 80,
            'height'    => 80,
            'method'    => 'fit',
            'watermark' => false,
        ],
        'mainAvatar'    => [
            'dir'       => 'uploads/avatars',
            'width'     => 263,
            'height'    => 263,
            'method'    => 'fit',
            'watermark' => false,
        ],
        'listingAvatar' => [
            'dir'       => 'uploads/avatars',
            'width'     => 117,
            'height'    => 117,
            'method'    => 'fit',
            'watermark' => false,
        ],
    ];


    public function __construct($file = null, $recipe = 'avatar')
    {
        $this->_setImage($file, $recipe);
    }

    protected function _setImage($file, $recipe = null)
    {

        $recipe = self::$sizes[$recipe];

        if ($file=='') {
            $this->file = 'placeholder.png';
            $this->dir = 'uploads/assets';
        } else {
            $this->file = $file;
            $this->dir = $recipe['dir'];
        }
        $this->width = $recipe['width'];
        $this->height = $recipe['height'];
        $this->method = $recipe['method'];
        $this->watermark = $recipe['watermark'];
    }


    public static function getSizes()
    {
        return self::$sizes;
    }

    public static function avatar($user, $recipe = null)
    {
        if ($user->avatar == null || $user->avatar == 'user') {
            $avatar = new Resize($user, $recipe);

            return $avatar->process();
        }
        $image = new Resize($user->avatar, $recipe);

        return $image->process();
    }

    protected function process()
    {

        // REB tengo que volar todo esto
        if ($this->file instanceof User) {
            if ($this->file->avatar == null || $this->file->avatar == 'user') {
                return get_gravatar($this->file->email, $this->width);
            }
        }

        $resize = new ResizeHelper($this->file, $this->dir, $this->width, $this->height, $this->method, $this->watermark);

        return $resize->resize();
    }

    public static function image($image, $recipe = null)
    {

        $image = new Resize(sprintf('%s.%s', $image->image_name, $image->type), $recipe);

        return $image->process();
    }

    public static function img($name, $recipe = null)
    {
        // var_dump($name);

        // $image = new Resize(sprintf('%s.%s', $image->image_name, $image->type), $recipe);
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
