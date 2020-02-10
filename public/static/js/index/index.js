// 前台js
// 响应式侧边栏导航滑出事件

//     mark = false;
// $('#nav-control i').on('click', function () {
//     // $('.nav-xs').slideToggle('slow');
//     if ( mark == false ) {
//         mark = true;
//         xs.css('width', '200px');
//         $(this).html('&#xe9ba;');
//     } else {
//         mark = false;
//         xs.css('width', 0);
//         $(this).html('&#xe8bc;');
//     }
// });

$('#nav-control').on('click', function () {
    var xs = $('.nav-xs');
    var mark = $(this).attr('data-mark');
    if (mark == 'false') {
        $(this).removeClass('menu_open').addClass('menu_close');
        //open
        // $('#navgation').removeClass('navgation_close').addClass('navgation_open');
        xs.css('width', '200px');
        $(this).attr({ "data-mark": "true" });
    } else {
        $(this).removeClass('menu_close').addClass('menu_open');
        //close
        // $('#navgation').removeClass('navgation_open').addClass('navgation_close');
        xs.css('width', 0);
        $(this).attr({ "data-mark": "false" });
    }
});