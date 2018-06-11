$(function () {
    loadData();
    add();

    $("#txtCat").focus(function () {  //处理添加分类的input 得到焦点的事件
        $("#addCatError").css("color", "gray");
    });

});


function loadData() {
    $.ajax({
        url: "/api/tags",
        type: "GET",
        dataType: "json",  //服务器返回的数据类型
        success: function (result) {
            if (result.success) {
                // console.log();
                let ele = '';
                data = result.data;
                for (let i = 0; i < data.length; i++) {
                    ele += '<tr>' +
                        '<td class="tdleft">' +
                        '<span style="display: inline;" class="name"><a href="article/tag/${cat.catId }/1" class="red" target="_blank">' + data[i].name + '</a></span>' +
                        '<span style="display: none;" class="input" id="' + data[i].id + '">' +
                        '<input type="text" maxlength="30" id="' + data[i].id + '">' +
                        '</td>' +
                        '<td></td>' +
                        '<td><a href="javascript:void(0);" onclick="del(' + data[i].id + ');">删除</a><br/></td>' +
                        '</tr>';
                }
                let content = $("#content");
                content.html(ele);
            } else {
                alert(result.message);
            }
        }
    });
}




//添加标签
function add() {
    $("#btnAdd").click(function () {
        let tagName = $("#txtCat").val();
        if (!tagName || tagName.trim().length < 2 || tagName.trim().length > 20) {
            $("#addCatError").css("color", "red");
        } else {
            $.ajax({
                url: "/api/admin/tag/insert",
                dataType: "json",
                type: "post",
                data: {"name": tagName},
                beforeSend: function (request) {
                    addTokenHader(request);
                },
                success: function (result) {
                    if (result.success) {
                        location.reload();
                    } else {
                        alert(result.message);
                    }
                }
            });
        }
    });
}

function del(tagId) {
    if (confirm("确认删除此标签吗?\ntag-id = " + tagId)) {
        $.ajax({
            url: "/api/admin/tag/delete/" + tagId,
            type: "POST",
            dataType: "json",
            beforeSend: function (request) {
                addTokenHader(request);
            },
            success: function (result) {
                if (result.success) {
                    location.reload();
                } else {
                    if (result.status === 401) {
                        location.href = "/admin/sign-in";
                    } else {
                        alert(result.message);
                    }
                }
            }
        });
    }
}