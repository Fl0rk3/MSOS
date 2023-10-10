function display_timer(){
    var refresh = 1000;
    checkTime = setTimeout('display_time()', refresh);
}

function display_time(){
    var date = new Date();

    var ampm = date.getHours( ) >= 12 ? ' PM' : ' AM';

    hours = date.getHours( ) % 12;
    hours = hours ? hours : 12;
    hours=hours.toString().length==1? 0+hours.toString() : hours;

    var minutes=date.getMinutes().toString()
    minutes=minutes.length==1 ? 0+minutes : minutes;

    var seconds=date.getSeconds().toString()
    seconds=seconds.length==1 ? 0+seconds : seconds;

    var month=(date.getMonth() +1).toString();
    month=month.length==1 ? 0+month : month;

    var day=date.getDate().toString();
    day=day.length==1 ? 0+day : day;

    var formatDate = hours + ':' + minutes + ':' + seconds + ' ' + ampm + '</br>';
    formatDate = formatDate + day + '/' + month + '/' + date.getFullYear();

    document.getElementById('time').innerHTML = formatDate;
    display_timer();
}