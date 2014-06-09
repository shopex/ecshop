window.onload = function () {
    setInputCheckedStatus();

    $("js-pre-step").onclick = function() {
        location.href="./index.php?lang=" + getAddressLang() + "&step=welcome";
    };
    $("js-recheck").onclick = function () {
        location.href="./index.php?lang=" + getAddressLang() + "&step=check";
    };
    $("js-submit").onclick = function () {
        this.form.action="index.php?lang=" + getAddressLang() + "&step=setting_ui" + "&ui=" + $('userinterface').value;
    };
};