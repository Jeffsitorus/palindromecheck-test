<?php

namespace App\Http\Request;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

abstract class FormRequest extends Request
{
    public function validate()
    {
        if (false === $this->authorize()) {
            throw new UnauthorizedException();
        }

        $validator = app('validator')->make($this->all(), $this->rules(), $this->messages(), $this->attributes());

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function resolveUser()
    {
        if (method_exists($this, 'setUserResolver')) {
            $this->setUserResolver(function () {
                return Auth::user();
            });
        }
    }

    protected function authorize()
    {
        return true;
    }

    abstract protected function rules();
    // abstract protected function attributes();

    protected function messages()
    {
        return [];
    }

    protected function attributes()
    {
        return [];
    }
}
