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
        <span class="title">見積作成</span>
        <a class="btn" href="./index?id={{ $_GET['id'] }}">戻る</a></div>
        <hr>
        <form action="" method="post">
        @csrf
            <table class="join_table">
                <tr><th>見積名</th> 
                    <td>
                        <input class="text_join" type="text" name="title" value="{{ old('title') }}">
                        <p class="error">{{ $errors->first('title') }}</p>
                    </td>
                </tr>
                <tr><th>会社名</th> 
                    <td>{{ $company['company_name'] }}</td>
                </tr>
                <tr><th>金額</th> 
                    <td>
                        <input class="text_join_en" type="text" name="total" value="{{ old('total') }}"> 円
                        <p class="error">{{ $errors->first('total') }}</p>
                    </td>
                </tr>
                <tr><th>見積書有効期限<br><span class="advice">(例:20210525)</span></th> 
                    <td>
                        <input class="text_join" type="text" name="period" value="{{ old('period') }}">
                        <p class="error">{{ $errors->first('period') }}</p>
                    </td>
                </tr>
                <tr><th>納期<br><span class="advice">(例:20210625)</span></th> 
                    <td>
                        <input class="text_join" type="text" name="due" value="{{ old('due') }}">
                        <p class="error">{{ $errors->first('due') }}</p>
                    </td>
                </tr>
                <tr><th>状態</th>
                    <td><select class="select_status" name="status">
                            <option value="">選択してください</option>
                            @foreach ($status as $number => $value)
                                <option value="{{ $number }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @if (!empty($errors->first('status')))
                            <p class="error">{{ $errors->first('status') }}</p>
                        @elseif ($errors->any()) <!--エラーメッセージがあればtrue、なければfalseを戻します。-->
                            <p class="error">もう一度選択してください</p>
                        @endif
                    </td>
                </tr>
            </table>
            <hr>
            <input type="submit" value="見積作成" class="long_btn">
            <input type="hidden" name="prefix" value="{{ $company['prefix'] }}">
            <input type="hidden" name="cid" value="{{$company['id']}}">
        </form>
    </div>
</main>
</body>
