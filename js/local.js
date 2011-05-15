YUI().use("yui", "tabview", "charts", 'io', "json-parse", function(Y) {
    var tabview = new Y.TabView({srcNode:'.dashboard'});tabview.render();
    
    start = new Array();;
    
    start[1] = new Array();    
    start[1]['m'] = "GBPCHF";
    start[1]['n'] = 5;
    start[1]['i'] = 2;
    start[1]['s'] = "12:52PM";
    
    start[2] = new Array();
    start[2]['m'] = "USDRON";
    start[2]['n'] = 10;
    start[2]['i'] = 5;
    start[2]['s'] = "13:41PM";
    
    start[3] = new Array();
    start[3]['m'] = "AUDCHF";
    start[3]['n'] = 5;
    start[3]['i'] = 1;
    start[3]['s'] = "5:52AM";
    
    chartcount = 3;
    uri = "index.php/chart/index?";
    for(i = 1; i <= chartcount; i++)    {
        Y.one("#dashboard-content").append("<section class='yui3-u-1-3'><div id='chart" + i + "' class='chart'></div></section>");
        if (i > 1) uri = uri + '&';
        uri = uri+"start["+i+"][n]="+start[i]['n']+"&start["+i+"][m]="+start[i]['m']+"&start["+i+"][i]="+start[i]['i']+"&start["+i+"][s]="+start[i]['s'];
    }    
    
     Y.on('io:complete', complete, Y,[ node.get('id')]); 
     request = Y.io(uri);
    
    var sent = Array(0);
    function complete(id, o, args) { 
        
        alert("id : " + id + "<br>O : " + o.responseText);
        
        /*
        if (!sent[args[0]]) {
            sent[args[0]] = 1;
            data = Y.JSON.parse(o.responseText);        
            var mychart = new Y.Chart({dataProvider:Y.JSON.parse(o.responseText), render:".chart#"+args[0], categoryKey:"time"});
            exit;
        }
        
        */
    };
    
});