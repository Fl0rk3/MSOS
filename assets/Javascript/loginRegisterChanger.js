function loginRegisterChanger() {
    document.getElementById('login_button').onclick = function () {
        const selected = this.className.includes('selected');

        if(!selected){
            this.classList.add('selected')
            document.getElementById('register_button').classList.remove('selected')
            document.getElementById('loginBox_register').classList.remove('loginBox_selected')
            document.getElementById('loginBox_login').classList.add('loginBox_selected')
        }
    }

    document.getElementById('register_button').onclick = function () {
        const selected = this.className.includes('selected');

        if(!selected){
            this.classList.add('selected')
            document.getElementById('login_button').classList.remove('selected')
            document.getElementById('loginBox_login').classList.remove('loginBox_selected')
            document.getElementById('loginBox_register').classList.add('loginBox_selected')
        }
    }
}