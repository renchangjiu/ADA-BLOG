$(function () {
    tagList();
    loadOriginalArticle();
    submit();
});


function loadOriginalArticle() {
    let url = location.href;
    let articleId = url.slice(url.lastIndexOf("/") + 1);

    $.ajax({
        url: "/api/article/" + articleId,
        dataType: "json",
        async: false,
        success: function (result) {
            if (result.success) {
                $("#title").val(result.data.title);
                $("#summary").val(result.data.summary);
                $("#content").val(result.data.content);
            } else {
                alert(result.message);
            }
        }
    });
}



function submit() {
    let url = location.href;
    let articleId = url.slice(url.lastIndexOf("/") + 1);

    $("#submit").click(function () {

        let formData = "";
        let title = $("#title").val();
        let summary = $("#summary").val();
        // 使用Tinymce后，通过 tinyMCE.get("content").getContent()获取textarea值
        let content = tinyMCE.get("content").getContent();
        let tagsArr = $("#tags").val();
        if (tagsArr == null) {
            alert("请至少选择一个标签");
            return;
        }
        let tags = "";
        for (let i = 0; i < tagsArr.length; i++) {
            tags += (tagsArr[i] + "|");
        }
        // 截掉结尾的 | 字符
        tags = tags.slice(0, -1);
        // id=45&title=t&summary=s&content=c&tags=2|3
        formData = "id=" + articleId + "&title=" + title + "&summary=" + summary + "&content=" + content + "&tags=" + tags;
        console.log(formData);
        $.ajax({
            url: "/api/admin/article/update",
            type: "POST",
            data: formData,
            dataType: "json",
            beforeSend: function (request) {
                addTokenHader(request);
            },
            success: function (result) {
                console.log(result);
                if (result.success) {
                    window.close();
                } else {
                    if (result.status === 401) {
                        location.href = "/admin/sign-in";
                    } else {
                        alert(result.message);
                    }
                }
            }
        });
    });
}

// 获得标签列表, 构造dom
function tagList() {
    $.ajax({
        url: "/api/tags",
        dataType: "json",
        success: function (result) {
            if (result.success) {
                let tagSelect = $("#tags");
                let data = result.data;
                let options = '';
                let len = data.length;
                for (let i = 0; i < len; i++) {
                    options += '<option id="t' + data[i].id +  '" value="' + data[i].id + '">' + data[i].name + '</option>';
                }
                tagSelect.append(options);
            }
        }
    });
}
