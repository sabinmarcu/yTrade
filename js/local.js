YUI().use("yui", "tabview", "charts", 'io', "json-parse", function(Y) {
    var tabview = new Y.TabView({srcNode:'.dashboard'});tabview.render(); 
    
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
    start[1]["ago"] = 60*60*2;
    start[1]["div"] = 10;
    start[1]["cur"] = "EURCHF";
    
    start[2] = new Array();
    start[2]["ago"] = 60*60*5;
    start[2]["div"] = 15;
    start[2]["cur"] = "RONEUR";
    
    start[3] = new Array();
    start[3]["ago"] = 60*60*4;
    start[3]["div"] = 5;
    start[3]["cur"] = "GBPUSD";
    
    start[4] = new Array();
    start[4]["ago"] = 60*60*8;
    start[4]["div"] = 2;
    start[4]["cur"] = "EURUSD";
    
    
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
        if (currentTab != elem.get('id'))   {
            currentTab = elem.get('id');
            sync();            
        }
        })
    })

    getDashboard = function()   {
        uri = "/index.php/chart/index?";   
        for(i = 1; i <= start.length - 1; i++)    {
           
                Y.one("#dashboard-content").append("<section class=''><div class='chart-wrapper'><div id='chart" + i + "' class='chart'></div></div></section>");
                 if (i > 1) uri = uri + '&';
                 uri = uri+"start["+i+"][ago]="+start[i]['ago']+"&start["+i+"][cur]="+start[i]['cur']+"&start["+i+"][div]="+start[i]['div'];
        }            
        
        Y.on('io:complete', dashComplete, Y); 
        Y.on('io:failure', ajaxFail, Y); 
        request = Y.io(uri);    
        function dashComplete(id, o, args) { 
            headers = o.getAllResponseHeaders();
            if (headers.indexOf("refresh: 0") > 0) return false;
            var charts = new Array();
           response = Y.JSON.parse(o.responseText) ;
            for (i = 1; i <= start.length-1; i++)   { 
            charts[i] = new Y.Chart({dataProvider:response[i], render:".chart#chart"+i, categoryKey:"time"});
            }
        }
        function ajaxFail(id, o, args)  {
            alert(o.status);
        }
    }
    
    
    
    
});