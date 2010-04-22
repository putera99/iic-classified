
var preClassName = "";
function list_sub_nav(sortname){

	  window.top.frames['leftFrame'].outlookbar.getbytitle(sortname);
	  window.top.frames['leftFrame'].outlookbar.getdefaultnav(sortname);

}

    function list_sub_detail(Id, item){
        if (preClassName != "") {
            $byid(preClassName).className = "left_back"
        }
        if ($byid(Id).className == "left_back") {
            $byid(Id).className = "left_back_onclick";
            outlookbar.getbyitem(item);
            preClassName = Id;
        }
    }


    function outlook(){
        this.titlelist = new Array();
        this.itemlist = new Array();
        this.addtitle = addtitle;
        this.additem = additem;
        this.getbytitle = getbytitle;
        this.getbyitem = getbyitem;
        this.getdefaultnav = getdefaultnav
    }

    function theitem(intitle, insort, inkey, inisdefault,listtype,ifrlink){
        this.sortname = insort;
        this.key = inkey;
        this.title = intitle;
        this.isdefault = inisdefault;
		this.listtype = listtype;
		this.ifrlink = ifrlink;

    }

    function addtitle(intitle, sortname, inisdefault,listtype,ifrlink){
	//lee99 add 2 subject for ifram listtype 1 is ifram default is 0;
        outlookbar.itemlist[outlookbar.titlelist.length] = new Array();
        outlookbar.titlelist[outlookbar.titlelist.length] = new theitem(intitle, sortname, 0, inisdefault,listtype,ifrlink);
        return (outlookbar.titlelist.length - 1)
    }

    function additem(intitle, parentid, inkey){
        if (parentid >= 0 && parentid <= outlookbar.titlelist.length) {
            insort = "item_" + parentid;
            outlookbar.itemlist[parentid][outlookbar.itemlist[parentid].length] = new theitem(intitle, insort, inkey, 0,0,'');
            return (outlookbar.itemlist[parentid].length - 1)
        }
        else
            additem = -1
    }

    function getdefaultnav(sortname){
    var output = "";
	var idvalue='';
	var titlevalue='';
	var showdefault = true;//lee99加上默认第一组数据
        for (i = 0; i < outlookbar.titlelist.length; i++) {
            if (outlookbar.titlelist[i].isdefault == 1 && outlookbar.titlelist[i].sortname == sortname) {
		if(idvalue=='' && showdefault==true){
		 idvalue="left_nav_" + i;
		 titlevalue=outlookbar.titlelist[i].title;
		}
                output += "<div class=list_tilte id=sub_sort_" + i + " onclick=\"hideorshow('sub_detail_" + i + "')\">";
                output += "<span>" + outlookbar.titlelist[i].title + "</span>";
                output += "</div><div class=list_detail id=sub_detail_" + i + ">";
				//生成标题和外框
				if(outlookbar.titlelist[i].listtype!=1){//如果为1的则为框架应用,否则就是链接列表
					output += "<ul>";
					for (j = 0; j < outlookbar.itemlist[i].length; j++) {
						output += "<li id=" + outlookbar.itemlist[i][j].sortname + j + " onclick=\"changeframe('" + outlookbar.itemlist[i][j].title + "','" + outlookbar.titlelist[i].title + "','" + outlookbar.itemlist[i][j].key + "')\"><a href=#>" + outlookbar.itemlist[i][j].title + "</a></li>"
					}
					output += "</ul>";
				}else{
					output += "<iframe  src='"+outlookbar.titlelist[i].ifrlink+"' frameborder='0' width='100%' height='100%' ></iframe>";
				}
				//alert(outlookbar.titlelist[i].listtype);
				output += "</div>";
				//alert(output);
            }
        }
        $byid('left_nav_right').innerHTML = output

		if(idvalue!=''  && showdefault==true){
			list_sub_detail(idvalue,titlevalue);//lee99加上默认第一组数据
		}
    }

    function getbytitle(sortname){
        var output = "<ul>";

        for (i = 0; i < outlookbar.titlelist.length; i++) {
            if (outlookbar.titlelist[i].sortname == sortname) {
                output += "<li id=left_nav_" + i + " onclick=\"list_sub_detail('"+"left_nav_" + i+"','" + outlookbar.titlelist[i].title + "')\" class=left_back>" + outlookbar.titlelist[i].title + "</li>"
            }
        }

        output += "</ul>";
        $byid('left_nav_left').innerHTML = output

    }

    function getbyitem(item){
        var output = "";
        for (i = 0; i < outlookbar.titlelist.length; i++) {
            if (outlookbar.titlelist[i].title == item) {
                output = "<div class=list_tilte id=sub_sort_" + i + " onclick=\"hideorshow('sub_detail_" + i + "')\">";
                output += "<span>" + outlookbar.titlelist[i].title + "</span>";
                output += "</div>";
                output += "<div class=list_detail id=sub_detail_" + i + " style='display:block;'>";
				//生成标题和外框
				if(outlookbar.titlelist[i].listtype!=1){//如果为1的则为框架应用,否则就是链接列表
				output += "<ul>";
					for (j = 0; j < outlookbar.itemlist[i].length; j++) {
						output += "<li id=" + outlookbar.itemlist[i][j].sortname + "_" + j + " onclick=\"changeframe('" + outlookbar.itemlist[i][j].title + "','" + outlookbar.titlelist[i].title + "','" + outlookbar.itemlist[i][j].key + "')\"><a href=#>" + outlookbar.itemlist[i][j].title + "</a></li>"
					}
					output += "</ul>";
				}else{
					output += "<iframe  src='"+outlookbar.titlelist[i].ifrlink+"' frameborder='0' width='100%' height='100%' ></iframe>";
				}
				output += "</div>"
            }
        }
        $byid('left_nav_right').innerHTML = output
    }

    function changeframe(item, sortname, src){
        if (src != "") {
            window.top.frames['mainFrame'].TabPaneadd('user_tab',item,src,1);
        }
    }

    function hideorshow(divid){
        subsortid = "sub_sort_" + divid.substring(11);
        if ($byid(divid).style.display == "none") {
            $byid(divid).style.display = "block";
            $byid(subsortid).className = "list_tilte"
        }
        else {
            $byid(divid).style.display = "none";
            $byid(subsortid).className = "list_tilte_onclick"
        }
    }

