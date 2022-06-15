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
    <div><span class="title">編集</span><a class="btn" href="{{route('index')}}">戻る</a></div>
    <hr>
<form action="" method="post">
    @csrf
    <table class="join_table">
        <tr><th>会社名</th> 
            <td>
                @if (!empty(old('name')))
                    <input class="text_join" type="text" name="name" value="{{ old('name') }}">
                @else
                    <input class="text_join" type="text" name="name" value="{{ ($data['company_name']) }}">
                @endif
                <p class="error">{{ $errors->first('name') }}</p>
            </td>
        </tr>
        <tr><th>担当者名</th> 
            <td>
                @if (!empty(old('manager')))
                    <input class="text_join" type="text" name="manager" value="{{ old('manager') }}">
                @else
                    <input class="text_join" type="text"name="manager" value="{{ $data['manager_name'] }}">
                @endif
                <p class="error">{{ $errors->first('manager') }}</p>
            </td>
        </tr>
        <tr><th>電話番号</th> 
            <td>
                @if (!empty(old('phone')))
                    <input class="text_join" type="text" name="phone" value="{{ old('phone') }}">
                @else
                    <input class="text_join" type="text" name="phone" value="{{ $data['phone_number'] }}">
                @endif
                <p class="error">{{ $errors->first('phone') }}</p>
            </td>
        </tr>
        <tr><th rowspan="3">住所</th> 
            <td>郵便番号 @if (!empty(old('postal')))
                            <input class="text_join" type="text" name="postal" value="{{ old('postal') }}">
                        @else
                            <input class="text_join_address" type="text" name="postal" value="{{ $data['postal_code'] }}">
                        @endif
                <p class="error">{{ $errors->first('postal') }}</p>
            </td>
        </tr>
        <tr><td>都道府県<select class="select_address" name="prefecture_code">
                        <option value="{{ $data['prefecture_code'] }}">{{ $prefecture[$data['prefecture_code']] }}</option>
                        @foreach ($prefecture as $number => $value)
                            <option value="{{ $number }}">{{$value}}</option>
                        @endforeach
                        </select>
                @if (!empty($errors->first('prefecture_code')))
                    <p class="error">{{ $errors->first('prefecture_code') }}</p>
                @elseif ($errors->any()) <!--エラーメッセージがあればtrue、なければfalseを戻す。-->
                    <p class="error">変更の場合もう一度選択してください</p>
                @endif
            </td>
        </tr>
            <tr><td>市区町村 @if (!empty(old('address')))
                                <input class="text_join" type="text" name="address" value="{{ old('address') }}">
                            @else 
                                <input class="text_join_address" type="text" name="address" value="{{ $data['address'] }}">
                            @endif
                <p class="error">{{ $errors->first('address') }}</p>
                </td>
            </tr>
        <tr><th>メールアドレス</th> 
            <td>@if (!empty(old('email')))
                    <input class="text_join" type="text" name="email" value="{{ old('email') }}">
                @else 
                    <input class="text_join" type="text" name="email" value="{{ $data['mail_address'] }}">
                @endif
                <p class="error">{{ $errors->first('email') }}</p>
            </td>
        </tr>
        <tr><th>プレフィックス</th> 
            <td>{{ $data['prefix'] }}</td>
            <input type="hidden" name="prefix" value="{{ $data['prefix'] }}">
        </tr>
    </table>
    <hr>
    <input type="submit" value="編集" class="long_btn">

</form>
</div>
</main>
</body>