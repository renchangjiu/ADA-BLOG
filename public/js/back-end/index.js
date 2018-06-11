$(function () {

    // 初始化
    let curPage = 1;
    // 实现点击文章后返回时记住当前页码
    if (typeof(Storage) !== "undefined") {
        if (sessionStorage.getItem("ap") != null) {
            curPage = sessionStorage.getItem("ap");
        }
    }
    let url = "api/admin/articles/";
    loadPage(url, curPage);

    $(".pagination").on("click", "a", function () {
        let p = $(this).attr("ap");
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
        ele += '<tr><td class="tdleft">\n' +
            '<a href="/article/' + data[i].id + '" target="_blank">' + data[i].title + '</a></td>' +
            '<td class="tdleft"><span class="gray">&nbsp;' + data[i].editTime + '</span></td>' +
            '<td class="tdleft"><a href="..." target="_blank">' + data[i].tags + '</a></td>' +
            '<td>' + data[i].readNum + '</td>' +
            '<td><a href="/admin/article/update/' + data[i].id + '" target="_blank">编辑</a> |' +
            '<a id="d" class="delArticle" href="javascript:void(0);" onclick="delArticle(' + data[i].id + ')">删除</a> |' +
            '<a href="javascript:void(0);" class="cat">修改标签</a>' +
            '</td></tr>';

    }
    let articles = $("#articles");
    articles.html(ele);
}


// 修改文章前, 加载要修改的文章
function getOriginalArticle(articleId) {
    $.ajax({
        url: "/api/article/" + articleId,
        dataType: "json",
        async: false,
        success: function (result) {
            if (result.success) {
                let objJson = JSON.stringify(result.data);
                sessionStorage.setItem("originalArticle", objJson);
            }
        }
    });
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
        ele += '<li class="page-item"><a href="javascript:void(0)" ap="' + (parseInt(curPage) - 1) + '">&lsaquo;</a></li>';
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
            ele += '<li class="active"><a href="javascript:void(0)" ap="' + i + '">' + i + '</a></li>';
        } else {
            ele += '<li ><a href="javascript:void(0)" ap="' + i + '">' + i + '</a></li>';
        }
    }

    if (parseInt(curPage) === parseInt(totalPage)) {    // 如果当前页是最后一页
        ele += '<li class="disabled"><span >&rsaquo;</span></li>';
    } else {
        ele += '<li><a href="javascript:void(0)" ap="' + (parseInt(curPage) + 1) + '">&rsaquo;</a></li>';
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
                sessionStorage.setItem("ap", curPage);
            } else {
                if (result.status === 401) {
                    location.href = "/admin/sign-in";
                }
            }
        }
    });
}


// 删除文章, ajax
function delArticle(id) {
    console.log(id);
    if (confirm("确认删除此文章吗？\nid =" + id)) { // 弹出确认框, 点确定再执行下面的代码
        $.ajax({
            url: "/api/admin/article/delete/" + id,
            type: "POST", // 默认GET
            dataType: "json", // 服务器返回的数据类型
            async: true,
            cache: false, // 是否允许缓存, 默认true
            beforeSend: function (request) {
                addTokenHader(request);
            },
            success: function (result) {
                // console.log(result);
                if (result.success) {
                    location.reload();
                } else {
                    alert(result.message);
                }
            }
        });
    }
}


/*
// 如果分类下拉框的被选项改变
function getArticles() {
	$("#selCat").change(function() {
		// option 被改变
		let catId = $("#selCat option:selected").val();
		$.ajax({
			url:"/admin/art/find",
			type:"POST",             // 默认GET
			data:{"catId": catId},
			dataType:"json",  //服务器返回的数据类型
			async:true,
			cache:false, //是否允许缓存, 默认true
			success:function(result) {
				if (result.status == 0) {
					alert(result.data);
				} else {
					let data = result.data;
					for (let i = 0; i < data.length; i++) {
					}
				}
			}
		});
	});
}
*/
/*

// 展示分类下拉框
function getOptions() {
	
	//取得上一次选择的分类id, 用于回显下拉框
	let catId = $("#catId").val();
	$.ajax({
		url : "cat/list",
		type : "POST",
		dataType : "json",
		async : true,
		cache : false, // 是否允许缓存, 默认true
		success : function(result) {
            let status = resolveStatus(result);
            let data = resolveData(result);
			if (status == 0) {
				alert("服务器异常, 请稍后再试, 或私信博主");
			} else {
				let $selCat = $("#selCat");
				// 添加节点后的结果
				// <option value="0">全部</option>
//				${user.sex=="1"?'selected':''
				
				for (let i = 0; i < data.length; i++) {
					let isSelected = "";
					if(catId == data[i].catId) {
						isSelected = "selected";
					}
					let opEle = $('<option value="' + data[i].catId + '"' + isSelected + '>'
							+ data[i].catName + '</option>');
					$selCat.append(opEle);
				}
			}
		}
	});
}
*/


/*

//根据目标页码改变form 的action, 然后提交表单
function pageCondition(targetNum) {
	$("#form").attr("action", "admin/index/" + targetNum);
	$("#form").submit();
}

//根据输入的页码改变form 的action, 然后提交表单
function _go() {
	let targetNum = $("#pageCode").val(); 
	if (!/^[1-9]\d*$/.test(targetNum)) { //对当前页码进行整数校验
		alert('请输入正确的页码！');
		return;
	}
	if (targetNum > 10) { //判断当前页码是否大于最大页
		alert('请输入正确的页码！');
		return;
	}
	$("#form").attr("action", "admin/index/" + targetNum);
	$("#form").submit();
}
*/
