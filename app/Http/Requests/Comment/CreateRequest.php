<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Http\Requests\Comment;

use App\Repository\ProductRepositoryInterface;
use App\Http\Requests\Request;

class CreateRequest extends Request
{

    public function authorize(ProductRepositoryInterface $product)
    {
        $product = $product->getById($this->route('id'))->first();
        if (!$product) {
            return false;
        }
        return auth()->check();
    }

    public function rules()
    {
        return [
            'comment' => ['required', 'min:2']
        ];
    }

    public function forbiddenResponse()
    {
        return redirect()->route('login')->with('flashError', t('You need to Login first'));
    }
}
