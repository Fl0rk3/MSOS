window.addEventListener('DOMContentLoaded', async function () {
    const savedAlert = sessionStorage.getItem('settings_alert');
    if (savedAlert) {
        const {message} = JSON.parse(savedAlert);

        const alertBox = document.createElement("div");
        alertBox.classList.add('alertBox');
        alertBox.classList.add('alertBox_success');
        await I18N.ready;
        alertBox.innerHTML = I18N.t(message);

        const displayBox = document.getElementById('alerts_display');
        displayBox.appendChild(alertBox);

        setTimeout(() => displayBox.removeChild(alertBox), 5000);

        sessionStorage.removeItem('settings_alert');
    }
});

function linksPopUp() {
    document.getElementById('addLink').onclick = function () {
        document.getElementById('option_window').classList.add('active_window')
        document.getElementById('addLink_box').classList.add('active_box')

        document.getElementById('addLink_close').onclick = function () {
            document.getElementById('option_window').classList.remove('active_window')
            document.getElementById('addLink_box').classList.remove('active_box')
        }
    }
}

function settingsPopUp() {
    document.getElementById('viewSettings').onclick = function () {
        document.getElementById('option_window').classList.add('active_window')
        document.getElementById('viewSettings_box').classList.add('active_box')

        document.getElementById('viewSettings_close').onclick = function () {
            document.getElementById('option_window').classList.remove('active_window')
            document.getElementById('viewSettings_box').classList.remove('active_box')
        }
    }
}