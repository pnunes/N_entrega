 /* Fun��o para controlar o posicionamento da tela apos clicar nas op��es */
 
  /* var $doc = $('html, body');*/
   $('a').click(function() {
      $('html, body').animate({
         scrollTop: $( $.attr(this, 'href') ).offset().top - 100
      }, 500);
      return false;
   });
   
   
  /* $('.nav a[href^="#"]').on('click', function(e) {
      e.preventDefault();
      var id = $(this).attr('href'),
      targetOffset = $(id).offset().top;

      $('html, body').animate({
         scrollTop: targetOffset - 100
      }, 500);
   }); */

