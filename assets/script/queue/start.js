let cloneTimes = 1;
console.log($);
// document.body.addEventListener('custom', () => {console.log('custom event')});

function addSetting() {
    const settings = document.getElementsByClassName('message-setting');
    const clone = settings[0].cloneNode(true);
    clone.id = `message-setting-${cloneTimes}`;
    document.body.dispatchEvent(new Event('custom'));
    document.getElementById('settings-stack').appendChild(clone);
    const num = document.querySelector('#'+ clone.id).querySelector('#job_num');
    num.innerHTML = ++cloneTimes;
}

function closeSetting(elem) {
    if (document.getElementsByClassName('message-setting').length > 1) {
        elem.parentElement.parentElement.parentElement.remove();
        cloneTimes--;
    }
}

function start() {
    let settings = [];

    document.querySelectorAll('.message-setting').forEach((settingHtml) => {
        settings.push({
            'sending_time': settingHtml.querySelector('#sending_time').value,
            'message': settingHtml.querySelector('#message').value
        })
    });

    document.querySelector('#start_sending_settings').value = JSON.stringify(settings)
    document.querySelector('form[name="start_sending"]').submit();
}