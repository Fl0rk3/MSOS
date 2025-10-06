document.getElementById('addLink_button').addEventListener('click', function () {

    urlName = document.getElementById('addLink_name').value;
    url = document.getElementById('addLink_link').value;
    sendValue = "urlName="+urlName+"&url="+url

    const xhr = new XMLHttpRequest();

    xhr.open('POST', './../Src/Php/scrapers/optionsBox/linksScraperAdd.php', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
    if (xhr.status === 200) {
        document.getElementById('option_window').classList.remove('active_window')
        document.getElementById('addLink_box').classList.remove('active_box')

        const alertBox = document.createElement("div");
        alertBox.classList.add('alertBox');
        phpResponse = JSON.parse(xhr.response)
        if(phpResponse[0]){
            alertBox.classList.add('alertBox_success');
            alertBox.innerHTML = "Dodano nowy link."
        }else{
            alertBox.classList.add('alertBox_error');
            alertBox.innerHTML = "Wystąpił błąd podczas dodawania nowego linka. Nazwa lub URL już istnieje."
        }
        const displayBox = document.getElementById('alerts_display');
        displayBox.appendChild(alertBox)

        document.getElementById('right_nav_links_bar').innerHTML = phpResponse[1];

        setTimeout(() =>{
            displayBox.removeChild(alertBox)
        }, 5000);
    } else {
        alertBox.classList.add('alertBox_error');
        alertBox.innerHTML = "Wystąpił błąd podczas dodawania nowego linka."
    }
    };

    xhr.send(sendValue);
});

function deleteLink(linkName){
    sendValue = "urlName="+linkName
    const xhr = new XMLHttpRequest();

    xhr.open('POST', './../Src/Php/scrapers/optionsBox/linksScraperRemove.php', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
    if (xhr.status === 200) {
        document.getElementById('option_window').classList.remove('active_window')
        document.getElementById('addLink_box').classList.remove('active_box')

        const alertBox = document.createElement("div");
        alertBox.classList.add('alertBox');
        phpResponse = JSON.parse(xhr.response)
        if(phpResponse[0]){
            alertBox.classList.add('alertBox_success');
            alertBox.innerHTML = "Usunięto link o nazwie "+linkName+"."
        }else{
            alertBox.classList.add('alertBox_error');
            alertBox.innerHTML = "Wystąpił błąd podczas usuwania linka. Link nie istnieje."
        }
        const displayBox = document.getElementById('alerts_display');
        displayBox.appendChild(alertBox)

        document.getElementById('right_nav_links_bar').innerHTML = phpResponse[1];

        setTimeout(() =>{
            displayBox.removeChild(alertBox)
        }, 5000);
    } else {
        alertBox.classList.add('alertBox_error');
        alertBox.innerHTML = "Wystąpił błąd podczas usuwania linka."
    }
    };

    xhr.send(sendValue);
}