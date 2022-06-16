<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/q_style.css') }}">
    <title>laravel練習</title>
</head>

<body>
<main>
    <div class="contents">
    <h2 class="home">見積一覧 <a href="{{route('index')}}" class="btn">会社一覧へ戻る</a>
        <span class="company_name">{{ $company['company_name'] }}</span>
    </h2>
    <hr>
    <!--絞り込み-->
    <form action='./index' method="get">
    <a href="add?id={{ $_GET['id'] }}" class="long_btn">見積作成</a>
        <input class="search_btn" type="submit" value="検索">
        <select class="text_search" name="search">
            <!--検索した後の初期値-->
            @if (!empty($_GET['search']))
                <option value="{{ $_GET['search'] }}">{{ $status[$_GET['search']] }}</option>
            @endif
            <option value="">すべての状態</option>
                @foreach ($status as $number => $value)
                    <option value="{{ $number }}">{{ $value }}</option>
                @endforeach
        </select>
        <input type='hidden' name='id' value="{{ $_GET['id'] }}">
    </form>

    <br>
    <table>
        <tr class="table_heading">
            <form action='./index' method=get>
            <th class="no">見積番号　<input class="ascdesc" type="submit" value="▼"></th>
                <input type='hidden' name='id' value="{{ $_GET['id'] }}">
                @if ($order === 'DESC')
                    <?php $order = 'ASC'; ?>
                @else <!-- 初期設定 -->
                    <?php $order = 'DESC'; ?>
                @endif
                <input type='hidden' name="order" value="{{$order}}">
                @if (!empty($search))
                    <input type='hidden' name="search" value="{{$search}}">
                @endif
            </form>
            <th class="title">見積名</th><th class="manager">担当者名</th>
            <th class="total">金額</th><th class="period">見積書有効期限</th><th class="due">納期</th>
            <th class="status">状態</th><th class="q_edit">編集</th><th class="q_delete">削除</th>
        </tr>
<!--配列に代入-->

        <?php  foreach ($quotations as $quotation) : ?>
            <tr>
                <td class="td">{{ $quotation['no'] }}</td>
                <td class="td">{{ $quotation['title'] }}</td>
                <td class="td">{{ $quotation['company']['manager_name'] }}</td>
                <td class="td">{{ number_format($quotation['total']) }}円</td><!--カンマをつける-->
                <td class="td">{{ str_replace('-', '/', $quotation['validity_period']) }}<br>
                <td class="td">{{ str_replace('-', '/', $quotation['due_date']) }}</td>
                <td class="td">{{ $status[$quotation['status']] }}</td>
                <td class="td"><a class="edit_delete" href="./edit?id={{ $quotation['id'] }}&cid={{ $company['id'] }}">編集</a></td>
                <form action='./delete' method=post>
                    @csrf
                    <td class="td">
                        <a href="q_delete.php"><input type='submit' class="edit_delete" onclick="return cfm()" value='削除'></a>
                        <input type='hidden' name='cid' value="{{ $company['id'] }}">
                        <input type='hidden' name='id' value="{{ $quotation['id'] }}">
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </table>
<hr>

<div class="paging">
    {{ $quotations->onEachSide(2)->appends(request()->query())->links() }}
</div>
</main>
</body>
<script>
function cfm()
{
    return confirm('本当に削除しますか');
}
</script>
</html>
