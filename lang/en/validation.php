<?php

return [
    'accepted' => 'El campo :attribute debe ser aceptado.',
    'accepted_if' => 'El campo :attribute debe ser aceptado cuando :other sea :value.',
    'active_url' => 'El campo :attribute no es una URL válida.',
    'after' => 'El campo :attribute debe ser una fecha posterior a :date.',
    'alpha' => 'El campo :attribute solo puede contener letras.',
    'alpha_dash' => 'El campo :attribute solo puede contener letras, números, guiones y guiones bajos.',
    'alpha_num' => 'El campo :attribute solo puede contener letras y números.',
    'array' => 'El campo :attribute debe ser un array.',
    'before' => 'El campo :attribute debe ser una fecha anterior a :date.',
    'confirmed' => 'La confirmación de :attribute no coincide.', // <--- Vital para las contraseñas
    'email' => 'El campo :attribute debe ser una dirección de correo válida.',
    'exists' => 'El campo :attribute seleccionado es inválido.',
    'image' => 'El campo :attribute debe ser una imagen.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'max' => [
        'numeric' => 'El campo :attribute no debe ser mayor a :max.',
        'file' => 'El campo :attribute no debe ser mayor a :max kilobytes.',
        'string' => 'El campo :attribute no debe ser mayor a :max caracteres.',
    ],
    'min' => [
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
    ],
    'numeric' => 'El campo :attribute debe ser un número.',
    'required' => 'El campo :attribute es obligatorio.',
    'unique' => 'El campo :attribute ya ha sido registrado.',

    /*
    |--------------------------------------------------------------------------
    | Personalización de Atributos
    |--------------------------------------------------------------------------
    */
    'attributes' => [
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'name' => 'nombre',
    ],
];