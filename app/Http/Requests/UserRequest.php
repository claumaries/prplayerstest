<?php

namespace App\Http\Requests;

use App\Services\UserServiceInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @throws BindingResolutionException
     */
    public function rules(): array
    {
        // Get the id from the route and make sure it is int if exists
        $id = $this->route('id');
        $id = !empty($id) ? intval($id) : null;

        return $this->container
            ->make(UserServiceInterface::class)
            ->rules($id);
    }
}
