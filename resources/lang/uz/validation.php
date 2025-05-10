<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute qabul qilinishi kerak.',
    'accepted_if' => ':other :value bo\'lganda, :attribute qabul qilinishi kerak.',
    'active_url' => ':attribute haqiqiy URL bo\'lishi kerak.',
    'after' => ':attribute :date dan keyingi sana bo\'lishi kerak.',
    'after_or_equal' => ':attribute :date ga teng yoki undan keyingi sana bo\'lishi kerak.',
    'alpha' => ':attribute faqat harflardan iborat bo\'lishi kerak.',
    'alpha_dash' => ':attribute faqat harflar, raqamlar, chiziqchalar va pastki chiziqlardan iborat bo\'lishi kerak.',
    'alpha_num' => ':attribute faqat harflar va raqamlardan iborat bo\'lishi kerak.',
    'array' => ':attribute massiv bo\'lishi kerak.',
    'before' => ':attribute :date dan oldingi sana bo\'lishi kerak.',
    'before_or_equal' => ':attribute :date ga teng yoki undan oldingi sana bo\'lishi kerak.',
    'between' => [
        'numeric' => ':attribute :min va :max orasida bo\'lishi kerak.',
        'file' => ':attribute :min va :max kilobayt orasida bo\'lishi kerak.',
        'string' => ':attribute :min va :max belgilar orasida bo\'lishi kerak.',
        'array' => ':attribute :min va :max elementlar orasida bo\'lishi kerak.',
    ],
    'boolean' => ':attribute maydoni faqat rost yoki yolg\'on qiymatga ega bo\'lishi kerak.',
    'confirmed' => ':attribute tasdiqlanmadi.',
    'current_password' => 'Parol noto\'g\'ri.',
    'date' => ':attribute haqiqiy sana emas.',
    'date_equals' => ':attribute :date ga teng sana bo\'lishi kerak.',
    'date_format' => ':attribute :format formatiga mos kelmaydi.',
    'declined' => ':attribute rad etilishi kerak.',
    'declined_if' => ':other :value bo\'lganda, :attribute rad etilishi kerak.',
    'different' => ':attribute va :other bir-biridan farq qilishi kerak.',
    'digits' => ':attribute :digits raqamdan iborat bo\'lishi kerak.',
    'digits_between' => ':attribute :min va :max raqamlar orasida bo\'lishi kerak.',
    'dimensions' => ':attribute noto\'g\'ri rasm o\'lchamlariga ega.',
    'distinct' => ':attribute maydoni takrorlanuvchi qiymatga ega.',
    'email' => ':attribute haqiqiy email manzil bo\'lishi kerak.',
    'ends_with' => ':attribute quyidagi qiymatlardan biri bilan tugashi kerak: :values.',
    'enum' => 'Tanlangan :attribute noto\'g\'ri.',
    'exists' => 'Tanlangan :attribute noto\'g\'ri.',
    'file' => ':attribute fayl bo\'lishi kerak.',
    'filled' => ':attribute maydoni to\'ldirilishi shart.',
    'gt' => [
        'numeric' => ':attribute :value dan katta bo\'lishi kerak.',
        'file' => ':attribute :value kilobaytdan katta bo\'lishi kerak.',
        'string' => ':attribute :value belgidan ko\'p bo\'lishi kerak.',
        'array' => ':attribute :value elementdan ko\'p bo\'lishi kerak.',
    ],
    'gte' => [
        'numeric' => ':attribute :value dan katta yoki teng bo\'lishi kerak.',
        'file' => ':attribute :value kilobaytdan katta yoki teng bo\'lishi kerak.',
        'string' => ':attribute :value belgidan ko\'p yoki teng bo\'lishi kerak.',
        'array' => ':attribute kamida :value elementga ega bo\'lishi kerak.',
    ],
    'image' => ':attribute rasm bo\'lishi kerak.',
    'in' => 'Tanlangan :attribute noto\'g\'ri.',
    'in_array' => ':attribute maydoni :other da mavjud emas.',
    'integer' => ':attribute butun son bo\'lishi kerak.',
    'ip' => ':attribute haqiqiy IP manzil bo\'lishi kerak.',
    'ipv4' => ':attribute haqiqiy IPv4 manzil bo\'lishi kerak.',
    'ipv6' => ':attribute haqiqiy IPv6 manzil bo\'lishi kerak.',
    'json' => ':attribute haqiqiy JSON qatori bo\'lishi kerak.',
    'lt' => [
        'numeric' => ':attribute :value dan kichik bo\'lishi kerak.',
        'file' => ':attribute :value kilobaytdan kichik bo\'lishi kerak.',
        'string' => ':attribute :value belgidan kam bo\'lishi kerak.',
        'array' => ':attribute :value elementdan kam bo\'lishi kerak.',
    ],
    'lte' => [
        'numeric' => ':attribute :value dan kichik yoki teng bo\'lishi kerak.',
        'file' => ':attribute :value kilobaytdan kichik yoki teng bo\'lishi kerak.',
        'string' => ':attribute :value belgidan kam yoki teng bo\'lishi kerak.',
        'array' => ':attribute :value elementdan ko\'p bo\'lmasligi kerak.',
    ],
    'mac_address' => ':attribute haqiqiy MAC manzil bo\'lishi kerak.',
    'max' => [
        'numeric' => ':attribute :max dan katta bo\'lmasligi kerak.',
        'file' => ':attribute :max kilobaytdan katta bo\'lmasligi kerak.',
        'string' => ':attribute :max belgidan ko\'p bo\'lmasligi kerak.',
        'array' => ':attribute :max elementdan ko\'p bo\'lmasligi kerak.',
    ],
    'mimes' => ':attribute quyidagi turdagi fayl bo\'lishi kerak: :values.',
    'mimetypes' => ':attribute quyidagi turdagi fayl bo\'lishi kerak: :values.',
    'min' => [
        'numeric' => ':attribute kamida :min bo\'lishi kerak.',
        'file' => ':attribute kamida :min kilobayt bo\'lishi kerak.',
        'string' => ':attribute kamida :min belgidan iborat bo\'lishi kerak.',
        'array' => ':attribute kamida :min elementga ega bo\'lishi kerak.',
    ],
    'multiple_of' => ':attribute :value ning karrali bo\'lishi kerak.',
    'not_in' => 'Tanlangan :attribute noto\'g\'ri.',
    'not_regex' => ':attribute formati noto\'g\'ri.',
    'numeric' => ':attribute son bo\'lishi kerak.',
    'password' => 'Parol noto\'g\'ri.',
    'present' => ':attribute maydoni mavjud bo\'lishi kerak.',
    'prohibited' => ':attribute maydoni taqiqlangan.',
    'prohibited_if' => ':other :value bo\'lganda, :attribute maydoni taqiqlangan.',
    'prohibited_unless' => ':other :values da bo\'lmasa, :attribute maydoni taqiqlangan.',
    'prohibits' => ':attribute maydoni :other ning mavjudligini taqiqlaydi.',
    'regex' => ':attribute formati noto\'g\'ri.',
    'required' => ':attribute maydoni to\'ldirilishi shart.',
    'required_array_keys' => ':attribute maydonida quyidagi elementlar bo\'lishi kerak: :values.',
    'required_if' => ':other :value bo\'lganda, :attribute maydoni to\'ldirilishi shart.',
    'required_unless' => ':other :values da bo\'lmasa, :attribute maydoni to\'ldirilishi shart.',
    'required_with' => ':values mavjud bo\'lganda, :attribute maydoni to\'ldirilishi shart.',
    'required_with_all' => ':values mavjud bo\'lganda, :attribute maydoni to\'ldirilishi shart.',
    'required_without' => ':values mavjud bo\'lmaganda, :attribute maydoni to\'ldirilishi shart.',
    'required_without_all' => ':values lardan hech biri mavjud bo\'lmaganda, :attribute maydoni to\'ldirilishi shart.',
    'same' => ':attribute va :other mos kelishi kerak.',
    'size' => [
        'numeric' => ':attribute :size bo\'lishi kerak.',
        'file' => ':attribute :size kilobayt bo\'lishi kerak.',
        'string' => ':attribute :size belgidan iborat bo\'lishi kerak.',
        'array' => ':attribute :size elementga ega bo\'lishi kerak.',
    ],
    'starts_with' => ':attribute quyidagi qiymatlardan biri bilan boshlanishi kerak: :values.',
    'string' => ':attribute satr bo\'lishi kerak.',
    'timezone' => ':attribute haqiqiy vaqt zonasi bo\'lishi kerak.',
    'unique' => ':attribute allaqachon olingan.',
    'uploaded' => ':attribute yuklanmadi.',
    'url' => ':attribute haqiqiy URL bo\'lishi kerak.',
    'uuid' => ':attribute haqiqiy UUID bo\'lishi kerak.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'maxsus-xabar',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'Ism',
        'email' => 'Email manzil',
        'password' => 'Parol',
        'password_confirmation' => 'Parolni tasdiqlash',
        'current_password' => 'Joriy parol',
        'title' => 'Sarlavha',
        'content' => 'Mazmun',
        'description' => 'Tavsif',
        'excerpt' => 'Qisqacha mazmun',
        'date' => 'Sana',
        'time' => 'Vaqt',
        'subject' => 'Mavzu',
        'message' => 'Xabar',
        'amount' => 'Miqdor',
        'price' => 'Narx',
        'phone' => 'Telefon',
        'mobile' => 'Mobil telefon',
        'age' => 'Yosh',
        'address' => 'Manzil',
        'city' => 'Shahar',
        'country' => 'Mamlakat',
        'year' => 'Yil',
        'month' => 'Oy',
        'day' => 'Kun',
        'hour' => 'Soat',
        'minute' => 'Daqiqa',
        'second' => 'Soniya',
    ],

];
