<?php
/**
 * @author Abhimanyu Sharma <abhimanyusharma003@gmail.com>
 */

namespace App\Http\Requests\Comment;

use App\Http\Requests\Request;

class CreateReply extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'textcontent' => ['required', 'min:2'],
            'reply_msgid' => ['required', 'exists:comments,id']
        ];
    }
}
