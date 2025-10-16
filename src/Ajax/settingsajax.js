document.getElementById('viewSettings_button').addEventListener('click', function () {

    settingFields = document.getElementsByClassName('option_window_viewSettings_select');
    let settingValues = {};
    for (const settingField of settingFields) {
        const select = settingField.querySelector('select');
        const id = select.id;
        settingValues[id] = select.value;
    }

    const sendValue = new URLSearchParams(settingValues).toString();

    const xhr = new XMLHttpRequest();

    xhr.open('POST', './../src/backend/Scraper/OptionsBox/settingsScraperUpdate.php', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        if (xhr.status === 200) {


            phpResponse = JSON.parse(xhr.response)
            if (phpResponse['success']) {
                sessionStorage.setItem('settings_alert', JSON.stringify({
                    success: true,
                    message: phpResponse.message
                }));

                window.location.reload();
            } else {
                document.getElementById('option_window').classList.remove('active_window')
                document.getElementById('viewSettings_box').classList.remove('active_box')
                const alertBox = document.createElement("div");
                alertBox.classList.add('alertBox');
                alertBox.classList.add('alertBox_error');
                alertBox.innerHTML = phpResponse['message'];

                const displayBox = document.getElementById('alerts_display');
                displayBox.appendChild(alertBox)

                setTimeout(() => {
                    displayBox.removeChild(alertBox)
                }, 5000);
            }
        } else {
            alertBox.classList.add('alertBox_error');
            alertBox.innerHTML = "Connection error. Please try again."
        }
    };

    xhr.send(sendValue);
});