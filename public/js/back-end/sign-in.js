$(function () {
    isNeedCaptcha();

    $("#submit").click(function () {
        let submitBtn = $("#submit");

        let formData = $("#form").serialize() + "&captchaId=" + sessionStorage.getItem("captchaId");
        $.ajax({
            url: "/api/admin/sign-in",
            type: "POST",
            data: formData,
            dataType: "json",
            success: function (result) {
                console.log(result);
                if (result.success) {
                    sessionStorage.removeItem("needCaptcha");
                    localStorage.setItem("token", result.data);
                    location.href = "/admin";
                } else {
                    sessionStorage.setItem("needCaptcha", "yes");
                    isNeedCaptcha();
                    alert(result.data);
                    $("#captcha").val("");
                }
            }
        });
    });
});

function hideCaptcha() {
    $("#captchaSection").css("display", "none");
}


function isNeedCaptcha() {
    let isNeedCaptcha = sessionStorage.getItem("needCaptcha");
    if (isNeedCaptcha != null) {
        $("#captchaSection").css("display", "inline");
        getCaptcha();
    }

}


function getCaptcha() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/api/captcha",
        success: function (result) {
            console.log(result);
            let src = "data:image/jpeg;base64," + result.data.imgBase64;
            $("#imgCaptcha").attr("src", src);
            sessionStorage.setItem("captchaId", result.data.captchaId);
        }
    });
}





