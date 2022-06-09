<?php
?>
<!DOCTYPE html>
<html>
  <head>
    <title>laravel練習</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
  </head>
  <main>
    <div class="contents">
    <h2 class="home" href='./'>会社一覧</h2>

    <hr>
    <a href="{{ route('add') }}" class="long_btn">新規登録</a>
    <form action="{{route('index')}}" method="get">
      <input class="search_btn" type="submit" value="検索">
      <input class="text_search" type="text" name="search" value="<?php
        if (!empty($search)) {
            echo ($search);
        } ?>">
    </form>
    <br><br>
    <table id='companies_list'>
 
        <tr class="table_heading">
            <form action="./index" method="get"> 
              <th class="th ID">会社番号　<input class="ascdesc" type="submit" value="▼"></th>
              @if ($order === 'DESC')
                <?php $order = 'ASC'; ?>
              @else <!-- 初期設定 -->
                <?php $order = 'DESC'; ?>
              @endif
              <input type='hidden' name="order" value="{{$order}}">
            <form>
            <th class="th name">会社名</th><th class="th PIC">担当者名</th><th class="th tel">電話番号</th>
            <th class="th address">住所</th><th class="th email">メールアドレス</th>
            <th class="th quotation">見積一覧</th><th class="th invoice">請求一覧</th>
            <th class="th edit">編集</th><th class="th delete">削除</th>
        </tr>
    @foreach ($datas as $data) 
        <tr>
            <td class="td">{{ ($data['id']) }}</td>
            <td class="td">{{ ($data['company_name']) }}</td>
            <td class="td">{{ ($data['manager_name']) }}</td>
            <td class="td">{{ ($data['phone_number']) }}</td>
            <td class="td">{{ ($data['postal_code']) }}<br>
            {{ $prefecture[$data["prefecture_code"]].($data['address']) }}</td>
            <td class="td">{{ ($data['mail_address']) }}</td>
            <td class="td"><a class="list_btn">見積(仮)</a></td>
            <td class="td"><a class="list_btn" href="">請求(仮)</a></td>
            <td class="td"><a class="edit_delete" href="{{ route('edit', ['id' => $data['id']]) }}">編集</a></td>
            <!-- ./edit?id='. { $data['id'] }  または　{route('edit?id='.$data['id'])} -->
            <td class="td"><a class="edit_delete" href="{{ route('delete', ['id' => $data['id']]) }}" onclick="return cfm()">削除</a></td>
        </tr>
    @endforeach
    </table>
    <hr>

    <div class="paging">
    <!-- {{ $datas->links() }} -->
    <!-- 前後で5件のリンク取得 -->
    <span class='center'>
    {{ $datas->onEachSide(3)->appends(request()->query())->links() }}
    </span>
    </div>
    <script>
      function cfm(){
          return confirm('本当に削除しますか');
      }
    </script>
  </body>
</html> 

