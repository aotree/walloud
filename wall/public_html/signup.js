$(function() {
  'use strict';

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
