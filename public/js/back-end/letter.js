$(function () {

    // 初始化
    let curPage = 1;
    // 实现点击文章后返回时记住当前页码
    if (typeof(Storage) !== "undefined") {
        if (sessionStorage.getItem("lp") != null) {
            curPage = sessionStorage.getItem("lp");
        }
    }
    let url = "/api/admin/letters/";
    loadPage(url, curPage);

    $(".pagination").on("click", "a", function () {
        let p = $(this).attr("lp");
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
        ele += '<tr>' +
            '<td class="tdleft"><span style="display: inline;" class="name">' +
            '<a href="/admin/letter/'  + data[i].id + '" class="red" target="_blank">' + data[i].message + '</a></span></td>' +
            '<td>' + data[i].name + '</td>' +
            '<td>' + data[i].email + '</td>' +
            '<td>' + data[i].isRead + '</td>' +
            '<td>' + data[i].time + '</td>' +
            '<td><a href="javascript:void(0);" onclick="delLetter(' + data[i].id + ');">删除</a><br/></td>' +
            '</tr>';
    }
    let letters = $("#letters");
    letters.html(ele);
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
        ele += '<li class="page-item"><a href="javascript:void(0)" lp="' + (parseInt(curPage) - 1) + '">&lsaquo;</a></li>';
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
            ele += '<li class="active"><a href="javascript:void(0)" lp="' + i + '">' + i + '</a></li>';
        } else {
            ele += '<li ><a href="javascript:void(0)" lp="' + i + '">' + i + '</a></li>';
        }
    }

    if (parseInt(curPage) === parseInt(totalPage)) {    // 如果当前页是最后一页
        ele += '<li class="disabled"><span >&rsaquo;</span></li>';
    } else {
        ele += '<li><a href="javascript:void(0)" lp="' + (parseInt(curPage) + 1) + '">&rsaquo;</a></li>';
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
                sessionStorage.setItem("lp", curPage);
            } else {
                if (result.status === 401) {
                    location.href = "/admin/sign-in/";
                }
            }
        }
    });
}


// 删除信件
function delLetter(letId) {
    if (confirm("确认删除此信件吗？\nid =" + letId)) { // 弹出确认框, 点确定再执行下面的代码
        $.ajax({
            url: "/api/admin/letter/delete/" + letId,
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


/*//根据目标页码改变form 的action, 然后提交表单
function pageCondition(targetNum) {
	$("#form").attr("action", "admin/letter/" + targetNum);
	$("#form").submit();
}

//根据输入的页码改变form 的action, 然后提交表单
function _go() {
	var targetNum = $("#pageCode").val(); 
	if (!/^[1-9]\d*$/.test(targetNum)) { //对当前页码进行整数校验
		alert('请输入正确的页码！');
		return;
	}
	if (targetNum > 10) { //判断当前页码是否大于最大页
		alert('请输入正确的页码！');
		return;
	}
	$("#form").attr("action", "admin/letter/" + targetNum);
	$("#form").submit();
}*/

