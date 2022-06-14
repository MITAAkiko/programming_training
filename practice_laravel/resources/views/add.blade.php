<?php
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="{{ asset('css/style_add.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
    <title>laravel練習</title>
</head>

<body>
<main>
    <div class="content_add">
    <div><span class="title">会社登録</span><a class="btn" href="{{route('index')}}">戻る</a></div>
    <hr>
<form action="" method="post">
    @csrf
    <table class="join_table">
        <tr><th>会社名</th> 
            <td>
                <input class="text_join" type="text" name="name" value="{{ old('name') }}">
                <p class="error">{{ $errors->first('name') }}</p>
            </td>
        </tr>
        <tr><th>担当者名</th> 
            <td>
                <input class="text_join" type="text"name="manager" value="{{ old('manager') }}">
                <p class="error">{{ $errors->first('manager') }}</p>
            </td>
        </tr>
        <tr><th>電話番号</th> 
            <td>
                <input class="text_join" type="text" name="phone" value="{{ old('phone') }}">
                <p class="error">{{ $errors->first('phone') }}</p>
            </td>
        </tr>
        <tr><th rowspan="3">住所</th> 
            <td>郵便番号 <input class="text_join_address" type="text" name="postal" value="{{ old('postal') }}">
            <p class="error">{{ $errors->first('postal') }}</p>
            </td>
        </tr>
        <tr><td>都道府県<select class="select_address" name="prefecture_code">
                <option value="">選択してください</option>
                @foreach ($prefecture as $number => $value)
                    <option value="{{ $number }}">{{$value}}</option>
                @endforeach
                </select>
                @if (!empty($errors->first('prefecture_code')))
                    <p class="error">{{ $errors->first('prefecture_code') }}</p>
                @elseif ($errors->any()) <!--エラーメッセージがあればtrue、なければfalseを戻します。-->
                    <p class="error">もう一度選択してください</p>
                @endif
            </td>
        </tr>
            <tr><td>市区町村 <input class="text_join_address" type="text" name="address" value="{{ old('address') }}">
            <p class="error">{{ $errors->first('address') }}</p>
                </td>
            </tr>
        <tr><th>メールアドレス</th> 
            <td>
                <input class="text_join" type="text" name="email" value="{{ old('email') }}">
                <p class="error">{{ $errors->first('email') }}</p>
            </td>
        </tr>
        <tr><th>プレフィックス</th> 
            <td>
                 <input class="text_join" type="text" name="prefix" value="{{ old('prefix') }}">
                 <p class="error">{{ $errors->first('prefix') }}</p>
            </td>
        </tr>
    </table>
    <hr>
    <input type="submit" value="新規登録" class="long_btn">
</form>
</div>
</main>
</body>