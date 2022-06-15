<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/i_style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/style_add.css') }}">
    <title>laravel練習</title>
</head>

<body>
<main>
    <div class="content_add">
    <div>
        <span class="title">請求書編集</span>
        <a class="btn" href="./index?id={{ $_GET['cid'] }}"">戻る</a>
    </div>
    <hr>
    <form action="" method="post">
        <table class="join_table">
            <tr><th>請求番号</th> 
                <td>{{ $data['no'] }}</td>
            </tr>
            <tr><th>請求名</th> 
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
            <tr><th>支払期限</th> 
                <td>
                    @if (!empty(old('pay')))
                        <input class="text_join" type="text" name="pay" value="{{ old('pay') }}">
                    @else
                        <input class="text_join" type="text"name="pay" value="{{ $data['payment_deadline'] }}">
                    @endif
                    <p class="error">{{ $errors->first('pay') }}</p>
                </td>
            </tr>
            <tr><th>請求日</th> 
                <td>
                    @if (!empty(old('date')))
                        <input class="text_join" type="text" name="date" value="{{ old('date') }}">
                    @else
                        <input class="text_join" type="text"name="date" value="{{ $data['date_of_issue'] }}">
                    @endif
                    <p class="error">{{ $errors->first('date') }}</p>
                </td>
            </tr>
            <tr><th>見積番号</th> 
                <td>{{ $data['quotation_no'] }}</td>
                <input type='hidden' name='quo' value="{{ $data['quotation_no'] }}">
            </tr>
            <tr><th>状態</th>
            <td><select class="select_status" name="status">
                        <option value="{{ $data['status'] }}">{{ $status[$data['status']] }}</option>
                        @foreach ($status as $number => $value)
                            <option value="{{ $number }}">{{ $value }}</option>
                        @endforeach
                    </select>
                    <p class="error">{{ $errors->first('status') }}</p>
            </td>
            </tr>
        </table>
        <hr>
        <input type="submit" value="変更" class="long_btn">
    </form>
    </div>
</main>
</body>
