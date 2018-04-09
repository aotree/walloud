$(function() {
  'use strict';

  // wall nameにfocus
  // document.getElementById("name").focus(); // nameにフォーカスすると、初回にtooltipが表示されないためコメントアウト

  // 英語対応
  var language = (window.navigator.languages && window.navigator.languages[0]) ||
                  window.navigator.language ||
                  window.navigator.userLanguage ||
                  window.navigator.browserLanguage;
  if (language == 'ja' || language == 'ja-JP' || language == 'ja-JP-mac') {
    // japanese

    // description
    document.getElementById("description").innerHTML = "壁に付箋を貼ろう！";
    // wall name
    document.getElementById("span_name").innerHTML = "ログインの際に使う名前です。\<br\>既に登録されている名前は使えません。\<br\>(半角英数字と記号(-_@.)で1~100文字)";
    // 1st section display name
    document.getElementById("span_display_name").innerHTML = "最初のセクションのタイトルとして画面上に表示する名前です。\<br\>セクションは1つのウォールに複数作成できます。\<br\>(1~20文字 ※全角は2文字分)";
    // email
    document.getElementById("span_email").innerHTML = "パスワードの再設定/アカウントの削除/問い合わせの際に使う管理者用のメールアドレスです。\<br\>\(xxx@xxx.xxx\)";
    // password
    document.getElementById("span_password").innerHTML = "ログインの際に使うパスワードです。\<br\>\(半角英数字で8~100文字\)";
  } else {
    // japanese以外

    // description
    document.getElementById("description").innerHTML = "Let's put a sticky note on the wall!";
    // wall name
    document.getElementById("span_name").innerHTML = "The name to use when logging in.\<br\>\(1 to 100 characters with\<br\> single-byte alphanumeric characters\<br\>and symbols\(-_@.\)\)";
    // 1st section display name
    document.getElementById("span_display_name").innerHTML = "The name to be displayed on the screen as the title of the first section.\<br\>Multiple sections can be created on one wall.\<br\>\(1 to 20 letters\)";
    // email
    document.getElementById("span_email").innerHTML = "The administrator's email address to use when Resetting passwords / Deleting accounts / Inquiry.\<br\>\(xxx@xxx.xxx\)";
    // password
    document.getElementById("span_password").innerHTML = "The password to use when logging in.\<br\>\(8 to 100 single-byte alphanumeric characters\)";
  }

  // tooltip
  $('#name').focus(function() {
    $('.tooltip').eq(0).css('display', 'inline');
  });
  $('#name').keyup(function() {
    if (document.getElementById('name').value !== ''){
      $('.tooltip').eq(0).css('display', 'none');
    } else {
      $('.tooltip').eq(0).css('display', 'inline');
    }
  });
  $('#name').focusout(function() {
    $('.tooltip').eq(0).css('display', 'none');
  });

  $('#display_name').focus(function() {
    $('.tooltip').eq(1).css('display', 'inline');
  });
  $('#display_name').keyup(function() {
    if (document.getElementById('display_name').value !== ''){
      $('.tooltip').eq(1).css('display', 'none');
    } else {
      $('.tooltip').eq(1).css('display', 'inline');
    }
  });
  $('#display_name').focusout(function() {
    $('.tooltip').eq(1).css('display', 'none');
  });

  $('#email').focus(function() {
    $('.tooltip').eq(2).css('display', 'inline');
  });
  $('#email').keyup(function() {
    if (document.getElementById('email').value !== ''){
      $('.tooltip').eq(2).css('display', 'none');
    } else {
      $('.tooltip').eq(2).css('display', 'inline');
    }
  });
  $('#email').focusout(function() {
    $('.tooltip').eq(2).css('display', 'none');
  });

  $('#password').focus(function() {
    $('.tooltip').eq(3).css('display', 'inline');
  });
  $('#password').keyup(function() {
    if (document.getElementById('password').value !== ''){
      $('.tooltip').eq(3).css('display', 'none');
    } else {
      $('.tooltip').eq(3).css('display', 'inline');
    }
  });
  $('#password').focusout(function() {
    $('.tooltip').eq(3).css('display', 'none');
  });

});
