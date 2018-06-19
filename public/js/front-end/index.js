$(function () {
    pageHelper();
    search();
});


// 分页
function pageHelper() {
    // 初始化
    let curPage = 1;
    // 点击文章后返回时记住当前页码,
    if (typeof(Storage) !== "undefined") {
        if (sessionStorage.getItem("p") != null) {
            curPage = sessionStorage.getItem("p");
        }
    }
    let url = "/api/articles/";
    loadPage(url, curPage);


    $(".pagination").on("click", "a", function () {
        let p = $(this).attr("p");
        loadPage(url, p);
        scrollTo(0, 0);
    });
}


/*
    构造展示数据的dom, 需要根据文档的结构来修改
    data : 以数组组合的对象
  */
function loadData(data) {
    let ele = '';
    for (let i = 0; i < data.length; i++) {
        ele += '' +
            '<div class="posts-list js-posts">' +
            '<div class="post post-layout-list" data-aos="fade-up">' +
            '<div class="status_list_item icon_kyubo">' +
            '<div class="status_user" style="background-image: url(/public/images/article-list/' + getRandomInteger(1, 17) + '.jpg)">' +
            '<div class="status_section">' +
            '<p class="section_p" style="color: #8d908b;font-family: 楷体,serif">' +
            data[i].editTime + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +
            data[i].readNum + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + data[i].tags +
            '</p>' +
            '<a href="' + '/article/' + data[i].id + '" class="status_btn">' + data[i].title + '</a>' +
            '<p class="section_p">' + data[i].summary + '</p>' +
            '</div></div></div></div>';
    }
    $("#content").html(ele);
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
        ele += '<li class="page-item"><a href="javascript:void(0)" p="' + (parseInt(curPage) - 1) + '">&lsaquo;</a></li>';
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
            ele += '<li class="active"><a href="javascript:void(0)" p="' + i + '">' + i + '</a></li>';
        } else {
            ele += '<li ><a href="javascript:void(0)" p="' + i + '">' + i + '</a></li>';
        }
    }

    if (parseInt(curPage) === parseInt(totalPage)) {    // 如果当前页是最后一页
        ele += '<li class="disabled"><span >&rsaquo;</span></li>';
    } else {
        ele += '<li><a href="javascript:void(0)" p="' + (parseInt(curPage) + 1) + '">&rsaquo;</a></li>';
    }
    pageEle.html(ele);
}

function loadPage(url, curPage) {
    $.ajax({
        url: url + curPage,
        type: "GET",
        dataType: "json",  //服务器返回的数据类型
        success: function (result) {
            if (result.success) {
                loadData(result.data.objects);
                loadPageBar(result.data.curPage, result.data.totalPage);
                // 把当前页码放入sessionStorage, 每次请求后更新
                sessionStorage.setItem("p", curPage);
            } else {
                if (result.status === 450) {
                    sessionStorage.setItem("p", 1);
                }
            }

        }
    });

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

// [min, max]
function getRandomInteger(min, max) {
    return Math.floor(Math.random() * (max - min + 1) + min);
}


