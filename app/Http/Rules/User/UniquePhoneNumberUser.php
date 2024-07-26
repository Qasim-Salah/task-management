<?php

namespace App\Http\Rules\User;

use App\Models\User as UserModel;
use Illuminate\Contracts\Validation\Rule;
use function App\Rules\User\filter_phone;

class UniquePhoneNumberUser implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        if ($this->params['key'] == 'store') {
            $phone = UserModel::where('phone_number', filter_phone($value))->exists();
            if ($phone === false) {
                return true;
            }
        } elseif ($this->params['key'] == 'update') {
            $phone = UserModel::where('phone_number', filter_phone($value))->exists();
            if ($phone === false) {
                return true;

            } else {
                $check = UserModel::where('phone_number', filter_phone($value))->first();
                if ($check) {
                    if ($check->id == $this->params['value']) {
                        return true;
                    }
                }
            }

        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */

    public function message()
    {
        if (app()->getLocale('en')) {
            return 'The value of the phone number used.';
        } else {
            return 'قيمة رقم الهاتف مُستخدم.';
        }
    }
}
