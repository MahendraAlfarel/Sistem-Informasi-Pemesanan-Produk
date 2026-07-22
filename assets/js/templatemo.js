/*

TemplateMo 559 Zay Shop

https://templatemo.com/tm-559-zay-shop

*/

'use strict';
$(document).ready(function() {

    // Accordion
    var all_panels = $('.templatemo-accordion > li > ul').hide();

    $('.templatemo-accordion > li > a').click(function() {
        console.log('Hello world!');
        var target =  $(this).next();
        if(!target.hasClass('active')){
            all_panels.removeClass('active').slideUp();
            target.addClass('active').slideDown();
        }
      return false;
    });
    // End accordion

    // Product detail
    $('.product-links-wap a').click(function(){
      var this_src = $(this).children('img').attr('src');
      $('#product-detail').attr('src',this_src);
      return false;
    });
    $('#btn-minus').click(function () {
        let input = $('#product-quantity');
        let val = parseInt(input.val());
        if (!isNaN(val) && val > 1) {
            input.val(val - 1);
        } else {
            input.val(1); // fallback ke 1 kalau kosong atau salah input
        }
    });

    $('#btn-plus').click(function () {
        let input = $('#product-quantity');
        let val = parseInt(input.val());
        if (!isNaN(val)) {
            input.val(val + 1);
        } else {
            input.val(1); // fallback ke 1 kalau kosong atau salah input
        }
    });

    $('#product-quantity').on('input', function () {
        let val = parseInt($(this).val());
        if (isNaN(val) || val < 1) {
            $(this).val(1);
        }
    });
    
    $('.btn-size').click(function(){
      var this_val = $(this).html();
      $("#product-size").val(this_val);
      $(".btn-size").removeClass('btn-secondary');
      $(".btn-size").addClass('btn-success');
      $(this).removeClass('btn-success');
      $(this).addClass('btn-secondary');
      return false;
    });
    // End roduct detail

});
