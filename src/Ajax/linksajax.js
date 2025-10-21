document.getElementById('addLink_button').addEventListener('click', function () {

    urlName = document.getElementById('addLink_name').value;
    url = document.getElementById('addLink_link').value;
    sendValue = "urlName=" + urlName + "&url=" + url

    const xhr = new XMLHttpRequest();

    xhr.open('POST', './../../src/backend/Scraper/OptionsBox/linksScraperAdd.php', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('option_window').classList.remove('active_window')
            document.getElementById('addLink_box').classList.remove('active_box')

            const alertBox = document.createElement("div");
            alertBox.classList.add('alertBox');
            phpResponse = JSON.parse(xhr.response)
            if (phpResponse['success']) {
                alertBox.classList.add('alertBox_success');
                alertBox.innerHTML = I18N.t(phpResponse['message_key'], phpResponse['message_vars']);
                document.getElementById('right_nav_links_bar').innerHTML = phpResponse['html'];
                document.getElementById('addLink_name').value = '';
                document.getElementById('addLink_link').value = '';
            } else {
                alertBox.classList.add('alertBox_error');
                alertBox.innerHTML = I18N.t(phpResponse['message']);
            }
            const displayBox = document.getElementById('alerts_display');
            displayBox.appendChild(alertBox)

            setTimeout(() => {
                displayBox.removeChild(alertBox)
            }, 5000);
        } else {
            alertBox.classList.add('alertBox_error');
            alertBox.innerHTML = I18N.t("alert.error.connection_error");
        }
    };

    xhr.send(sendValue);
});

function deleteLink(linkName) {
    sendValue = "urlName=" + linkName
    const xhr = new XMLHttpRequest();

    xhr.open('POST', './../../src/backend/Scraper/OptionsBox/linksScraperRemove.php', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {
            const alertBox = document.createElement("div");
            alertBox.classList.add('alertBox');
            phpResponse = JSON.parse(xhr.response)
            if (phpResponse['success']) {
                alertBox.classList.add('alertBox_success');
                alertBox.innerHTML = I18N.t(phpResponse['message_key'], phpResponse['message_vars']);
                document.getElementById('right_nav_links_bar').innerHTML = phpResponse['html'];
            } else {
                alertBox.classList.add('alertBox_error');
                alertBox.innerHTML = I18N.t(phpResponse['message']);
            }
            const displayBox = document.getElementById('alerts_display');
            displayBox.appendChild(alertBox)

            setTimeout(() => {
                displayBox.removeChild(alertBox)
            }, 5000);
        } else {
            alertBox.classList.add('alertBox_error');
            alertBox.innerHTML = I18N.t("alert.error.connection_error");
        }
    };

    xhr.send(sendValue);
}