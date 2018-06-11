$(function () {

    // 初始化
    let curPage = 1;
    // 实现点击链接后返回时记住当前页码
    if (typeof(Storage) !== "undefined") {
        if (sessionStorage.getItem("acp") != null) {
            curPage = sessionStorage.getItem("acp");
        }
    }
    let url = "/api/admin/comments/";
    loadPage(url, curPage);

    $(".pagination").on("click", "a", function () {
        let p = $(this).attr("acp");
        loadPage(url, p);
    });

});

/*
    构造展示数据的dom, 需要根据文档的结构来修改
    data : 以数组组合的对象
  */
function loadData(data) {
    // console.log();
    let ele = '';
    for (let i = 0; i < data.length; i++) {
        ele += '<tr class="altitem"><td class="tdleft"><i style="color: red;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RE:</i>' +
            '<a href="/article/' + data[i].artId + '" target=_blank>' + data[i].artTitle + '</a></td>' +
            '<td>' + data[i].name + '&' + data[i].ip + '</td>' +
            '<td>' + data[i].time + '</td>' +
            '<td><a class="del" href="javascript:void(0);" onclick="delComment(' + data[i].id + ');">删除</a></td>' +
            '</tr>' +
            '<tr><td colspan=4><div class="recon"  style="text-align: center">' + data[i].content + '</div></td></tr>';
    }
    let comments = $("#content");
    comments.html(ele);
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
        ele += '<li class="page-item"><a href="javascript:void(0)" acp="' + (parseInt(curPage) - 1) + '">&lsaquo;</a></li>';
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
            ele += '<li class="active"><a href="javascript:void(0)" acp="' + i + '">' + i + '</a></li>';
        } else {
            ele += '<li ><a href="javascript:void(0)" acp="' + i + '">' + i + '</a></li>';
        }
    }

    if (parseInt(curPage) === parseInt(totalPage)) {    // 如果当前页是最后一页
        ele += '<li class="disabled"><span >&rsaquo;</span></li>';
    } else {
        ele += '<li><a href="javascript:void(0)" acp="' + (parseInt(curPage) + 1) + '">&rsaquo;</a></li>';
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
            if (result.success) {
                loadData(result.data.objects);
                loadPageBar(result.data.curPage, result.data.totalPage);
                // 把当前页码放入sessionStorage, 每次请求后更新
                sessionStorage.setItem("acp", curPage);
            } else {
                if (result.status === 401) {
                    location.href = "/admin/sign-in/";
                }
            }
        }
    });
}


// 删除评论
function delComment(id) {
    if (confirm("确认删除此评论吗？\nid =" + id)) { // 弹出确认框, 点确定再执行下面的代码
        $.ajax({
            url: "/api/admin/comment/delete/" + id,
            dataType: "json",
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
}


