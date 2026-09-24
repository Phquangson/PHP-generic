<?php

use App\Collections\Collection;
use Illuminate\Support\Facades\Route;
use App\Database\InsertBuilder;
use App\Exceptions\ValidationException;
use App\Sanitizer\Sanitizer;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/numbers', function () {
    $numbers = new Collection();

    $numbers -> add(1);
    $numbers -> add(2);
    $numbers -> add(3);
    $numbers -> add(4);
    
    return view('numbers', [
        'content' => 'Sum is: ' . array_sum($numbers->all())
    ]);
});

Route::get('/sanitize', function () {
    $input = [
        'name'  => '  <b>Nguyễn   Văn A</b>  ',
        'email' => ' VanA@Example.COM ',
        'age'   => '25',
    ];

    $rules = [
        'name'  => ['type' => 'string', 'required' => true, 'max' => 50, 'strip_tags' => true, 'collapse_spaces' => true],
        'email' => ['type' => 'email', 'required' => true],
        'age'   => ['type' => 'int', 'min' => 0, 'max' => 150],
    ];

    try {
        $clean = (new Sanitizer())->sanitize($input, $rules);
        [$sql, $params] = InsertBuilder::build('users', $clean);

        return response()->json(['clean' => $clean, 'sql' => $sql], 200, [], JSON_UNESCAPED_UNICODE);
    } catch (ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422, [], JSON_UNESCAPED_UNICODE);
    }
});