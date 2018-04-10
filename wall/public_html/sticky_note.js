// スマートフォンの画面が横になった際の警告
// →SweetAlert2のiOSブランク背景問題解消用 参考<https://github.com/sweetalert2/sweetalert2/issues/473>
window.onorientationchange = function () {
 switch ( window.orientation ) {
  case 0:
   break;
  case 90:
   alert('画面を縦にしてください\n(Please make the screen vertical)');
   break;
  case -90:
   alert('画面を縦にしてください\n(Please make the screen vertical)');
   break;
 }
}

$(function() {
  'use strict';

  $('#new_sticky_note').val('').focus();

  // move_section
  $(document).on('click', '.section', function() {
    $.post('/../_ajax.php', {
      section_id: $(this).data('id'),
      mode: 'section',
      token: $('#token').val()
    }, function() {
      location.reload();
    });
  });

  // sort
  $(".sortable").sortable();
  $(".sortable").disableSelection();
  $('.sortable').bind('sortstop', function (e, ui) {
    var sort_result = $(".sortable").sortable("toArray");
    var sort_values = [];
    for(var i = 0; i < sort_result.length - 1; i++) {
      sort_values.push(document.getElementById(sort_result[i]).getAttribute('value'));
    }

    // ajax処理
    $.post('/../_ajax.php', {
      sort_values: sort_values,
      mode: 'sort',
      token: $('#token').val()
    }, function() {
      location.reload();
    });
  });

  // delete
  $(document).on('click', '.delete_sticky_note ', function() {

    // ブラウザの言語設定を取得
    var language = (window.navigator.languages && window.navigator.languages[0]) ||
                    window.navigator.language ||
                    window.navigator.userLanguage ||
                    window.navigator.browserLanguage;

    if (language == 'ja' || language == 'ja-JP' || language == 'ja-JP-mac') {
      // Japanese

      // idを取得
      var id = $(this).parents('li').data('id');

      swal({
        text: "ウォールからはがしますか？",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
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
    } else {
      // japanese以外

      // idを取得
      var id = $(this).parents('li').data('id');

      swal({
        text: "Peel off the sticky note from the wall?",
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
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
    }
  });

  // update
  $(document).on('click', '.update_sticky_note', async function() {

    // ブラウザの言語設定を取得
    var language = (window.navigator.languages && window.navigator.languages[0]) ||
                    window.navigator.language ||
                    window.navigator.userLanguage ||
                    window.navigator.browserLanguage;

    if (language == 'ja' || language == 'ja-JP' || language == 'ja-JP-mac') {
      // Japanese

      // idを取得
      var id = $(this).parents('span').parents('li').data('id');
      var original_sentence = $(this).parents('span').text().trim();

      const {value: sentence} = await swal({
        text: '✏️ 文章を修正できます',
        input: 'text',
        inputValue: original_sentence,
        showCancelButton: true,
        inputValidator: (value) => {
          return !value && '文字を入力してください！'
        }
      })
      if (sentence) {
        if (sentence.length <= 120) {
          $.post('/../_ajax.php', {
            id: id,
            sentence: sentence,
            mode: 'update',
            token: $('#token').val()
          }, function() {
            $('#sticky_note_' + id).find('.sticky_note_sentence').text(sentence);
            $('#sticky_note_' + id).attr('value', sentence);
            $('#sticky_note_' + id).find('.sticky_note_sentence').append(' <i class="fas fa-pencil-alt font-awesome update_sticky_note"></i>');
          });
        } else {
          swal({
            text: '120文字以下で入力してください！',
          });
        }
      }
    } else {
      // japanese以外

      // idを取得
      var id = $(this).parents('span').parents('li').data('id');
      var original_sentence = $(this).parents('span').text().trim();

      const {value: sentence} = await swal({
        text: '✏️ You can modify the sentence',
        input: 'text',
        inputValue: original_sentence,
        showCancelButton: true,
        inputValidator: (value) => {
          return !value && 'Please enter a letter!'
        }
      })
      if (sentence) {
        if (sentence.length <= 120) {
          $.post('/../_ajax.php', {
            id: id,
            sentence: sentence,
            mode: 'update',
            token: $('#token').val()
          }, function() {
            $('#sticky_note_' + id).find('.sticky_note_sentence').text(sentence);
            $('#sticky_note_' + id).attr('value', sentence);
            $('#sticky_note_' + id).find('.sticky_note_sentence').append(' <i class="fas fa-pencil-alt font-awesome update_sticky_note"></i>');
          });
        } else {
          swal({
            text: 'Please enter less than 120 characters!',
          });
        }
      }
    }
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

    // ブラウザの言語設定を取得
    var language = (window.navigator.languages && window.navigator.languages[0]) ||
                    window.navigator.language ||
                    window.navigator.userLanguage ||
                    window.navigator.browserLanguage;

    if (language == 'ja' || language == 'ja-JP' || language == 'ja-JP-mac') {
      // Japanese

      if ($('.sticky_note_sentence').length <= 100) {
        // sentenceを取得
        var sentence = $('#new_sticky_note').val().trim();

        // ajax処理
        if (sentence.length >= 1 && sentence.match(/^\s+$/) == null) {
          if (sentence.length <= 120) {
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
                .attr('value', sentence)
                .find('.sticky_note_sentence').text(sentence)
                .append(' <i class="fas fa-pencil-alt font-awesome update_sticky_note"></i>');
              // $('#sticky_notes').append($li.fadeIn());
              $('#sticky_note_template').before($li.fadeIn());
              $('#new_sticky_note').val('').focus();
            });
          } else {
            swal({
              text: '120文字以下で入力してください！',
            });
          }
        } else {
          swal({
            text: '文字を入力してください！',
          });
        }
      } else {
        swal({
          text: '100枚までしか貼り付けられません！',
        });
      }
      return false; // formをsubmitして画面の遷移が行われないようにする
    } else {
      // japanese以外

      if ($('.sticky_note_sentence').length <= 100) {
        // sentenceを取得
        var sentence = $('#new_sticky_note').val().trim();

        // ajax処理
        if (sentence.length >= 1 && sentence.match(/^\s+$/) == null) {
          if (sentence.length <= 120) {
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
                .attr('value', sentence)
                .find('.sticky_note_sentence').text(sentence)
                .append(' <i class="fas fa-pencil-alt font-awesome update_sticky_note"></i>');
              // $('#sticky_notes').append($li.fadeIn());
              $('#sticky_note_template').before($li.fadeIn());
              $('#new_sticky_note').val('').focus();
            });
          } else {
            swal({
              text: 'Please enter less than 120 characters!',
            });
          }
        } else {
          swal({
            text: 'Please enter a letter!',
          });
        }
      } else {
        swal({
          text: 'Up to 100 sticky notes can be pasted!',
        });
      }
      return false; // formをsubmitして画面の遷移が行われないようにする
    }
  });

});
