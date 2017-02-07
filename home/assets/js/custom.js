!function($){$(function(){
  
  var $window = $(window);
    
  //$('#shoutTextarea').autogrow();
  
  $('#shoutTextarea').NobleCount('#charCount',{
    on_negative: 'negativeChar',
    on_positive: 'positiveChar',
    max_chars: 500
  });
  
  // Idle & Away
  /*
  var awayOn = false;
  var keepCover = false;
  setIdleTimeout(900000); // 15min
  setAwayTimeout(3600000); // 60min
  document.onIdle = function () {
    if (awayOn == false) {
      $('#idleScreen').slideDown();
      $('#idleText').fadeIn();
    }
  }
  document.onAway = function () {
    if (awayOn == false) {
      $('#idleText').hide();
      $('#idleTextAway').fadeIn();
      keepCover = true;
      awayOn = true;
    }
  }
  document.onBack = function (isIdle, isAway) {
    if (isIdle) {
      if (keepCover == false) {
        $('#idleScreen').slideUp();
        $('#idleText').hide();
      }
    }
  }
  $('#siteReload').click(function (e) {
    e.preventDefault();
    siteReload();
  });
  */

  // make code pretty (google)
  window.prettyPrint && prettyPrint();
  
  $('.tip-top, .btn-emoticon, .emoticons img, #clearButton').tooltip();
  $('.tip-bottom').tooltip({placement: 'bottom'});
  $('.tip-left').tooltip({placement: 'left'});
  $('.tip-right').tooltip({placement: 'right'});
  
  $('.pop-right').popover();
  $('.pop-top').popover({placement: 'top'});
  $('.pop-bottom').popover({placement: 'bottom'});
  $('.pop-left').popover({placement: 'left'});
  
  $('.pop-login-data').popover({trigger: 'hover'});
  
  //$('.btn-shout').click(function () {
  //  var btn = $(this)
  //  btn.button('loading')
  //  setTimeout(function () { btn.button('reset') }, 3000)
  //});
  $('#clearButton').click(function (e) {
    e.preventDefault();
    $('#shoutTextarea').val('');
  });
  
  // sharerlink box  
  $('.btn-close').click(function(e){
    e.preventDefault();
    $(this).parent().parent().parent().fadeOut();
  });
  $('.btn-minimize').click(function(e){
    e.preventDefault();
    var $target = $(this).parent().parent().next('.box-content');
    if ($target.is(':visible')) { $('i',$(this)).removeClass('icon-chevron-up').addClass('icon-chevron-down'); }
    else { $('i',$(this)).removeClass('icon-chevron-down').addClass('icon-chevron-up'); }
    $target.slideToggle();
  });
  
  // emoticons buttons - standard, onion, tuzki
  var btn1_open = false;
  var btn2_open = false;
  var btn3_open = false;
  $('#showSmileys').click(function (e) {
    e.preventDefault();
    if (btn2_open == true) {
      $('.emoticons-space').slideUp();
      $('#showOnion').removeClass('active');
      btn2_open = false;
      $(".emoticon-space").html('<div class="loader"></div>').fadeIn();
      $.ajax({
        url: 'include/emoticons_standard.php',
        success: function(data) {
          $('.emoticons-space').html(data).slideDown();
          $('#showSmileys').addClass('active');
          btn1_open = true;
        }
      });
    }
    else if (btn3_open == true) {
      $('.emoticons-space').slideUp();
      $('#showTuzki').removeClass('active');
      btn3_open = false;
      $(".emoticon-space").html('<div class="loader"></div>').fadeIn();
      $.ajax({
        url: 'include/emoticons_standard.php',
        success: function(data) {
          $('.emoticons-space').html(data).slideDown();
          $('#showSmileys').addClass('active');
          btn1_open = true;
        }
      });
    }
    else {
      if (btn1_open == false) {
        $(".emoticon-space").html('<div class="loader"></div>').fadeIn();
        $.ajax({
          url: 'include/emoticons_standard.php',
          success: function(data) {
            $('.emoticons-space').html(data).slideDown();
            $('#showSmileys').addClass('active');
            btn1_open = true;
          }
        });
      } else {
        $('.emoticons-space').slideUp();
        $('#showSmileys').removeClass('active');
        btn1_open = false;
      }
    }
  });
  $('#showOnion').click(function (e) {
    e.preventDefault();
    if (btn1_open == true) {
      $('.emoticons-space').slideUp();
      $('#showSmileys').removeClass('active');
      btn1_open = false;
      $(".emoticon-space").html('<div class="loader"></div>').fadeIn();
      $.ajax({
        url: 'include/emoticons_onion.php',
        success: function(data) {
          $('.emoticons-space').html(data).slideDown();
          $('#showOnion').addClass('active');
          btn2_open = true;
        }
      });
    }
    else if (btn3_open == true) {
      $('.emoticons-space').slideUp();
      $('#showTuzki').removeClass('active');
      btn3_open = false;
      $(".emoticon-space").html('<div class="loader"></div>').fadeIn();
      $.ajax({
        url: 'include/emoticons_onion.php',
        success: function(data) {
          $('.emoticons-space').html(data).slideDown();
          $('#showOnion').addClass('active');
          btn2_open = true;
        }
      });
    }
    else {
      if (btn2_open == false) {
        $(".emoticon-space").html('<div class="loader"></div>').fadeIn();
        $.ajax({
          url: 'include/emoticons_onion.php',
          success: function(data) {
            $('.emoticons-space').html(data).slideDown();
            $('#showOnion').addClass('active');
            btn2_open = true;
          }
        });
      } else {
        $('.emoticons-space').slideUp();
        $('#showOnion').removeClass('active');
        btn2_open = false;
      }
    }
  });
  $('#showTuzki').click(function (e) {
    e.preventDefault();
    if (btn1_open == true) {
      $('.emoticons-space').slideUp();
      $('#showSmileys').removeClass('active');
      btn1_open = false;
      $(".emoticon-space").html('<div class="loader"></div>').fadeIn();
      $.ajax({
        url: 'include/emoticons_tuzki.php',
        success: function(data) {
          $('.emoticons-space').html(data).slideDown();
          $('#showTuzki').addClass('active');
          btn3_open = true;
        }
      });
    }
    else if (btn2_open == true) {
      $('.emoticons-space').slideUp();
      $('#showOnion').removeClass('active');
      btn2_open = false;
      $(".emoticon-space").html('<div class="loader"></div>').fadeIn();
      $.ajax({
        url: 'include/emoticons_tuzki.php',
        success: function(data) {
          $('.emoticons-space').html(data).slideDown();
          $('#showTuzki').addClass('active');
          btn3_open = true;
        }
      });
    }
    else {
      if (btn3_open == false) {
        $(".emoticon-space").html('<div class="loader"></div>').fadeIn();
        $.ajax({
          url: 'include/emoticons_tuzki.php',
          success: function(data) {
            $('.emoticons-space').html(data).slideDown();
            $('#showTuzki').addClass('active');
            btn3_open = true;
          }
        });
      } else {
        $('.emoticons-space').slideUp();
        $('#showTuzki').removeClass('active');
        btn3_open = false;
      }
    }
  });
    
    
    

  })
}(window.jQuery)

// Insert emoticon codes into textarea (main shoutbox only)
function insertSmiley(smiley){
  var currentText = document.getElementById("shoutTextarea");
  var smileyWithPadding = " " + smiley + " ";
  currentText.value += smileyWithPadding;
}

function urlencode(a) {
  a = (a + "").toString();
  return encodeURIComponent(a).replace(/!/g, "%21").replace(/'/g, "%27").replace(/\(/g, "%28").replace(/\)/g, "%29").replace(/\*/g, "%2A").replace(/%20/g, "+")
}

function siteReload(){ location.reload(); } // Reload entire site

function bbCodeWrap(code) {
  $("#shoutTextarea").surroundSelectedText("["+code+"]", "[/"+code+"]");
}