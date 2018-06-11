$(function () {
    page();
    search();

    $("#name").val(localStorage.getItem("comment-name"));

});

function page() {
    let articleId = location.href.slice(location.href.lastIndexOf("/") + 1);

    let url = "/api/comments/" + articleId + "/";

    // 初始化
    let curPage = 1;
    // 点击文章后返回时记住当前页码,
    if (typeof(Storage) !== "undefined") {
        if (sessionStorage.getItem("cp" + articleId) != null) {
            curPage = sessionStorage.getItem("cp" + articleId);
        }
    }

    loadPage(url, curPage, articleId);

    $("#comments-page").on("click", "a", function () {
        let p = $(this).attr("cp" + articleId);
        loadPage(url, p, articleId);

        // 页面滚动到 #panel
        $('html, body').animate({
            scrollTop: $("#panel").offset().top
        }, 200);
    });
}


/*
    构造展示数据的dom, 需要根据文档的结构来修改
    data : 以数组组合的对象
  */
function loadData(data) {

    let ele = '';
    for (let i = 0; i < data.length; i++) {
        ele += '<li class="comment even thread-even depth-1">' +
            '<div class="comment_body contents">' +
            '<div class="main shadow">' +
            '<div class="commentinfo">' +
            '<section class="commeta">' +
            '<div class="shang">' +
            '<h6>#' + data[i].floor + ' - ' + data[i].name + '</h6>' +
            '</div></section></div>' +
            '<div class="body">' +
            '<p>' + data[i].content + '</p>' +
            '</div>' +
            '<div class="xia info">' +
            '<span><time>' + data[i].time + '</time></span></div></div></div>' +
            '</li>';
    }
    $("#comments").html(ele);
}

/*
    构造显示分页效果的dom
    curPage ; 目标页码
    totalPage : 总页数
  */
function loadPageBar(curPage, totalPage, articleId) {
    let pageEle = $("#comments-page");
    pageEle.empty();
    let ele = '';
    if (parseInt(curPage) === 1) {    // 如果当前页是第一页
        ele += '<span class="prev page-numbers""><</span>';
    } else {
        ele += '<a class="prev page-numbers" href="javascript:void(0)" cp' + articleId + '="' + (parseInt(curPage) - 1) + '"><</a>';
    }

    let begin = 0;
    let end = 0;

    if (totalPage <= 5) {
        begin = 1;
        end = totalPage;
    } else {              // 如果总页数大于５
        begin = parseInt(curPage) - 2;
        end = parseInt(curPage) + 2;
        if (begin < 1) {
            begin = 1;
            end = 5
        }
        if (end > totalPage) {
            begin = totalPage - 4;
            end = totalPage;
        }
    }

    for (let i = begin; i <= end; i++) {
        if (parseInt(curPage) === i) {
            ele += '<span aria-current="page" class="page-numbers current" cp' + articleId + '="' + i + '">' + i + '</span>';
        } else {
            ele += '<a class="page-numbers" href="javascript:void(0)" cp' + articleId + '="' + i + '"> ' + i + ' </a>';
        }
    }

    if (parseInt(curPage) === parseInt(totalPage)) {    // 如果当前页是最后一页
        ele += '<span class="prev page-numbers" href="javascript:void(0)">></span>';
    } else {
        ele += '<a class="prev page-numbers" href="javascript:void(0)" cp' + articleId + '="' + (parseInt(curPage) + 1) + '">></a>';
    }
    pageEle.html(ele);
}

function loadPage(url, curPage, articleId) {
    $.ajax({
        url: url + curPage,
        dataType: "json",
        success: function (result) {
            if (result.success) {
                if (result.data != null) {
                    $("#commentComment").text(result.data.totalCount + "条评论");
                    loadData(result.data.objects);
                    loadPageBar(result.data.curPage, result.data.totalPage, articleId);
                    // 把当前页码放入sessionStorage, 每次请求后更新
                    sessionStorage.setItem("cp" + articleId, curPage);
                } else {
                    $("#commentComment").text("现在还没有评论哦 ~~~~(>_<)~~~~");
                }
            }
        }
    });

}

