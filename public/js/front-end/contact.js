$(function () {
    $("#submit").click(function () {
        let submitBtn = $("#submit");

        if (!validateTextarea()) {
            // $("#errors").html("message 是必填项");
            alert("message 是必填项");
            return;
        }
        let formName = $("#name").val();
        let formEmail = $("#email").val();
        let formMessage = $("#message").val();

        submitBtn.attr("disabled", true);
        submitBtn.val("发送中...");
        $.ajax({
            url: "/api/letter/send",
            type: "POST",
            data: {"name": formName, "email": formEmail, "message": formMessage},
            dataType: "json",  //服务器返回的数据类型
            success: function (result) {
                submitBtn.attr("disabled", false);
                submitBtn.val("Send");
                if (result.success) {
                    submitBtn.attr("disabled", false);
                    submitBtn.val("Send");
                    $("#message").val(" ");
                    // alert(result.message);
                    $("#resultMessage").text(result.message);
                    $('body,html').animate({
                        scrollTop: 0
                    }, 1000);
                } else {
                    alert(result.message);
                }
            }
        });
    });
});

function validateTextarea() {
    let result = true;
    let text = $("#message").val();

    if (text === "") {
        result = false;
    }
    return result;
}
