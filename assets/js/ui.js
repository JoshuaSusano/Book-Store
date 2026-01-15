// UI-specific JS for Book Store
$(function() {
  // Smooth scroll for anchor links
  $("a[href^='#']").on('click', function(e) {
    var target = $(this.getAttribute('href'));
    if (target.length) {
      e.preventDefault();
      $('html, body').stop().animate({scrollTop: target.offset().top - 60}, 600);
    }
  });

  // Auto-hide alerts after 4s
  setTimeout(function() {
    $('.alert').fadeOut('slow');
  }, 4000);

  // Add ripple effect to buttons
  $(document).on('click', '.btn', function(e) {
    var $btn = $(this);
    if ($btn.find('.ripple').length === 0) {
      $btn.append('<span class="ripple"></span>');
    }
    var $ripple = $btn.find('.ripple');
    $ripple.removeClass('show');
    var x = e.pageX - $btn.offset().left;
    var y = e.pageY - $btn.offset().top;
    $ripple.css({top: y, left: x}).addClass('show');
  });
});
