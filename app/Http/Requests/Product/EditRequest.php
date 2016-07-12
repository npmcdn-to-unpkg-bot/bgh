<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */
namespace App\Http\Requests\Product;

use App\Repository\ProductRepositoryInterface;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class EditRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(ProductRepositoryInterface $product)
    {
        if ( ! auth()->check()) {
            return false;
        }

        $product = $product->getById($this->route('id'));

        if ( ! $product || auth()->user()->id !== $product->user_id) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET': {
                return [];
            }
            case 'POST': {
                return [
                    'title'    => ['required', 'max:200'],
                    'category' => ['required', 'exists:categories,id']
                ];
            }
        }
    }

    public function forbiddenResponse()
    {
        return redirect()->route('gallery')->with('flashError', t('You are not allowed'));
    }
}
