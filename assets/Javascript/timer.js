function display_timer(hour_format) {
    var refresh = 1000;
    checkTime = setTimeout(() => display_time(hour_format), refresh);
}

function display_time(hour_format) {
    const date = new Date();
    let hours;
    let ampm = '';

    if (hour_format === "12h") {
        ampm = date.getHours() >= 12 ? ' PM' : ' AM';

        hours = date.getHours() % 12;
        hours = hours ? hours : 12;
    } else {
        hours = date.getHours();
    }
    hours = hours.toString().length === 1 ? 0 + hours.toString() : hours;

    let minutes = date.getMinutes().toString()
    minutes = minutes.length === 1 ? 0 + minutes : minutes;

    let seconds = date.getSeconds().toString()
    seconds = seconds.length === 1 ? 0 + seconds : seconds;

    let month = (date.getMonth() + 1).toString();
    month = month.length === 1 ? 0 + month : month;

    let day = date.getDate().toString();
    day = day.length === 1 ? 0 + day : day;

    let formatDate = hours + ':' + minutes + ':' + seconds + ' ' + ampm + '</br>';
    formatDate = formatDate + day + '/' + month + '/' + date.getFullYear();

    document.getElementById('time').innerHTML = formatDate;
    display_timer(hour_format);
}