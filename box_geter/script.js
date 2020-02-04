$(function(){
  function getRandomNum() {
    return Math.ceil(Math.random() * 10);
  }

  $('.box').click(function() {
    $('#bg2').fadeToggle(500);

    var points = getRandomNum();
    $('#point').text(points);

    $.ajax({
      type: 'POST',
      url: '_points.php',
      data: {
        token: $('#token').val(),
        points: points,
      },
    }).done(function(res) {

    }).fail(function() {
      console.log('Ajax Error');
    });
  });

  $('.delete').click(function() {
    $('#bg3').show(0);
  });

  $('.notdelete').click(function() {
    $('#bg3').hide(0);
  });
});
