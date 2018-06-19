$(function () {
    search();
    loadArticle();
    submit();
});


function loadArticle() {
    let url = location.href;
    let articleId = url.slice(url.lastIndexOf("/") + 1);

    $.ajax({
        url: "/api/article/" + articleId,
        dataType: "json",
        success: function (result) {
            if (result.success) {
                // console.log(result);
                let data = result.data;
                $("title").html(data.title);
                $("#title").html(data.title);
                $("#editTime").text(data.editTime);
                $("#content").html(data.content);
                loadTags(data.tags);
            }
        }
    });
}


function subSummary(summary) {
    if (summary.length > 138) {
        return summary.substr(0, 138) + "...";
    }
    return summary;
}

function loadTags(tags) {
    let tagsEle = $("#tags");
    let tagsArr = tags.split(" ");
    for (let tag of tagsArr) {
        let aEle = "<a href='#'>" + tag + "&nbsp;&nbsp;</a>";
        tagsEle.append(aEle);
    }
}


function search() {
    let searchInputEle = $("#search-input");
    let searchResultEle = $("#search_filtered");

    searchInputEle.keyup(function () {
        $.ajax({
            url: "/api/search",
            type: "POST",
            data: {"input": searchInputEle.val()},
            dataType: "json",  //服务器返回的数据类型
            success: function (result) {
                // console.log(result);
                searchResultEle.empty();
                let data = result.data;
                let len = data.length;
                let ele = '';
                for (let i = 0; i < len; i++) {
                    ele += '<li><a href="/article/' + data[i].id + '" target="_blank">' + data[i].title + '</a></li>';
                }
                searchResultEle.append(ele);
            }
        });
    });
}

// 提交评论
function submit() {
    $("#submit").click(function () {
        // 从url里获取当前文章的id
        let artId = location.href.slice(location.href.lastIndexOf("/") + 1);
        let submitBtn = $("#submit");

        if (!validateTextarea()) {
            $("#errorMsg").text("评论不能为空哦");
            return;
        }
        let name = $("#name").val();
        let content = $("#comment-content").val();
        submitBtn.attr("disabled", true);
        submitBtn.val("发送中...");
        $.ajax({
            url: "/api/comment/send",
            type: "POST",
            data: {"artId": artId, "name": name, "content": content},
            dataType: "json",
            success: function (result) {
                submitBtn.attr("disabled", false);
                submitBtn.val("发送");
                if (result.success) {
                    submitBtn.attr("disabled", true);
                    let step = 5;
                    let interval = setInterval(function () {
                        submitBtn.val(step);
                        step--;
                        if (step === -1) {
                            clearInterval(interval);
                            submitBtn.attr("disabled", false);
                            submitBtn.val("发送");
                        }
                    }, 1000);
                    $("#comment-content").val("");
                    $("#errorMsg").empty();
                    localStorage.setItem("comment-name", name);
                    // 页面滚动到 #panel
                    $('html, body').animate({
                        scrollTop: $("#panel").offset().top
                    }, 500);
                    page();     // 重新分页
                } else {
                    alert(result.message);
                }
            }
        });
    });

}

function validateTextarea() {
    let result = false;
    let text = $("#comment-content").val();
    if (text !== "") {
        result = true;
    }
    return result;
}



















