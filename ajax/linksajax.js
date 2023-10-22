document.getElementById('addLink_button').addEventListener('click', function () {

    urlName = document.getElementById('addLink_name').value;
    url = document.getElementById('addLink_link').value;
    sendValue = "urlName="+urlName+"&url="+url

    const xhr = new XMLHttpRequest();

    xhr.open('POST', './php/scrapers/optionsBox/linksScraper.php', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
    if (xhr.status === 200) {
        document.getElementById('option_window').classList.remove('active_window')
        document.getElementById('addLink_box').classList.remove('active_box')

        const alertBox = document.createElement("div");
        alertBox.classList.add('alertBox');
        if(xhr.responseText){
            alertBox.classList.add('alertBox_success');
            alertBox.innerHTML = "Dodano nowy link."
        }else{
            alertBox.classList.add('alertBox_error');
            alertBox.innerHTML = "Wystąpił błąd podczas dodawania nowego linka."
        }
        const displayBox = document.getElementById('alerts_display');
        displayBox.appendChild(alertBox)

        setTimeout(() =>{
            displayBox.removeChild(alertBox)
        }, 5000);
    } else {
        console.error('Request failed:', xhr.status, xhr.statusText);
    }
    };

    xhr.send(sendValue);
   });