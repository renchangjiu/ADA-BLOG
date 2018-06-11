$(function () {
    let url = location.href;
    let id = url.slice(url.lastIndexOf("/") + 1);

    $.ajax({
        url: "/api/admin/letter/" + id,
        type: "GET",
        dataType: "json",  //服务器返回的数据类型
        beforeSend: function (request) {
            addTokenHader(request);
        },
        success: function (result) {
            console.log(result);
            if (result.success) {
                let data = result.data;
                if (data.name === "") {
                    data.name = "unknown ";
                }
                if (data.email === "") {
                    data.email = "unknown ";
                }
                $("#name").html(data.name);
                $("#email").html(data.email);
                $("#time").html(data.time);
                $("#message").html(data.message);
            } else {
                alert(result.message);
            }
        }
    });
});