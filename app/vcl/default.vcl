/** 
 * 后端web服务器
 */
backend webserver {
    .host = "127.0.0.1";
    .port = "99";
}

/** 
 * 允许清除操作的客户
 */
acl purge {
    "localhost";
    "127.0.0.1";
    "192.168.1.0"/24;
}
/** 
 * 
 */
/** 
 * 接受请求部分
 */
sub vcl_recv {

    remove req.http.X-Forwarded-For;
    set req.http.X-Forwarded-For = client.ip;

    /** 
     * 验证清除请求来源
     */
    if (req.request == "PURGE") {
        if (!client.ip ~ purge) {
            error 405 "Not allowed.";
        }
        return (lookup);
    }

    /** 
     * 设置后端//所有站点都在同一个web
     */
    set req.backend = webserver;
    #return (lookup);

/** 
 * 如果请求域匹配
 */

    if (req.http.host ~ "^www\.beingfunchina\.com$") 
    {
    //return (pass);

#        if (req.http.Authorization) 
#        {
#            /* Not cacheable by default */
#            return (pass);
#        }

    /** 
     * 选择需要被缓存页面
     */
	if ( req.url ~ "^\/Classified\/index.php" )
	{
	return (lookup);
	}
	elseif ( req.url ~ "\.(js|css|gif|jpg|png|shtml)$" )
	{
	    return (lookup);
	}
	else
	{
	    return (pass);
	}
    }
	
	if (req.http.host ~ "^www\.xn--fiQs8Sxv8ApmA\.com$") 
    {

	if (req.url ~ "^\/Classified\/index.php" )
	{
	return (lookup);
	}
	elseif ( req.url ~ "\.(js|css|gif|jpg|png|shtml)$" )
	{
	    return (lookup);
	}
	else
	{
	    return (pass);
	}
    }

    return (pass);
}


/** 
 * 命中以后
 */
sub vcl_hit 
{
    if (req.request == "PURGE") 
    {
        set obj.ttl = 0s;
        error 200 "Purged.";
    }
    return (deliver);
}

/** 
 * 命中丢失？
 */
sub vcl_miss 
{
    if (req.request == "PURGE")
    {
        error 404 "Not in cache.";
    }
    return (fetch);
}

/*抓取到的数据.*/
sub vcl_fetch 
{
    if (!obj.cacheable)
    {
        return (pass);
    }

#    set obj.ttl = 240s;
    set obj.ttl = 60s;
    return (deliver);
}
