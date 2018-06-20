$(function () {
    readCache();
    tagList();
    submit();

});

function submit() {
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
        $.ajax({
            url: "/api/admin/article/publish",
            type: "POST",
            data: {"title": title, "summary": summary, "tags": tags, "content": content},
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
                    options += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                }
                tagSelect.append(options);
            }
        }
    });
}

// 缓存文章到本地
function writeCache() {
    let title = $("#title").val();
    let summary = $("#summary").val();
    let content = tinyMCE.get("content").getContent();
    localStorage.setItem("article-title", title);
    localStorage.setItem("article-summary", summary);
    localStorage.setItem("article-content", content);
}


// 清空本地缓存的文章
function clearCache() {
    localStorage.removeItem("article-title");
    localStorage.removeItem("article-summary");
    localStorage.removeItem("article-content");
}

// 读取本地缓存
function readCache() {
    let title = localStorage.getItem("article-title");
    let summary = localStorage.getItem("article-summary");
    let content = localStorage.getItem("article-content");
    $("#title").val(title);
    $("#summary").val(summary);
    $("#content").val(content);
}
