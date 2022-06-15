<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/q_style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/style_add.css') }}">
    <title>laravel練習</title>
</head>

<body>
<main>
    <div class="content_add">
    <div>
        <span class="title">見積書編集</span>
        <a class="btn" href="./index?id={{ $_GET['cid'] }}">戻る</a>
    </div>
    <hr>
    <form action="" method="post">
    @csrf
        <table class="join_table">
            <tr><th>見積番号</th> 
                    <td>{{ $data['no'] }}</td>
                </tr>
            <tr><th>見積名</th> 
                <td>
                    @if (!empty(old('title')))
                        <input class="text_join" type="text" name="title" value="{{ old('title') }}">
                    @else
                        <input class="text_join" type="text"name="title" value="{{ $data['title'] }}">
                    @endif
                    <p class="error">{{ $errors->first('title') }}</p>
                </td>
            </tr>
            <tr><th>会社名</th> 
                <td>{{ $company['company_name'] }}</td>
            </tr>
            <tr><th>金額</th> 
                <td>
                    @if (!empty(old('total')))
                        <input class="text_join_en" type="text" name="total" value="{{ old('total') }}"> 円
                    @else
                        <input class="text_join_en" type="text"name="total" value="{{ $data['total'] }}"> 円
                    @endif
                    <p class="error">{{ $errors->first('total') }}</p>
                </td>
            </tr>
            <tr><th>見積書有効期限</th> 
                <td>
                    @if (!empty(old('period')))
                        <input class="text_join" type="text" name="period" value="{{ old('period') }}">
                    @else
                        <input class="text_join" type="text"name="period" value="{{ str_replace('-', '', $data['validity_period']) }}">
                    @endif
                    <p class="error">{{ $errors->first('period') }}</p>
                </td>
            </tr>
            <tr><th>納期</th> 
                <td>
                    @if (!empty(old('due')))
                        <input class="text_join" type="text" name="due" value="{{ old('due') }}">
                    @else
                        <input class="text_join" type="text"name="due" value="{{ str_replace('-', '', $data['due_date']) }}">
                    @endif
                    <p class="error">{{ $errors->first('due') }}</p>
                </td>
            </tr>
            <tr><th>状態</th>
                <td><select class="select_status" name="status">
                        <option value="{{ $data['status'] }}">{{ $status[$data['status']] }}</option>
                        @foreach ($status as $number => $value)
                            <option value="{{ $number }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    @if (!empty($errors->first('status')))
                        <p class="error">{{ $errors->first('status') }}</p>
                    @elseif ($errors->any()) <!--エラーメッセージがあればtrue、なければfalseを戻します。-->
                        <p class="error">変更の場合もう一度選択してください</p>
                    @endif
                </td>
            </tr>
        </table>
        <hr>
        <input type="submit" value="変更" class="long_btn">
        <input type="hidden" name="cid" value="{{$company['id']}}">
        <input type="hidden" name="id" value="{{$_GET['id']}}">
    </form>
    </div>
</main>
</body>
