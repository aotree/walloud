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
      location.href = "index.php";
    });
  });

  // delete_section
  $(document).on('click', '.delete_sticky_note ', function() {

    // ブラウザの言語設定を取得
    var language = (window.navigator.languages && window.navigator.languages[0]) ||
                    window.navigator.language ||
                    window.navigator.userLanguage ||
                    window.navigator.browserLanguage;

    if (language == 'ja' || language == 'ja-JP' || language == 'ja-JP-mac') {
      // Japanese
      if ($('.sticky_note_sentence').length > 2) {

        // idを取得
        var section_id = $(this).parents('li').data('id');
        var section_display_name = $(this).prev('span').text();

        // ajax処理
        swal({
          text: "セクションを削除すると、そのセクションに貼られた付箋も全て削除されます。「" + section_display_name + "」を削除しますか？",
          showCancelButton: true,
        }).then((result) => {
          if (result.value) {
            $.post('/../_ajax.php', {
              section_id: section_id,
              mode: 'delete_section',
              token: $('#token').val()
            }, function() {
              $('#sticky_note_' + section_id).fadeOut(100);
              $('#sticky_note_' + section_id).remove();
              $('#section_' + section_id).remove();
            });
          }
        });
      } else {
        // セクションが1つ(非表示のテンプレートを合わせると2つ)しかない場合
        swal({
          text: 'セクションが1つしかない時は削除できません！',
        });
      }
    } else {
      // japanese以外
      if ($('.sticky_note_sentence').length > 2) {

        // idを取得
        var section_id = $(this).parents('li').data('id');
        var section_display_name = $(this).prev('span').text();

        // ajax処理
        swal({
          text: "If you delete a section, all the sticky notes on the selected section will also be deleted. Delete \" " + section_display_name + "\"？",
          showCancelButton: true,
        }).then((result) => {
          if (result.value) {
            $.post('/../_ajax.php', {
              section_id: section_id,
              mode: 'delete_section',
              token: $('#token').val()
            }, function() {
              $('#sticky_note_' + section_id).fadeOut(100);
              $('#sticky_note_' + section_id).remove();
              $('#section_' + section_id).remove();
            });
          }
        });
      } else {
        // セクションが1つ(非表示のテンプレートを合わせると2つ)しかない場合
        swal({
          text: 'You can\'t delete it if there is only one section!',
        });
      }
    }
  });

  // update_section
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
        text: '✏️ セクション表示名を修正できます',
        input: 'text',
        inputValue: original_sentence,
        showCancelButton: true,
        inputValidator: (value) => {
          return !value && '文字を入力してください！'
        }
      })
      if (sentence) {
        // sentenceの文字数を取得
        var display_name_length = countLength(sentence);

        // ajax処理
        if (display_name_length <= 20 && display_name_length !== 0) {
          $.post('/../_ajax.php', {
            section_id: id,
            display_name: sentence,
            mode: 'update_section',
            token: $('#token').val()
          }, function() {
            location.reload();
          });
        } else {
          swal({
            text: '文字を入力してください！(1~20文字 ※全角は2文字分)',
          });
        }
      }
    } else {
      // japanese以外

      // idを取得
      var id = $(this).parents('span').parents('li').data('id');
      var original_sentence = $(this).parents('span').text().trim();

      const {value: sentence} = await swal({
        text: '✏️ You can modify the section display name',
        input: 'text',
        inputValue: original_sentence,
        showCancelButton: true,
        inputValidator: (value) => {
          return !value && 'Please enter a letter!'
        }
      })
      if (sentence) {
        // sentenceの文字数を取得
        var display_name_length = countLength(sentence);

        // ajax処理
        if (display_name_length <= 20 && display_name_length !== 0) {
          $.post('/../_ajax.php', {
            section_id: id,
            display_name: sentence,
            mode: 'update_section',
            token: $('#token').val()
          }, function() {
            location.reload();
          });
        } else {
          swal({
            text: 'Please enter a letter!(1 ~ 20 letters)',
          });
        }
      }
    }
  });

  // create_section
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

      if ($('.sticky_note_sentence').length <= 10) {

        // sentenceを取得
        var display_name = $('#new_sticky_note').val().trim();
        var display_name_length = countLength(display_name);

        // ajax処理
        if (display_name_length <= 20 && display_name_length !== 0) {
          $.post('/../_ajax.php', {
            display_name: display_name,
            mode: 'create_section',
            token: $('#token').val()
          }, function(res) {
            // liを追加
            var $li = $('#sticky_note_template').clone();
            $li
              .attr('id', 'sticky_note_' + res)
              .attr('data-id', res)
              .find('.sticky_note_sentence').text(display_name)
              .append(' <i class="fas fa-pencil-alt font-awesome update_sticky_note" style="color: #fc0 !important"></i>');
            $('#sticky_notes').append($li.fadeIn());

            // liを追加
            var  $li = $('#section_templete').clone();
            $li
              .attr('id', 'section_' + res)
              .attr('data-id', res)
              .text(display_name)
            $('#li_section').before($li.fadeIn());
            $('#new_sticky_note').val('').focus();
          });
        } else {
          swal({
            text: '文字を入力してください！(1~20文字 ※全角は2文字分)',
          });
        }
      } else {
        swal({
          text: '10個までしかセクションを作れません！',
        });
      }
      return false; // formをsubmitして画面の遷移が行われないようにする
    } else {
      // japanese以外

      if ($('.sticky_note_sentence').length <= 10) {

        // sentenceを取得
        var display_name = $('#new_sticky_note').val().trim();
        var display_name_length = countLength(display_name);

        // ajax処理
        if (display_name_length <= 20 && display_name_length !== 0) {
          $.post('/../_ajax.php', {
            display_name: display_name,
            mode: 'create_section',
            token: $('#token').val()
          }, function(res) {
            // liを追加
            var $li = $('#sticky_note_template').clone();
            $li
              .attr('id', 'sticky_note_' + res)
              .attr('data-id', res)
              .find('.sticky_note_sentence').text(display_name)
              .append(' <i class="fas fa-pencil-alt font-awesome update_sticky_note" style="color: #fc0 !important"></i>');
            $('#sticky_notes').append($li.fadeIn());

            // liを追加
            var  $li = $('#section_templete').clone();
            $li
              .attr('id', 'section_' + res)
              .attr('data-id', res)
              .text(display_name)
            $('#li_section').before($li.fadeIn());
            $('#new_sticky_note').val('').focus();
          });
        } else {
          swal({
            text: 'Please enter a letter!(1 ~ 20 letters)',
          });
        }
      } else {
        swal({
          text: 'Only 10 sections can be made!',
        });
      }
      return false; // formをsubmitして画面の遷移が行われないようにする
    }
  });

  // 半角->1文字、全角->2文字カウント
  function countLength(str) {
    var length = 0;
    for (var i = 0; i < str.length; i++) {
      var c = str.charCodeAt(i);
      if ((c >= 0x0 && c < 0x81) || (c === 0xf8f0) || (c >= 0xff61 && c < 0xffa0) || (c >= 0xf8f1 && c < 0xf8f4)) {
        length += 1;
      } else {
        length += 2;
      }
    }
    return length;
  }
  
});
