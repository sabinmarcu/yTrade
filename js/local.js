YUI().use("yui", "tabview", "charts", 'io', "json-parse", function(Y) {
    var tabview = new Y.TabView({srcNode:'.dashboard'});tabview.render();    Y.JSON.useNativeParse = false;
    
    start = {
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
    
    Y.all(".dashboard a").each( function(elem) {
        elem.on("click", function(e) {
        e.preventDefault();
                switch (elem.get('id')) {
                    case 'dashboard' :  Y.one("#dashboard-content").empty();  getDashboard(); break;
                    case 'bavailable' : break;
                    case 'bmine' : break;
                    case 'statistics' : break;
                }
        })
    })

    getDashboard = function()   {
        uri = "index.php/chart/index?";
        for(i = 1; i <= chartcount; i++)    {
            Y.one("#dashboard-content").append("<section class='yui3-u-1-3'><div id='chart" + i + "' class='chart'></div></section>");/*
            if (i > 1) uri = uri + '&';
            uri = uri+"start["+i+"][n]="+start[i]['n']+"&start["+i+"][m]="+start[i]['m']+"&start["+i+"][i]="+start[i]['i']+"&start["+i+"][s]="+start[i]['s'];*/
        }            
        Y.on('io:complete', dashComplete, Y); 
        request = Y.io(uri);       
         var sent = Array(0);
         function dashComplete(id, o, args) { 
        var charts = new Array();
        for (i = 1; i <= chartcount; i++)   {
            charts[i] = new Y.Chart({dataProvider:start[i], render:".chart#chart"+i, categoryKey:"time"});
        }
        
        /*
        if (!sent[args[0]]) {
            sent[args[0]] = 1;
            data = Y.JSON.parse(o.responseText);        
            var mychart = new Y.Chart({dataProvider:Y.JSON.parse(o.responseText), render:".chart#"+args[0], categoryKey:"time"});
            exit;
        }*/
        }
    }
    
});