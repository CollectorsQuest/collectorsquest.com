$(document).ready(function()
{
  // opening and closing of FAQ question
  $('#wizard .wpfaqtoggle').click(function () {
    var question = $(this).parent().find('div.wpfaqcontent');
    if (question.is(':hidden')) {
      question.slideDown();
    }
    else {
      question.slideUp();
    }
  });

  // style contact form submit button
  $('#wizard .contact-form input:submit').addClass('btn btn-primary').val('Submit');

  // init Smart Wizard
  $('#wizard').smartWizard();

  // no buttons on step 4
  $('#wizard a[href *= "step-"]').click(function () {
	  $('.actionBar').show();
  });

  $('#wizard a[href *= "step-4"]').click(function () {
    if ($(this).hasClass('done') || $(this).hasClass('selected'))
    {
      $('.actionBar').hide();
    }
  });
  $('#wizard a.buttonNext').click(function () {
  	if (!$('#step-3').is(':hidden')) {
      $('.actionBar').hide();
    }
  });

  // fix FOUC problem
  $(window).load(function() {
	$ ('#wizard').show();
  });
});





