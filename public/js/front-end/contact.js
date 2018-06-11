$(function () {
    $("#submit").click(function () {
        let submitBtn = $("#submit");

        if (!validateTextarea()) {
            // $("#errors").html("message 是必填项");
            alert("message 是必填项");
            return;
        }
        let formData = $("#form").serialize();
        submitBtn.attr("disabled", true);
        submitBtn.val("发送中...");
        $.ajax({
            url: "/api/letter/send",
            type: "POST",
            data: formData,
            dataType: "json",  //服务器返回的数据类型
            success: function (result) {
                console.log(result);
                submitBtn.attr("disabled", false);
                submitBtn.val("Send");
                if (result.success) {
                    // console.log(result);
                    submitBtn.attr("disabled", false);
                    submitBtn.val("Send");
                    $("#message").val(" ");
                    alert(result.message);
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
