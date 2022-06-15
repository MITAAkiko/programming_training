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
    <form action="{{route('index')}}" method="get">
    <a href="{{ route('add') }}" class="long_btn">新規登録</a>
      <input class="search_btn" type="submit" value="検索">
      <input class="text_search" type="text" name="search" value="<?php
        if (!empty($search)) {
            echo ($search);
        } ?>">
    </form>
    <br>
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
            @if (!empty($search))
              <input type='hidden' name="search" value="{{$search}}">
            @endif
        </form>
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
        <td class="td">
          {{ ($data['postal_code']) }}<br>
          {{ $prefecture[$data["prefecture_code"]].($data['address']) }}
        </td>
        <td class="td">{{ ($data['mail_address']) }}</td>
        <td class="td"><a class="list_btn" href="./quotations/index?id={{ ($data['id']) }}">見積一覧</a></td>
        <td class="td"><a class="list_btn" href="./invoices/index?id={{ ($data['id']) }}">請求一覧</a></td>
        <td class="td"><a class="edit_delete" href="{{ route('edit', ['id' => $data['id']]) }}">編集</a></td>
        <td class="td">
          <form method="post" action="./delete">
            @csrf
            <input type="hidden" name='id' value="{{ ($data['id']) }}">
            <!-- @//csrf
            @//method('delete')-->
            <input type='submit' class="edit_delete" onclick="return cfm()" value='削除'>
          </form>
        </td>
      </tr>
    @endforeach
    </table>
    <hr>
    <div class="paging">
    {{ $datas->onEachSide(2)->appends(request()->query())->links() }}
    </div>
    <script>
      function cfm(){
          return confirm('本当に削除しますか');
      }
    </script>
  </body>
</html> 

