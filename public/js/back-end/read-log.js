$(function () {

    // 初始化
    let curPage = 1;
    // 实现点击文章后返回时记住当前页码
    if (typeof(Storage) !== "undefined") {
        if (sessionStorage.getItem("rlp") != null) {
            curPage = sessionStorage.getItem("rlp");
        }
    }
    let url = "/api/admin/read-logs/";
    loadPage(url, curPage);

    $(".pagination").on("click", "a", function () {
        let p = $(this).attr("rlp");
        loadPage(url, p);
    });

});

/*
    构造展示数据的dom, 需要根据文档的结构来修改
    data : 以数组组合的对象
  */
function loadData(data) {
    let ele = '';
    for (let i = 0; i < data.length; i++) {
        ele += '<tr>' +
            '<td class="tdleft"><a href="/article/' + data[i].artId + '">' + data[i].artTitle + '</td>' +
            '<td class="tdleft">' + data[i].time + '</td>' +
            '<td class="tdleft">' + data[i].ip + '</td>' +
            '</tr>';
    }
    let content = $("#content");
    content.html(ele);
}


/*
    构造显示分页效果的dom
    curPage ; 目标页码
    totalPage : 总页数
  */
function loadPageBar(curPage, totalPage) {
    let pageEle = $(".pagination");
    pageEle.empty();
    let ele = '';
    if (parseInt(curPage) === 1) {    // 如果当前页是第一页
        ele += '<li class="disabled"><span >&lsaquo;</span></li>';
    } else {
        ele += '<li class="page-item"><a href="javascript:void(0)" rlp="' + (parseInt(curPage) - 1) + '">&lsaquo;</a></li>';
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
            ele += '<li class="active"><a href="javascript:void(0)" rlp="' + i + '">' + i + '</a></li>';
        } else {
            ele += '<li ><a href="javascript:void(0)" rlp="' + i + '">' + i + '</a></li>';
        }
    }

    if (parseInt(curPage) === parseInt(totalPage)) {    // 如果当前页是最后一页
        ele += '<li class="disabled"><span >&rsaquo;</span></li>';
    } else {
        ele += '<li><a href="javascript:void(0)" rlp="' + (parseInt(curPage) + 1) + '">&rsaquo;</a></li>';
    }
    pageEle.html(ele);
}

function loadPage(url, curPage) {
    $.ajax({
        url: url + curPage,
        type: "GET",
        dataType: "json",  //服务器返回的数据类型
        beforeSend: function (request) {
            addTokenHader(request);
        },
        success: function (result) {
            // console.log(result);
            if (result.success) {
                loadData(result.data.objects);
                loadPageBar(result.data.curPage, result.data.totalPage);
                // 把当前页码放入sessionStorage, 每次请求后更新
                sessionStorage.setItem("rlp", curPage);
            } else {
                if (result.status === 401) {
                    location.href = "/admin/sign-in/";
                }
            }
        }
    });
}
