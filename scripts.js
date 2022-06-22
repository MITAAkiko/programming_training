
//Ajax関数
function getData() {
    //
    var incode = $("#postcode").val();
    $.ajax({
        type: 'get'
      , url: 'https://zip-cloud.appspot.com/api/search'
      , dataType:'jsonp'        // 応答のデータの種類 
      , data: { 
            zipcode: incode     // 郵便番号
        }
      , jsonp: 'callback'       //コールバックパラメータ名の指定 callback{データ}の形で格納？
    }).done(function(res){
        // ajaxがOK
        if (res.status === 200 && res.results !== null) {
            var str = "";
            var str2 = "";
            var obj = res.results[0]; //resの１つ目のデータ

            str = obj.prefcode; //都道府県コード
            str2 = obj.address1; //都道府県名
            $("#prefcode").val(str).text(str2) 
            if (res.results.length > 1) { //市区町村が2個以上ある場合
                $('#post_modal').show()
                    for (i = 0; i < res.results.length; i++) {
                        str = res.results[i].address2 + res.results[i].address3; //市区町村
                        let radiobtn = '<input name="post" id=address'+i+' type="radio" value="' + str + '" class="rdobtn"><label class="label" for="address'+i+'">' + str + '</label><br class="label">'
                        $('#select_post').append(radiobtn)
                    }
                $('#select_post').click(function(){
                    const str1 = $('input:radio[name="post"]:checked').val();
                    $('.decision-modal').click(function(){
                        $('#post_modal').hide();//モーダル消す
                        $('.rdobtn').remove();//前のデータ消す。ラジオボタン
                        $('.label').remove();//前のデータ消す。ラベルと改行
                        $("#address").val(str1)
                        document.getElementById('address').focus();
                    });
               
                });
            } else {//1つの場合
                str = obj.address2 + obj.address3; //市区町村
                $("#address").val(str)
                document.getElementById('address').focus(); //カーソルを移動
            }//
        } else {
            alert('郵便番号を確認してください。　[status:' + res.status + ']');
        }
    }).fail(function() {
        // 取得エラー
        alert('取得エラー');
    });
    return false;
}
function cfm(){
    return confirm('本当に削除しますか');
}
