function optionsBox(){
    document.getElementById('addLink').onclick = function(){
        document.getElementById('option_window').classList.add('active_window')
        document.getElementById('addLink_box').classList.add('active_box')

        document.getElementById('addLink_close').onclick = function(){
            document.getElementById('option_window').classList.remove('active_window')
            document.getElementById('addLink_box').classList.remove('active_box')
        }
    }
}