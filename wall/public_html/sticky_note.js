$(function() {
  'use strict';

  $('#new_sticky_note').val('').focus();

  // delete
  $('#sticky_notes').on('click', '.delete_sticky_note ', function() {
      // idを取得
      var id = $(this).parents('li').data('id');

      // ajax処理
      swal({
        text: "ウォールからはがしますか？",
        icon: 'info',
        buttons: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          $.post('/../_ajax.php', {
            id: id,
            mode: 'delete',
            token: $('#token').val()
          }, function() {
            $('#sticky_note_' + id).fadeOut(100);
            $('#sticky_note_' + id).remove();
          });
        }
      });
      // SweetAlertなしでの実装
      // if (window.confirm('削除しますか？')) {
      //   $.post('/../_ajax.php', {
      //     id: id,
      //     mode: 'delete',
      //     token: $('#token').val()
      //   }, function() {
      //     $('#sticky_note_' + id).fadeOut(100);
      //   });
      // }
    });

  // create
  $('#new_sticky_note').keyup(function(){
      if ($('#new_sticky_note').val() == '') {
        $('#new_sticky_note_form > .font-awesome').css('display', 'none');
      } else {
        $('#new_sticky_note_form > .font-awesome').css('display', 'inline-block');
      }
  });
  $('#new_sticky_note_form').on('submit', function() {
    if ($('.sticky_note_sentence').length <= 100) {
      // sentenceを取得
      var sentence = $('#new_sticky_note').val();

      // ajax処理
      if (sentence !== '') {
        $.post('/../_ajax.php', {
          sentence: sentence,
          mode: 'create',
          token: $('#token').val()
        }, function(res) {
          // liを追加
          var  $li = $('#sticky_note_template').clone();
          $li
            .attr('id', 'sticky_note_' + res)
            .attr('data-id', res)
            // .data('id', res)
            .find('.sticky_note_sentence').text(sentence);
          $('#sticky_notes').prepend($li.fadeIn());
          $('#new_sticky_note').val('').focus();
        });
      } else {
        swal('文字を入力してください！');
      }
    } else {
      swal('無料枠では100枚までしか貼り付けられません！');
    }
    return false; // formをsubmitして画面の遷移が行われないようにする
  });

});
