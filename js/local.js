YUI().use("yui", "tabview", "charts", 'io', "json-parse", function(Y) {
    var tabview = new Y.TabView({srcNode:'.dashboard'});tabview.render();    Y.JSON.useNativeParse = false;
    
    startinp = {
            1 : [
                {'time' : "12:35PM", 'rate': 0.83},
                {'time' : "12:36PM", 'rate': 0.82},
                {'time' : "12:37PM", 'rate': 0.84},
                {'time' : "12:38PM", 'rate': 0.85},
                {'time' : "12:39PM", 'rate': 0.1}
            ],
            2 : [
                {'time' : "12:35PM", 'rate': 0.83},
                {'time' : "12:36PM", 'rate': 0.24},
                {'time' : "12:37PM", 'rate': 0.26},
                {'time' : "12:38PM", 'rate': 0.25},
            ],
            3 : [
                {'time' : "12:35PM", 'rate': 0.83},
                {'time' : "12:36PM", 'rate': 0.82}
            ]
    };
    
    
    start = new Array();
    
    start[1] = new Array();
    start[1]["ago"] = 60*60*40;
    start[1]["div"] = 10;
    start[1]["cur"] = "EURCHF";
    
    start[2] = new Array();
    start[2]["ago"] = 60*60*50;
    start[2]["div"] = 15;
    start[2]["cur"] = "RONEUR";
    
    start[3] = new Array();
    start[3]["ago"] = 60*60*60;
    start[3]["div"] = 5;
    start[3]["cur"] = "GBPUSD";
    
    chartcount = 3;
    
    
    dcauc = new Array();
    
    dcauc[1] = new Array();
    dcauc[1]['who'] = 'velici.vlad';
    dcauc[1]['what'] = 'RON';
    dcauc[1]['quant'] = "500";
    dcauc[1]['req'] = 'CHF';
    
    dcauc[2] = new Array();
    dcauc[2]['who'] = 'velici.vlad';
    dcauc[2]['what'] = 'aud';
    dcauc[2]['quant'] = "250";
    dcauc[2]['req'] = 'GBP';
    
    currentTab = null;
    
    sync = function()   {        
                switch (currentTab) {
                    case 'dashboard' :  Y.one("#dashboard-content").empty();  getDashboard(); break;
                    case 'bavailable' : break;
                    case 'bmine' : break;
                    case 'statistics' : break;
                }
          setTimeout('sync()', 360000);
    }
    
    Y.all(".dashboard a").each( function(elem) {
        elem.on("click", function(e) {
        e.preventDefault();
        currentTab = elem.get('id');
        sync();
        })
    })

    getDashboard = function()   {
        uri = "/index.php/chart/index?";   
        for(i = 1; i <= chartcount; i++)    {
            Y.one("#dashboard-content").append("<section class='yui3-u-1-3'><div id='chart" + i + "' class='chart'></div></section>");
            if (i > 1) uri = uri + '&';
            uri = uri+"start["+i+"][ago]="+start[i]['ago']+"&start["+i+"][cur]="+start[i]['cur']+"&start["+i+"][div]="+start[i]['div'];
        }            
        Y.on('io:complete', dashComplete, Y); 
        request = Y.io(uri);    
        function dashComplete(id, o, args) { 
            var charts = new Array();
            for (i = 1; i <= chartcount; i++)   {
                charts[i] = new Y.Chart({dataProvider:o.responseText[i], render:".chart#chart"+i, categoryKey:"time"});
            }
        }
    }
    
});