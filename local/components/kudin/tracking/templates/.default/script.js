function digitalWatch() {
    var curdate = new Date();
    var date = new Date($('#tracking_time').attr('data-date'));
    var interval = new Date(curdate - date);
    H = interval.getUTCHours();
    if (H < 10) {
        H = "0" + H;
    }
    M = interval.getUTCMinutes();
    if (M < 10) {
        M = "0" + M;
    }
    S = interval.getUTCSeconds();
    if (S < 10) {
        S = "0" + S;
    }
    str = [H, M, S].join(':');
    $('#tracking_time').text(str);
}

$(function() {
    setInterval(digitalWatch, 1000); 
});
