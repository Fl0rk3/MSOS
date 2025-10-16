window.addEventListener('DOMContentLoaded', function () {
    const savedAlert = sessionStorage.getItem('settings_alert');
    if (savedAlert) {
        const {success, message} = JSON.parse(savedAlert);

        const alertBox = document.createElement("div");
        alertBox.classList.add('alertBox');
        alertBox.classList.add(success ? 'alertBox_success' : 'alertBox_error');
        alertBox.innerHTML = message;

        const displayBox = document.getElementById('alerts_display');
        displayBox.appendChild(alertBox);

        setTimeout(() => displayBox.removeChild(alertBox), 5000);

        // Clear so it doesn’t show again on next reload
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